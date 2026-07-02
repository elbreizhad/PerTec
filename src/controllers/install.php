<?php
declare(strict_types=1);

/**
 * Installateur web : crée config/config.php, la base, les tables et l'admin.
 * Actif uniquement tant que config/config.php n'existe pas.
 */

App::get('/install', function () {
    view('install', ['errors' => [], 'old' => []], 'layout_bare');
});

// Tant que l'appli n'est pas installée, tout redirige vers l'installateur.
App::get('/', function () {
    redirect('/install');
});

App::get('/{any}', function () {
    redirect('/install');
});

App::post('/install', function () {
    $errors = [];
    $old = $_POST;

    $db = [
        'host'    => trim((string)post('db_host', '127.0.0.1')),
        'port'    => (int) post('db_port', 3306),
        'name'    => trim((string)post('db_name', 'pertec')),
        'user'    => trim((string)post('db_user', 'root')),
        'pass'    => (string) post('db_pass', ''),
        'charset' => 'utf8mb4',
    ];
    $adminUser = trim((string) post('admin_user'));
    $adminPass = (string) post('admin_pass');

    if ($db['name'] === '') $errors[] = 'Le nom de la base est requis.';
    if ($adminUser === '')  $errors[] = 'Le nom d\'utilisateur admin est requis.';
    if (strlen($adminPass) < 6) $errors[] = 'Le mot de passe doit faire au moins 6 caractères.';

    // Tentative de connexion serveur + création base
    if (!$errors) {
        try {
            $dsn = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $db['host'], $db['port']);
            $pdo = new PDO($dsn, $db['user'], $db['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$db['name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $pdo->exec("USE `{$db['name']}`");

            $schema = file_get_contents(__DIR__ . '/../../database/schema.sql');
            foreach (array_filter(array_map('trim', explode(';', $schema))) as $stmt) {
                if ($stmt !== '') $pdo->exec($stmt);
            }

            // Création admin (upsert)
            $hash = password_hash($adminPass, PASSWORD_DEFAULT);
            $ins = $pdo->prepare(
                'INSERT INTO users (username, password_hash) VALUES (?, ?)
                 ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)'
            );
            $ins->execute([$adminUser, $hash]);
        } catch (Throwable $ex) {
            $errors[] = 'Connexion / création impossible : ' . $ex->getMessage();
        }
    }

    // Écriture du fichier de config
    if (!$errors) {
        $secret = bin2hex(random_bytes(24));
        $cfg = "<?php\nreturn " . var_export([
            'db'  => $db,
            'app' => [
                'name'     => 'PerTec — Gestion locative',
                'base_url' => trim((string) post('base_url', '')),
                'secret'   => $secret,
            ],
        ], true) . ";\n";
        $path = __DIR__ . '/../../config/config.php';
        if (@file_put_contents($path, $cfg) === false) {
            $errors[] = 'Impossible d\'écrire config/config.php (droits d\'écriture ?).';
        }
    }

    if ($errors) {
        view('install', ['errors' => $errors, 'old' => $old], 'layout_bare');
        return;
    }

    // Installé : on démarre la session et on connecte l'admin.
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    flash('Installation terminée ! Connectez-vous.');
    redirect('/login');
});
