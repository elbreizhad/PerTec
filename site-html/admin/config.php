<?php
/**
 * Configuration de l'administration Pertec
 * Copiez ce fichier ou modifiez les valeurs selon votre installation
 */

// Sécurité - Changez ces valeurs !
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'pertec2024');  // Changez ce mot de passe !
define('ADMIN_SECRET', 'changez_cette_cle_secrete_123');

// Configuration email
define('CONTACT_EMAIL', 'contact@pertec.fr');
define('SITE_NAME', 'Pertec');
define('SITE_URL', 'https://pertec.fr');

// Chemin vers le fichier de paramètres (JSON)
define('SETTINGS_FILE', __DIR__ . '/settings.json');

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Charge les paramètres du site
 */
function get_settings() {
    if (file_exists(SETTINGS_FILE)) {
        $json = file_get_contents(SETTINGS_FILE);
        return json_decode($json, true) ?: get_default_settings();
    }
    return get_default_settings();
}

/**
 * Paramètres par défaut
 */
function get_default_settings() {
    return [
        'site_name' => 'Pertec',
        'site_tagline' => 'Création de sites internet',
        'contact_email' => 'contact@pertec.fr',
        'contact_phone' => '',
        'address' => 'Evreux, Eure (27)',
        'social_facebook' => '',
        'social_linkedin' => '',
        'social_instagram' => '',
        'analytics_id' => '',
        'smtp_host' => '',
        'smtp_port' => '587',
        'smtp_user' => '',
        'smtp_pass' => '',
        'smtp_from' => 'contact@pertec.fr',
        'smtp_from_name' => 'Pertec',
    ];
}

/**
 * Sauvegarde les paramètres
 */
function save_settings($settings) {
    $json = json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    return file_put_contents(SETTINGS_FILE, $json) !== false;
}

/**
 * Vérifie si l'utilisateur est connecté
 */
function is_logged_in() {
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Vérifie les credentials
 */
function check_login($user, $pass) {
    return $user === ADMIN_USER && $pass === ADMIN_PASS;
}

/**
 * Redirige si non connecté
 */
function require_login() {
    if (!is_logged_in()) {
        header('Location: index.php');
        exit;
    }
}

/**
 * Échappe le HTML
 */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
