<?php
/**
 * Emplacement des données enregistrées par le site - Pertec.fr
 *
 * Paramètres de l'admin, messages, estimations, statistiques et identifiants
 * sont rangés HORS du dossier public, dans « pertec-data » à côté de lui
 * (ex. /home/compte/pertec-data à côté de /home/compte/public_html).
 * Un déploiement, quel qu'il soit (script, FTP, gestionnaire de fichiers),
 * ne peut donc ni les effacer ni les remplacer, et ils ne sont pas accessibles
 * depuis le web.
 *
 * Si ce dossier ne peut pas être créé, on retombe sur admin/ comme avant.
 * Les fichiers existants dans admin/ sont recopiés automatiquement la première fois.
 */

if (!defined('PERTEC_SITE_ROOT')) {
    define('PERTEC_SITE_ROOT', dirname(__DIR__));
}

function pertec_data_dir(): string {
    static $dir = null;
    if ($dir !== null) {
        return $dir;
    }

    $candidats = [];
    if (getenv('PERTEC_DATA_DIR')) {
        $candidats[] = rtrim(getenv('PERTEC_DATA_DIR'), '/');
    }
    $candidats[] = dirname(PERTEC_SITE_ROOT) . '/pertec-data';

    foreach ($candidats as $candidat) {
        if (is_dir($candidat) || @mkdir($candidat, 0750, true)) {
            if (is_writable($candidat)) {
                return $dir = $candidat;
            }
        }
    }

    return $dir = PERTEC_SITE_ROOT . '/admin';
}

/**
 * Ancien emplacement (dossier admin du site).
 */
function pertec_legacy_path(string $nom): string {
    return PERTEC_SITE_ROOT . '/admin/' . $nom;
}

/**
 * Chemin d'un fichier de données ('settings.json', 'messages.json'…).
 * Recopie l'ancien fichier de admin/ s'il n'existe pas encore au nouvel endroit.
 */
function pertec_data_file(string $nom): string {
    $chemin = pertec_data_dir() . '/' . $nom;
    $ancien = pertec_legacy_path($nom);

    if ($chemin !== $ancien && !file_exists($chemin) && is_file($ancien)) {
        @copy($ancien, $chemin);
    }
    return $chemin;
}

/**
 * Chemin d'un sous-dossier de données ('stats'), créé si besoin,
 * avec recopie des fichiers de l'ancien emplacement.
 */
function pertec_data_subdir(string $nom): string {
    $chemin = pertec_data_dir() . '/' . $nom;
    $ancien = pertec_legacy_path($nom);

    if (!is_dir($chemin)) {
        @mkdir($chemin, 0750, true);
        if ($chemin !== $ancien && is_dir($ancien)) {
            foreach (glob($ancien . '/*') ?: [] as $fichier) {
                if (is_file($fichier)) {
                    @copy($fichier, $chemin . '/' . basename($fichier));
                }
            }
        }
    }
    return $chemin;
}

/**
 * Paramètres enregistrés depuis l'admin (tableau vide si aucun).
 */
function pertec_settings(): array {
    $fichier = pertec_data_file('settings.json');
    if (!is_file($fichier)) {
        return [];
    }
    return json_decode(file_get_contents($fichier), true) ?: [];
}
