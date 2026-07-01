<?php
declare(strict_types=1);

/**
 * PerTec — Gestion locative
 * Point d'entrée unique (front controller).
 */

error_reporting(E_ALL);
ini_set('display_errors', '0'); // passez à '1' pour déboguer

// Autoload Composer (Dompdf) si présent — l'appli fonctionne même sans.
if (is_file(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
}

require __DIR__ . '/src/App.php';
require __DIR__ . '/src/Database.php';
require __DIR__ . '/src/Migrator.php';
require __DIR__ . '/src/helpers.php';
require __DIR__ . '/src/Pdf.php';
require __DIR__ . '/src/Auth.php';
require __DIR__ . '/src/models/Setting.php';
require __DIR__ . '/src/models/Expense.php';
require __DIR__ . '/src/models/Finance.php';
require __DIR__ . '/src/models/Lmnp.php';
require __DIR__ . '/src/models/Property.php';
require __DIR__ . '/src/models/Tenant.php';
require __DIR__ . '/src/models/Lease.php';
require __DIR__ . '/src/models/Payment.php';

App::boot();

// ---------------------------------------------------------------------------
// Installateur : si pas de config, on force /install
// ---------------------------------------------------------------------------
if (!App::isInstalled()) {
    require __DIR__ . '/src/controllers/install.php';
    App::dispatch();
    return;
}

// Applique les migrations en attente (évolutions de schéma).
Migrator::run();

require __DIR__ . '/src/controllers/routes.php';
App::dispatch();
