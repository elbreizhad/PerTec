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
}
