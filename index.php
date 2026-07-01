<?php
declare(strict_types=1);

/**
 * PerTec — Gestion locative
 * Point d'entrée unique (front controller).
 */

error_reporting(E_ALL);
ini_set('display_errors', '0'); // passez à '1' pour déboguer

require __DIR__ . '/src/App.php';
require __DIR__ . '/src/Database.php';
require __DIR__ . '/src/helpers.php';
require __DIR__ . '/src/Auth.php';
require __DIR__ . '/src/models/Setting.php';
require __DIR__ . '/src/models/Finance.php';
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

require __DIR__ . '/src/controllers/routes.php';
App::dispatch();
