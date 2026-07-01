<?php
declare(strict_types=1);

/**
 * Applique les migrations de base de données en ligne de commande (accès SSH) :
 *   php scripts/migrate.php
 *
 * Note : les migrations s'exécutent DÉJÀ automatiquement à chaque chargement du
 * site (Migrator::run() dans index.php). Ce script n'est utile que pour forcer
 * la mise à jour manuellement, par exemple juste après un déploiement en SSH.
 */

$root = dirname(__DIR__);
require $root . '/src/App.php';
require $root . '/src/Database.php';
require $root . '/src/Migrator.php';

App::boot();

if (!App::isInstalled()) {
    fwrite(STDERR, "❌ config/config.php introuvable : l'application n'est pas encore installée.\n");
    exit(1);
}

try {
    Migrator::run();
    echo "✓ Migrations appliquées avec succès.\n";
} catch (Throwable $e) {
    fwrite(STDERR, '❌ Erreur : ' . $e->getMessage() . "\n");
    exit(1);
}
