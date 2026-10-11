<?php
/**
 * Copiez ce fichier en `deploy.config.php` (ignoré par git) et renseignez vos
 * valeurs. Ce fichier a la priorité sur les variables d'environnement.
 */
return [
    // Secret exigé dans l'URL : /deploy.php?secret=...
    'DEPLOY_SECRET' => 'CHANGEZ_MOI_123',

    // Personal Access Token GitHub (portée "repo" en lecture). Laissez vide
    // pour un dépôt public.
    'GITHUB_TOKEN'  => '',

    // Dépôt et branche à importer.
    'GITHUB_REPO'   => 'elbreizhad/PerTec',
    'DEPLOY_BRANCH' => 'claude/analyze-requirements-IB5yN',

    // Chemins sur le serveur (adaptez selon votre hébergement PlanetHoster)
    'REPO_PATH'     => '/home/pertecfr/repo',
    'PUBLIC_PATH'   => '/home/pertecfr/public_html',
    'SOURCE_FOLDER' => 'site-html',
];
