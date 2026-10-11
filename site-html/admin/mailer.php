<?php
/**
 * Classe SMTP simple pour Pertec
 * Envoie des emails via SMTP sans dépendance externe
 */

class PertecMailer {
    private $host;
    private $port;
    private $user;
    private $pass;
    private $from;
    private $fromName;
    private $socket;
    private $debug = false;

    public function __construct($settings = []) {
        $this->host = $settings['smtp_host'] ?? '';
        $this->port = (int)($settings['smtp_port'] ?? 587);
        $this->user = $settings['smtp_user'] ?? '';
        $this->pass = $settings['smtp_pass'] ?? '';
        $this->from = $settings['smtp_from'] ?? $settings['contact_email'] ?? '';
        $this->fromName = $settings['smtp_from_name'] ?? $settings['site_name'] ?? 'Pertec';
    }

    /**
     * Vérifie si SMTP est configuré
     */
    public function isConfigured() {
        return !empty($this->host) && !empty($this->user) && !empty($this->pass);
    }

    /**
     * Envoie un email
     */
    public function send($to, $subject, $body, $replyTo = null) {
        // Si SMTP pas configuré, utiliser mail()
        if (!$this->isConfigured()) {
            return $this->sendWithMail($to, $subject, $body, $replyTo);
        }

        try {
            return $this->sendWithSmtp($to, $subject, $body, $replyTo);
        } catch (Exception $e) {
            // Fallback sur mail() en cas d'erreur SMTP
            error_log("SMTP Error: " . $e->getMessage());
            return $this->sendWithMail($to, $subject, $body, $replyTo);
        }
    }

    /**
     * Envoi via SMTP
     */
    private function sendWithSmtp($to, $subject, $body, $replyTo = null) {
        // Connexion
        $this->socket = @fsockopen(
            ($this->port == 465 ? 'ssl://' : '') . $this->host,
            $this->port,
            $errno,
            $errstr,
            30
        );

        if (!$this->socket) {
            throw new Exception("Connexion SMTP impossible: $errstr ($errno)");
        }

        stream_set_timeout($this->socket, 30);
        $this->getResponse();

        // EHLO
        $this->sendCommand("EHLO " . gethostname());

        // STARTTLS pour port 587
        if ($this->port == 587) {
            $this->sendCommand("STARTTLS");
            stream_socket_enable_crypto($this->socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            $this->sendCommand("EHLO " . gethostname());
        }

        // AUTH LOGIN
        $this->sendCommand("AUTH LOGIN");
        $this->sendCommand(base64_encode($this->user));
        $this->sendCommand(base64_encode($this->pass));

        // MAIL FROM
        $this->sendCommand("MAIL FROM:<{$this->from}>");

        // RCPT TO
        $this->sendCommand("RCPT TO:<$to>");

        // DATA
        $this->sendCommand("DATA");

        // Headers
        $headers = "From: {$this->fromName} <{$this->from}>\r\n";
        $headers .= "To: $to\r\n";
        $headers .= "Subject: $subject\r\n";
        if ($replyTo) {
            $headers .= "Reply-To: $replyTo\r\n";
        }
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "Date: " . date('r') . "\r\n";
        $headers .= "\r\n";

        // Corps du message
        $message = $headers . $body . "\r\n.";
        $this->sendCommand($message);

        // QUIT
        $this->sendCommand("QUIT");

        fclose($this->socket);
        return true;
    }

    /**
     * Envoi via mail() (fallback)
     */
    private function sendWithMail($to, $subject, $body, $replyTo = null) {
        $headers = "From: {$this->fromName} <{$this->from}>\r\n";
        if ($replyTo) {
            $headers .= "Reply-To: $replyTo\r\n";
        }
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

        return @mail($to, $subject, $body, $headers);
    }

    /**
     * Envoie une commande SMTP
     */
    private function sendCommand($command) {
        fputs($this->socket, $command . "\r\n");
        return $this->getResponse();
    }

    /**
     * Lit la réponse SMTP
     */
    private function getResponse() {
        $response = '';
        while ($line = fgets($this->socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == ' ') break;
        }

        $code = (int)substr($response, 0, 3);

        if ($code >= 400) {
            throw new Exception("Erreur SMTP: $response");
        }

        return $response;
    }
}
