<?php
/**
 * Cloudflare Turnstile verification helper
 * https://developers.cloudflare.com/turnstile/
 */

/**
 * Vérifie le token Turnstile auprès de Cloudflare
 *
 * @param string $token Le token cf-turnstile-response du formulaire
 * @param string $secretKey La clé secrète Turnstile
 * @param string|null $ip Optionnel : IP du client pour vérification additionnelle
 * @return array ['success' => bool, 'error' => string|null]
 */
function verify_turnstile(string $token, string $secretKey, ?string $ip = null): array {
    if (empty($token)) {
        return ['success' => false, 'error' => 'Token CAPTCHA manquant.'];
    }

    if (empty($secretKey)) {
        // Si pas de clé configurée, on laisse passer (fallback honeypot)
        return ['success' => true, 'error' => null];
    }

    $data = [
        'secret' => $secretKey,
        'response' => $token,
    ];

    if ($ip) {
        $data['remoteip'] = $ip;
    }

    $ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error || $httpCode !== 200) {
        // En cas d'erreur réseau, on laisse passer (fail-open)
        error_log("Turnstile verification failed: $error (HTTP $httpCode)");
        return ['success' => true, 'error' => null];
    }

    $result = json_decode($response, true);

    if (!$result || !isset($result['success'])) {
        return ['success' => true, 'error' => null]; // fail-open
    }

    if (!$result['success']) {
        $errorCodes = $result['error-codes'] ?? [];
        return [
            'success' => false,
            'error' => 'Vérification anti-spam échouée. Veuillez réessayer.'
        ];
    }

    return ['success' => true, 'error' => null];
}

/**
 * Récupère la clé du site Turnstile depuis les settings
 * @return string|null
 */
function get_turnstile_site_key(): ?string {
    static $key = null;

    if ($key === null) {
        $settings_file = __DIR__ . '/../admin/settings.json';
        if (file_exists($settings_file)) {
            $settings = json_decode(file_get_contents($settings_file), true) ?: [];
            $key = $settings['turnstile_site_key'] ?? '';
        } else {
            $key = '';
        }
    }

    return $key ?: null;
}

/**
 * Récupère la clé secrète Turnstile depuis les settings
 * @return string|null
 */
function get_turnstile_secret_key(): ?string {
    static $key = null;

    if ($key === null) {
        $settings_file = __DIR__ . '/../admin/settings.json';
        if (file_exists($settings_file)) {
            $settings = json_decode(file_get_contents($settings_file), true) ?: [];
            $key = $settings['turnstile_secret_key'] ?? '';
        } else {
            $key = '';
        }
    }

    return $key ?: null;
}
