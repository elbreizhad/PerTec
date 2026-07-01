<?php
/**
 * Routeur pour le serveur de développement PHP intégré :
 *   php -S 127.0.0.1:8000 router.php
 *
 * Sert les fichiers statiques existants (assets), sinon délègue à index.php.
 * En production (Apache + .htaccess ou Nginx), ce fichier est inutile.
 */
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = __DIR__ . $path;
if ($path !== '/' && is_file($file)) {
    return false; // laisse le serveur intégré servir le fichier
}
require __DIR__ . '/index.php';
