<?php
declare(strict_types=1);

/**
 * Espace locataire : connexion sans mot de passe par code à 6 chiffres envoyé
 * par email, et données visibles par le locataire (baux, loyers, quittances,
 * documents). L'administrateur peut voir le même espace sans code (aperçu).
 */
class TenantPortal
{
    public const CODE_MINUTES = 10;   // durée de validité d'un code
    public const MAX_ATTEMPTS = 5;    // essais par code
    public const MAX_CODES    = 3;    // codes envoyés par adresse sur 15 minutes

    /** Locataires (fiches) correspondant à une adresse email. */
    public static function tenantsByEmail(string $email): array
    {
        $email = trim(mb_strtolower($email));
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return [];
        return Database::all('SELECT * FROM tenants WHERE LOWER(TRIM(email)) = ?', [$email]);
    }

    /**
     * Envoie un code si l'adresse correspond à un locataire.
     * Ne révèle jamais si l'adresse existe (même message dans tous les cas).
     * @throws RuntimeException trop de demandes, ou échec d'envoi
     */
    public static function sendCode(string $email): void
    {
        $email = trim(mb_strtolower($email));
        $tenants = self::tenantsByEmail($email);
        if (!$tenants) return;

        $recent = Database::one(
            'SELECT COUNT(*) AS n FROM tenant_login_codes WHERE email = ? AND created_at > ?',
            [$email, date('Y-m-d H:i:s', time() - 900)]
        );
        if ((int) $recent['n'] >= self::MAX_CODES) {
            throw new RuntimeException('Trop de demandes de code. Réessayez dans quelques minutes.');
        }
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Database::insert('tenant_login_codes', [
            'email'      => $email,
            'code_hash'  => password_hash($code, PASSWORD_DEFAULT),
            'expires_at' => date('Y-m-d H:i:s', time() + self::CODE_MINUTES * 60),
            'ip'         => substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45),
        ]);
        $s = Setting::all();
        $name = trim((string) ($s['mail_from_name'] ?? '')) ?: (string) ($s['landlord_name'] ?? '');
        Mailer::send($email, "Votre code de connexion : $code",
            "Bonjour " . $tenants[0]['first_name'] . ",\n\n"
            . "Voici votre code pour accéder à votre espace locataire :\n\n    $code\n\n"
            . "Il est valable " . self::CODE_MINUTES . " minutes. Si vous n'êtes pas à l'origine de cette demande, ignorez cet email.\n\n"
            . "Cordialement,\n$name");
    }

    /**
     * Vérifie le code ; renvoie les identifiants des fiches locataire en cas de succès.
     * @throws RuntimeException message lisible
     */
    public static function verify(string $email, string $code): array
    {
        $email = trim(mb_strtolower($email));
        $code = preg_replace('/\D/', '', $code);
        $row = Database::one(
            'SELECT * FROM tenant_login_codes WHERE email = ? AND used_at IS NULL ORDER BY id DESC LIMIT 1',
            [$email]
        );
        if (!$row || strtotime($row['expires_at']) < time()) {
            throw new RuntimeException('Code expiré ou inexistant : demandez un nouveau code.');
        }
        if ((int) $row['attempts'] >= self::MAX_ATTEMPTS) {
            throw new RuntimeException('Trop d\'essais : demandez un nouveau code.');
        }
        if (strlen($code) !== 6 || !password_verify($code, $row['code_hash'])) {
            Database::query('UPDATE tenant_login_codes SET attempts = attempts + 1 WHERE id = ?', [(int) $row['id']]);
            $left = self::MAX_ATTEMPTS - (int) $row['attempts'] - 1;
            throw new RuntimeException('Code incorrect' . ($left > 0 ? " ($left essai" . ($left > 1 ? 's' : '') . ' restant' . ($left > 1 ? 's' : '') . ').' : '.'));
        }
        Database::query('UPDATE tenant_login_codes SET used_at = NOW() WHERE id = ?', [(int) $row['id']]);
        $ids = array_map('intval', array_column(self::tenantsByEmail($email), 'id'));
        if (!$ids) throw new RuntimeException('Aucun espace locataire pour cette adresse.');
        Database::query('UPDATE tenants SET portal_last_login = NOW() WHERE id IN (' . implode(',', $ids) . ')');
        return $ids;
    }

    /** Fiches locataire de la session (locataire connecté). */
    public static function sessionTenantIds(): array
    {
        return array_map('intval', $_SESSION['tenant_ids'] ?? []);
    }

    /** Baux des fiches locataire, plus récents d'abord. */
    public static function leases(array $tenantIds): array
    {
        if (!$tenantIds) return [];
        $in = implode(',', array_map('intval', $tenantIds));
        $ids = array_column(Database::all("SELECT id FROM leases WHERE tenant_id IN ($in) ORDER BY start_date DESC"), 'id');
        return array_values(array_filter(array_map(fn($id) => Lease::find((int) $id), $ids)));
    }

    /** Le bail / l'échéance / le document appartient-il à l'un de ces locataires ? */
    public static function ownsLease(array $tenantIds, int $leaseId): ?array
    {
        $l = Lease::find($leaseId);
        return $l && in_array((int) $l['tenant_id'], $tenantIds, true) ? $l : null;
    }
    /**
     * Démarches du locataire pour un bail, classées par étape, avec leur état.
     * Chaque tâche : ['label', 'done' (bool), 'hint', 'url' (lien à suivre), 'post' (action à envoyer), 'button'].
     * @return array<string, array<int, array>>
     */
    public static function tasks(array $lease, array $settings, array $payments): array
    {
        $today = date('Y-m-d');
        $edls = Inspection::forLease((int) $lease['id']);
        $tenantSigned = fn(?array $i) => $i && isset(Inspection::valid($i, $lease, $settings)['locataire']);
        $mail = !empty($settings['landlord_email']) ? 'mailto:' . $settings['landlord_email'] : null;

        // --- Avant l'entrée dans le logement ---
        $before = [];
        $leaseSigned = isset(LeaseSignature::valid($lease, $settings)['locataire']);
        $blocked = $lease['lease_type'] === 'meuble' && Lease::contractIssues($lease, $settings)['blocking'];
        $before[] = ['label' => 'Signer le bail', 'done' => $leaseSigned,
            'hint' => $leaseSigned ? 'Votre signature est enregistrée.' : ($blocked ? 'Le bail est en cours de préparation par votre bailleur.' : 'Lisez le contrat et signez-le en ligne.'),
            'url' => !$leaseSigned && !$blocked ? '/locataire/bail/' . $lease['id'] . '/signer' : null, 'button' => 'Signer mon bail',
            'view' => $leaseSigned ? '/locataire/bail/' . $lease['id'] : null, 'viewLabel' => 'Voir mon bail', 'blank' => true];

        if ((float) $lease['deposit_amount'] > 0) {
            $paid = !empty($lease['deposit_paid_date']);
            $before[] = ['label' => 'Verser le dépôt de garantie (' . euros($lease['deposit_amount']) . ')', 'done' => $paid,
                'hint' => $paid ? 'Reçu le ' . fdate($lease['deposit_paid_date']) . '.' : 'À verser au plus tard le ' . fdate(Lease::depositDue($lease)) . ' (jour du début du bail).'];
        }

        $checked = Checklist::checkedKeys((int) $lease['id']);
        foreach (Checklist::applicableItems($lease['lease_type']) as $item) {
            if ($item['group'] !== 'locataire') continue;
            if ($item['key'] === 'garant' && ($lease['guarantee_type'] ?? 'aucune') === 'aucune') continue;
            $done = in_array($item['key'], $checked, true);
            $before[] = ['label' => 'Fournir : ' . (preg_match('/^\p{Lu}\p{Ll}/u', $item['label']) ? mb_strtolower(mb_substr($item['label'], 0, 1)) . mb_substr($item['label'], 1) : $item['label']), 'done' => $done,
                'hint' => $done ? 'Reçu par votre bailleur.' : 'À transmettre à votre bailleur.',
                'url' => !$done ? $mail : null, 'button' => 'Envoyer par email'];
        }

        $first = $payments ? array_reduce($payments, fn($c, $p) => !$c || [$p['period_year'], $p['period_month']] < [$c['period_year'], $c['period_month']] ? $p : $c) : null;
        $firstPaid = $first && $first['status'] === 'paid';
        $before[] = ['label' => 'Payer le premier loyer', 'done' => $firstPaid,
            'hint' => $firstPaid ? 'Payé le ' . fdate($first['paid_date']) . '.' : ($first && $first['due_date'] ? 'Échéance le ' . fdate($first['due_date']) . '.' : 'L\'échéance apparaîtra ci-dessous.')];

        $in = $edls['entree'] ?? null;
        $inDone = $tenantSigned($in);
        $before[] = ['label' => "Faire et signer l'état des lieux d'entrée", 'done' => $inDone,
            'hint' => $inDone ? 'Signé.' : ($in && $in['shared_at'] ? 'Vérifiez chaque pièce, complétez si besoin, puis signez.' : 'Faites le tour du logement pièce par pièce et notez l\'état de chaque élément.'),
            'url' => !$inDone && $in && $in['shared_at'] ? '/locataire/edl/' . $in['id'] : null,
            'post' => !$inDone && (!$in || !$in['shared_at']) ? '/locataire/bail/' . $lease['id'] . '/edl' : null,
            'button' => $in && $in['shared_at'] && !$inDone ? "Faire l'état des lieux" : "Commencer l'état des lieux",
            'view' => $inDone ? '/locataire/edl/' . $in['id'] : null, 'viewLabel' => "Voir l'état des lieux signé"];

        $groups = ["Avant l'entrée dans le logement" => $before];

        // --- Pendant la location ---
        $late = array_filter($payments, fn($p) => $p['status'] !== 'paid' && $p['due_date'] && $p['due_date'] < $today);
        $groups['Pendant la location'] = [
            ['label' => 'Payer le loyer chaque mois', 'done' => !$late,
             'hint' => $late ? count($late) . ' échéance(s) en retard — voir « Mes loyers et quittances ».' : 'Vos loyers sont à jour.'],
        ];

        // --- Départ ---
        $out = $edls['sortie'] ?? null;
        if (!empty($lease['termination_date']) && !($out && $out['shared_at'])) {
            $groups['Départ du logement'] = [
                ['label' => "Faire et signer l'état des lieux de sortie", 'done' => false,
                 'hint' => 'Fin du bail le ' . fdate($lease['termination_date']) . ' : votre bailleur vous proposera un rendez-vous pour l\'état des lieux de sortie.'],
            ];
        }
        if ($out && $out['shared_at']) {
            $outDone = $tenantSigned($out);
            $hasInfo = $out['data']['tenant']['new_address'] !== '' && $out['data']['tenant']['iban'] !== '';
            $groups['Départ du logement'] = [
                ['label' => 'Indiquer votre nouvelle adresse et votre IBAN', 'done' => $hasInfo,
                 'hint' => $hasInfo ? 'Renseignés.' : 'Nécessaires pour vous restituer le dépôt de garantie.',
                 'url' => !$hasInfo ? '/locataire/edl/' . $out['id'] : null, 'button' => 'Compléter'],
                ['label' => "Faire et signer l'état des lieux de sortie", 'done' => $outDone,
                 'hint' => $outDone ? 'Signé.' : 'Vérifiez chaque pièce, complétez si besoin, puis signez.',
                 'url' => !$outDone ? '/locataire/edl/' . $out['id'] : null, 'button' => "Faire l'état des lieux",
                 'view' => $outDone ? '/locataire/edl/' . $out['id'] : null, 'viewLabel' => "Voir l'état des lieux signé"],
            ];
        }
        return $groups;
    }
}
