<?php
/**
 * Déploiement par git pull, déclenché depuis le navigateur.
 * ------------------------------------------------------------------
 * À placer à la racine du site (le dossier qui contient .git et index.php).
 *
 * Utilisation :
 *   https://maloc.pertec.fr/deploy.php?token=VOTRE_TOKEN
 *   (optionnel) &branch=main   pour forcer une branche précise
 *
 * SÉCURITÉ : changez impérativement le TOKEN ci-dessous par une valeur secrète
 * à vous. Sans le bon token, le script refuse de s'exécuter.
 */

// >>> CHANGEZ CETTE VALEUR <<<  (gardez-la secrète)
const DEPLOY_TOKEN = '708794be2b9f6159cd32e3054c5d6c45';

// Branche à déployer par défaut (celle que suit votre serveur).
const DEFAULT_BRANCH = 'main';

header('Content-Type: text/plain; charset=utf-8');

// --- Contrôle du token -------------------------------------------------
$given = $_GET['token'] ?? '';
if (!hash_equals(DEPLOY_TOKEN, (string) $given)) {
    http_response_code(403);
    exit("403 — token invalide.\n");
}

// --- Vérification de l'environnement -----------------------------------
$root = __DIR__;
if (!is_dir($root . '/.git')) {
    http_response_code(500);
    exit("Erreur : ce dossier n'est pas un dépôt git (.git introuvable dans $root).\n");
}
foreach (['exec', 'shell_exec'] as $fn) {
    if (!function_exists($fn) || in_array($fn, array_map('trim', explode(',', (string) ini_get('disable_functions'))), true)) {
        http_response_code(500);
        exit("Erreur : la fonction PHP $fn est désactivée sur cet hébergement.\n"
           . "Le déploiement par PHP n'est pas possible ; utilisez le Terminal SSH.\n");
    }
}

// --- Branche demandée --------------------------------------------------
$branch = preg_replace('/[^a-zA-Z0-9._\/-]/', '', $_GET['branch'] ?? DEFAULT_BRANCH);
if ($branch === '') {
    $branch = DEFAULT_BRANCH;
}

// --- Exécution ---------------------------------------------------------
function run(string $cmd): string
{
    $out = [];
    $code = 0;
    exec($cmd . ' 2>&1', $out, $code);
    return "\$ $cmd\n" . implode("\n", $out) . "\n(code $code)\n\n";
}

$git = 'git -C ' . escapeshellarg($root);
$b   = escapeshellarg($branch);

echo "=== Déploiement PerTec ===\n";
echo "Dossier : $root\n";
echo "Branche : $branch\n";
echo "Date    : " . date('Y-m-d H:i:s') . "\n\n";

echo run("$git fetch origin $b");
echo run("$git checkout $b");
echo run("$git reset --hard origin/$b");
echo run("$git log -1 --pretty=format:'Dernier commit : %h — %s (%ci)'");

echo "\n\n=== Terminé ===\n";
echo "Pensez à supprimer ce fichier (deploy.php) ou à changer le token après usage.\n";
