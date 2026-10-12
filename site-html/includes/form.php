<?php
/**
 * Formulaires de devis - Pertec.fr
 * À inclure avant toute sortie HTML (démarre la session pour le jeton CSRF).
 * Les formulaires envoient vers /contact-handler.php.
 */
require_once __DIR__ . '/turnstile.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/**
 * Champs cachés : jeton CSRF et piège à robots.
 */
function form_hidden_fields(): string {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($_SESSION['csrf_token']) . '">' . "\n"
         . '          <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">' . "\n";
}

/**
 * Widget anti-spam Cloudflare Turnstile (si configuré dans l'admin).
 */
function form_turnstile(): string {
    $key = get_turnstile_site_key();
    if (!$key) {
        return '';
    }
    return '<div class="form-group full">' . "\n"
         . '            <div class="cf-turnstile" data-sitekey="' . htmlspecialchars($key) . '" data-theme="dark"></div>' . "\n"
         . '          </div>' . "\n"
         . '          <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>' . "\n";
}
