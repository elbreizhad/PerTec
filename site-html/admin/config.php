<?php
/**
 * Configuration de l'administration Pertec
 * Ce fichier charge la config personnalisée ou les valeurs par défaut
 */

require_once dirname(__DIR__) . '/includes/data.php';

// Identifiants : config.local.php dans le dossier de données (hors site), sinon dans admin/
$config_file = pertec_data_file('config.local.php');
if (!file_exists($config_file)) {
    $config_file = __DIR__ . '/config.local.php';
}
if (file_exists($config_file)) {
    require_once $config_file;
} else {
    // Valeurs par défaut - À PERSONNALISER sur le serveur
    define('ADMIN_USER', 'admin');
    define('ADMIN_PASS', 'pertec2024');
    define('ADMIN_SECRET', 'changez_cette_cle_secrete_123');
}

// Configuration site (toujours définie)
if (!defined('CONTACT_EMAIL')) define('CONTACT_EMAIL', 'contact@pertec.fr');
if (!defined('SITE_NAME')) define('SITE_NAME', 'Pertec');
if (!defined('SITE_URL')) define('SITE_URL', 'https://pertec.fr');

// Chemin vers le fichier de paramètres
define('SETTINGS_FILE', pertec_data_file('settings.json'));
define('SETTINGS_EXAMPLE', __DIR__ . '/settings.example.json');

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Charge les paramètres du site
 */
function get_settings() {
    // Charger settings.json s'il existe
    if (file_exists(SETTINGS_FILE)) {
        $json = file_get_contents(SETTINGS_FILE);
        $settings = json_decode($json, true);
        if ($settings) return $settings;
    }

    // Sinon charger l'exemple et créer le fichier
    if (file_exists(SETTINGS_EXAMPLE)) {
        $json = file_get_contents(SETTINGS_EXAMPLE);
        $settings = json_decode($json, true);
        if ($settings) {
            // Créer le fichier settings.json
            file_put_contents(SETTINGS_FILE, $json);
            return $settings;
        }
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
        'site_url' => 'https://pertec.fr',
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

    // Les visites du propriétaire ne sont pas comptées dans les statistiques
    if (empty($_COOKIE['pertec_nostats'])) {
        setcookie('pertec_nostats', '1', [
            'expires' => time() + 365 * 86400,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
}

/**
 * Échappe le HTML
 */
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}
