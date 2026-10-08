<?php
declare(strict_types=1);

/**
 * Signature électronique simple du bail (art. 1366-1367 du Code civil).
 *
 * Chaque partie signe en dessinant sa signature. On conserve la preuve :
 * image, nom, date, adresse IP, navigateur et empreinte SHA-256 du contrat
 * tel qu'il a été signé. Si le bail est modifié ensuite, l'empreinte change :
 * les signatures ne sont plus valables pour la nouvelle version.
 * Quand les deux parties ont signé, le PDF signé est archivé tel quel.
 */
class LeaseSignature
{
    public const ROLES = ['bailleur' => 'Le bailleur', 'locataire' => 'Le locataire'];
    public const TOKEN_DAYS = 30;

    public static function template(array $lease): string
    {
        return $lease['lease_type'] === 'meuble' ? 'documents/contrat_meuble' : 'documents/contrat';
    }

    /** Empreinte du contrat (rendu sans les signatures). */
    public static function contractHash(array $lease, array $settings): string
    {
        $html = render_template(self::template($lease), ['lease' => $lease, 'settings' => $settings, 'forPdf' => true, 'forHash' => true]);
        return hash('sha256', $html);
    }

    /** Signatures enregistrées, indexées par rôle. */
    public static function forLease(int $leaseId): array
    {
        try {
            $out = [];
            foreach (Database::all('SELECT * FROM lease_signatures WHERE lease_id = ?', [$leaseId]) as $r) $out[$r['role']] = $r;
            return $out;
        } catch (Throwable $e) {
            return [];
        }
    }

    /** Signatures valables pour la version actuelle du contrat. */
    public static function valid(array $lease, array $settings): array
    {
        $all = self::forLease((int) $lease['id']);
        if (!$all) return [];
        // Cache par requête : le contrat affiche les signatures à plusieurs endroits.
        static $cache = [];
        $key = (int) $lease['id'] . ':' . md5(serialize([$lease, $settings, $all]));
        if (!isset($cache[$key])) {
            $hash = self::contractHash($lease, $settings);
            $cache[$key] = array_filter($all, fn($s) => hash_equals($s['doc_hash'], $hash));
        }
        return $cache[$key];
    }

    /**
     * Enregistre la signature d'une partie, puis archive le PDF signé si les deux ont signé.
     * @return bool true si le bail est désormais signé par les deux parties
     * @throws RuntimeException
     */
    public static function sign(array $lease, string $role, string $name, string $image): bool
    {
        if (!isset(self::ROLES[$role])) throw new RuntimeException('Rôle de signataire inconnu.');
        $name = trim(mb_substr($name, 0, 150));
        if ($name === '') throw new RuntimeException('Indiquez le nom du signataire.');
        $prefix = 'data:image/png;base64,';
        $raw = str_starts_with($image, $prefix) ? base64_decode(substr($image, strlen($prefix)), true) : false;
        if ($raw === false || substr($raw, 0, 8) !== "\x89PNG\r\n\x1a\n" || strlen($image) > 400000) {
            throw new RuntimeException('Signature invalide : merci de la redessiner.');
        }

        // La date de signature figure dans le contrat (« Fait à …, le … ») : on la fixe à la 1re signature.
        if (empty($lease['signature_date'])) {
            Database::update('leases', ['signature_date' => date('Y-m-d')], 'id = :id', ['id' => (int) $lease['id']]);
            $lease = Lease::find((int) $lease['id']);
        }
        $settings = Setting::all();
        $hash = self::contractHash($lease, $settings);

        Database::query('DELETE FROM lease_signatures WHERE lease_id = ? AND role = ?', [(int) $lease['id'], $role]);
        Database::insert('lease_signatures', [
            'lease_id'    => (int) $lease['id'],
            'role'        => $role,
            'signer_name' => $name,
            'image'       => $image,
            'signed_at'   => date('Y-m-d H:i:s'),
            'ip'          => substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45),
            'user_agent'  => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 255),
            'doc_hash'    => $hash,
        ]);

        if (count(self::valid($lease, $settings)) === count(self::ROLES)) {
            self::archive($lease, $settings);
            return true;
        }
        return false;
    }

    /** Génère et archive le PDF signé. */
    private static function archive(array $lease, array $settings): void
    {
        if (!Pdf::available()) return;
        $pdf = Pdf::renderDocument(self::template($lease), ['lease' => $lease, 'settings' => $settings]);
        Database::query('DELETE FROM lease_signed_pdf WHERE lease_id = ?', [(int) $lease['id']]);
        Database::insert('lease_signed_pdf', ['lease_id' => (int) $lease['id'], 'pdf' => $pdf, 'sha256' => hash('sha256', $pdf)]);
    }

    public static function signedPdf(int $leaseId): ?array
    {
        try {
            return Database::one('SELECT * FROM lease_signed_pdf WHERE lease_id = ?', [$leaseId]);
        } catch (Throwable $e) {
            return null;
        }
    }

    /** Annule toutes les signatures (pour faire signer une nouvelle version). Le PDF archivé est conservé. */
    public static function reset(int $leaseId): void
    {
        Database::query('DELETE FROM lease_signatures WHERE lease_id = ?', [$leaseId]);
    }

    /** Crée (ou renouvelle) le lien de signature à distance du locataire. */
    public static function newToken(int $leaseId): string
    {
        $token = bin2hex(random_bytes(32));
        Database::update('leases', [
            'sign_token'         => $token,
            'sign_token_expires' => date('Y-m-d H:i:s', strtotime('+' . self::TOKEN_DAYS . ' days')),
        ], 'id = :id', ['id' => $leaseId]);
        return $token;
    }

    /** Bail correspondant à un lien de signature valable, sinon null. */
    public static function leaseForToken(string $token): ?array
    {
        if (!preg_match('/^[a-f0-9]{64}$/', $token)) return null;
        $row = Database::one('SELECT id, sign_token, sign_token_expires FROM leases WHERE sign_token = ?', [$token]);
        if (!$row || !hash_equals((string) $row['sign_token'], $token) || strtotime((string) $row['sign_token_expires']) < time()) return null;
        return Lease::find((int) $row['id']);
    }

    /** URL absolue (pour l'email envoyé au locataire). */
    public static function absoluteUrl(string $path): string
    {
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https';
        return ($https ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . url($path);
    }
}
