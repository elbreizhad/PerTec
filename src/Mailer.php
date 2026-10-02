<?php
declare(strict_types=1);

/**
 * Envoi d'emails avec pièce jointe, sans dépendance externe.
 *
 * Réglages lus dans la table settings (page Paramètres) — jamais dans un
 * fichier, pour que les déploiements ne les écrasent pas :
 *   smtp_host, smtp_port, smtp_secure (ssl|tls|none), smtp_user, smtp_pass,
 *   mail_from, mail_from_name.
 * Sans serveur SMTP renseigné, on se rabat sur la fonction mail() de PHP.
 */
class Mailer
{
    /**
     * @param array $attachments liste de ['name' => 'x.pdf', 'type' => 'application/pdf', 'data' => binaire]
     * @throws RuntimeException en cas d'échec (message lisible)
     */
    public static function send(string $to, string $subject, string $body, array $attachments = [], ?string $replyTo = null): void
    {
        $s = Setting::all();
        $to = trim($to);
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException("Adresse email du destinataire invalide : « $to ».");
        }
        $from = trim((string) ($s['mail_from'] ?? '')) ?: trim((string) ($s['landlord_email'] ?? ''));
        if (!filter_var($from, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException("Renseignez l'adresse d'expédition (Paramètres → Envoi des emails).");
        }
        $fromName = trim((string) ($s['mail_from_name'] ?? '')) ?: trim((string) ($s['landlord_name'] ?? ''));
        $replyTo  = $replyTo ?: (trim((string) ($s['landlord_email'] ?? '')) ?: null);

        [$headers, $mime] = self::build($from, $fromName, $to, $subject, $body, $attachments, $replyTo);

        if (trim((string) ($s['smtp_host'] ?? '')) !== '') {
            self::smtp($s, $from, $to, "To: <$to>\r\nSubject: " . self::encodeHeader($subject) . "\r\n" . $headers, $mime);
            return;
        }
        if (!@mail($to, self::encodeHeader($subject), $mime, $headers, '-f' . $from)) {
            throw new RuntimeException("La fonction mail() du serveur a échoué. Renseignez un serveur SMTP dans les Paramètres.");
        }
    }

    /** Construit les en-têtes et le corps MIME (texte + pièces jointes). */
    private static function build(string $from, string $fromName, string $to, string $subject, string $body, array $attachments, ?string $replyTo): array
    {
        $boundary = 'pertec_' . bin2hex(random_bytes(12));
        $domain = substr(strrchr($from, '@'), 1);
        $headers = 'From: ' . ($fromName !== '' ? self::encodeHeader($fromName) . ' ' : '') . "<$from>\r\n"
            . ($replyTo ? "Reply-To: <$replyTo>\r\n" : '')
            . 'Date: ' . date('r') . "\r\n"
            . 'Message-ID: <' . bin2hex(random_bytes(10)) . '@' . $domain . ">\r\n"
            . "MIME-Version: 1.0\r\n"
            . "Content-Type: multipart/mixed; boundary=\"$boundary\"";

        $text = str_replace(["\r\n", "\r"], "\n", $body);
        $mime = "--$boundary\r\n"
            . "Content-Type: text/plain; charset=UTF-8\r\n"
            . "Content-Transfer-Encoding: base64\r\n\r\n"
            . chunk_split(base64_encode(str_replace("\n", "\r\n", $text)), 76, "\r\n");
        foreach ($attachments as $a) {
            $name = preg_replace('/[^A-Za-z0-9._-]/', '_', (string) $a['name']);
            $mime .= "--$boundary\r\n"
                . 'Content-Type: ' . ($a['type'] ?? 'application/octet-stream') . "; name=\"$name\"\r\n"
                . "Content-Transfer-Encoding: base64\r\n"
                . "Content-Disposition: attachment; filename=\"$name\"\r\n\r\n"
                . chunk_split(base64_encode((string) $a['data']), 76, "\r\n");
        }
        $mime .= "--$boundary--\r\n";
        return [$headers, $mime];
    }

    private static function encodeHeader(string $v): string
    {
        return preg_match('/[^\x20-\x7E]/', $v) ? '=?UTF-8?B?' . base64_encode($v) . '?=' : $v;
    }

    /** Dialogue SMTP minimal (SSL implicite, STARTTLS ou clair) avec AUTH LOGIN. */
    private static function smtp(array $s, string $from, string $to, string $headers, string $mime): void
    {
        $host   = trim((string) $s['smtp_host']);
        $secure = (string) ($s['smtp_secure'] ?? 'ssl');
        $port   = (int) ($s['smtp_port'] ?? 0) ?: ($secure === 'ssl' ? 465 : 587);
        $remote = ($secure === 'ssl' ? 'ssl://' : 'tcp://') . $host . ':' . $port;

        $fp = @stream_socket_client($remote, $errno, $errstr, 20, STREAM_CLIENT_CONNECT,
            stream_context_create(['ssl' => ['SNI_enabled' => true, 'peer_name' => $host]]));
        if (!$fp) {
            throw new RuntimeException("Connexion SMTP impossible à $host:$port ($errstr).");
        }
        stream_set_timeout($fp, 30);

        $read = function () use ($fp): array {
            $out = '';
            while (($line = fgets($fp, 1024)) !== false) {
                $out .= $line;
                if (strlen($line) < 4 || $line[3] === ' ') break;
            }
            return [(int) substr($out, 0, 3), trim($out)];
        };
        // $label : ce qui est affiché en cas d'erreur (jamais les identifiants).
        $cmd = function (string $c, array $ok, ?string $label = null) use ($fp, $read): string {
            if ($c !== '') fwrite($fp, $c . "\r\n");
            [$code, $msg] = $read();
            if (!in_array($code, $ok, true)) {
                fclose($fp);
                $shown = $label ?? $c;
                throw new RuntimeException("Erreur SMTP" . ($shown !== '' ? " ($shown)" : '') . " : $msg");
            }
            return $msg;
        };

        $ehlo = 'EHLO ' . (gethostname() ?: 'localhost');
        $cmd('', [220]);
        $cmd($ehlo, [250]);
        if ($secure === 'tls') {
            $cmd('STARTTLS', [220]);
            if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                fclose($fp);
                throw new RuntimeException('Échec du chiffrement STARTTLS.');
            }
            $cmd($ehlo, [250]);
        }
        $user = (string) ($s['smtp_user'] ?? '');
        if ($user !== '') {
            $cmd('AUTH LOGIN', [334]);
            $cmd(base64_encode($user), [334], 'identifiant');
            $cmd(base64_encode((string) ($s['smtp_pass'] ?? '')), [235],
                stripos($host, 'gmail.com') !== false
                    ? "identifiant ou mot de passe refusé — Gmail exige un « mot de passe d'application » (compte Google → Sécurité → Validation en 2 étapes → Mots de passe des applications), pas votre mot de passe habituel"
                    : 'identifiant ou mot de passe refusé');
        }
        $cmd("MAIL FROM:<$from>", [250]);
        $cmd("RCPT TO:<$to>", [250, 251]);
        $cmd('DATA', [354]);
        // Échappement des lignes commençant par un point (RFC 5321 §4.5.2).
        $data = preg_replace('/^\./m', '..', $headers . "\r\n\r\n" . $mime);
        fwrite($fp, $data . "\r\n.\r\n");
        $cmd('', [250], 'envoi du message');
        fwrite($fp, "QUIT\r\n");
        fclose($fp);
    }
}
