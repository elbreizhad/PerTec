<?php
/**
 * Copiez ce fichier en config/config.php puis adaptez les valeurs.
 * config/config.php est ignoré par git (contient vos identifiants).
 */
return [
    'db' => [
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'name'    => 'pertec',
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'name'     => 'PerTec — Gestion locative',
        // Si l'appli est dans un sous-dossier (ex: https://site.fr/pertec),
        // mettez '/pertec'. Sinon laissez ''.
        'base_url' => '',
        // Clé secrète pour les sessions (changez-la).
        'secret'   => 'changez-moi-avec-une-chaine-aleatoire',
    ],
];
