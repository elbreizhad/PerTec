<?php
/**
 * Script de déploiement automatique depuis GitHub
 * Usage: https://pertec.fr/deploy.php?secret=VOTRE_SECRET
 */

// ============================================
// CONFIGURATION
// ============================================

$config = [];
if (file_exists(__DIR__ . '/deploy.config.php')) {
    $config = require __DIR__ . '/deploy.config.php';
}

$secret_key    = $config['DEPLOY_SECRET'] ?? getenv('DEPLOY_SECRET') ?: 'CHANGEZ_MOI';
$github_token  = $config['GITHUB_TOKEN'] ?? getenv('GITHUB_TOKEN') ?: '';
$github_repo   = $config['GITHUB_REPO'] ?? getenv('GITHUB_REPO') ?: 'elbreizhad/PerTec';
$branch        = $config['DEPLOY_BRANCH'] ?? getenv('DEPLOY_BRANCH') ?: 'claude/analyze-requirements-IB5yN';
$repo_path     = $config['REPO_PATH'] ?? getenv('REPO_PATH') ?: __DIR__ . '/../repo';
$public_path   = $config['PUBLIC_PATH'] ?? getenv('PUBLIC_PATH') ?: __DIR__;
$source_folder = $config['SOURCE_FOLDER'] ?? getenv('SOURCE_FOLDER') ?: 'site-html';

// ============================================
// SÉCURITÉ
// ============================================

if (!isset($_GET['secret']) || $_GET['secret'] !== $secret_key) {
    http_response_code(403);
    die('Accès refusé. Secret invalide.');
}

// ============================================
// DÉPLOIEMENT
// ============================================

header('Content-Type: text/plain; charset=utf-8');

echo "=== DÉPLOIEMENT PERTEC.FR ===\n\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo "Repo: $github_repo\n";
echo "Branche: $branch\n";
echo "Repo path: $repo_path\n";
echo "Public path: $public_path\n";
echo "Source folder: $source_folder\n";
echo "Current dir: " . __DIR__ . "\n";
echo "Home: " . getenv('HOME') . "\n\n";

function run_command($cmd, $description, $stop_on_error = false) {
    echo ">> $description\n";
    echo "$ $cmd\n";

    $output = [];
    $return_var = 0;
    exec($cmd . ' 2>&1', $output, $return_var);

    echo implode("\n", $output) . "\n";

    if ($return_var !== 0) {
        echo "⚠️  Erreur (code: $return_var)\n";
        if ($stop_on_error) {
            echo "\n❌ ARRÊT DU DÉPLOIEMENT\n";
            exit(1);
        }
    } else {
        echo "✅ OK\n";
    }
    echo "\n";

    return $return_var === 0;
}

// Construire l'URL du repo
$repo_url = "https://github.com/$github_repo.git";
if (!empty($github_token)) {
    $repo_url = "https://$github_token@github.com/$github_repo.git";
    echo "🔑 Token GitHub configuré\n\n";
} else {
    echo "ℹ️  Pas de token GitHub (repo public)\n\n";
}

// Créer le dossier parent si nécessaire
$parent_dir = dirname($repo_path);
if (!is_dir($parent_dir)) {
    echo "📁 Création du dossier parent: $parent_dir\n";
    if (!mkdir($parent_dir, 0755, true)) {
        echo "❌ Impossible de créer $parent_dir\n";
        echo "Vérifiez que vous avez les droits d'écriture.\n";
        exit(1);
    }
    echo "✅ OK\n\n";
}

// Vérifier si le repo existe
if (!is_dir($repo_path . '/.git')) {
    echo "📁 Le dépôt n'existe pas, clonage...\n\n";

    // Supprimer le dossier s'il existe mais n'est pas un repo git
    if (is_dir($repo_path)) {
        run_command("rm -rf $repo_path", "Suppression du dossier existant");
    }

    // Cloner le repo
    $clone_cmd = "git clone --branch $branch $repo_url $repo_path";
    if (!run_command($clone_cmd, "Clonage du dépôt", true)) {
        echo "\n💡 Solutions possibles:\n";
        echo "1. Vérifiez que le token GitHub est valide\n";
        echo "2. Vérifiez que le repo existe: https://github.com/$github_repo\n";
        echo "3. Vérifiez que la branche existe: $branch\n";
        exit(1);
    }
} else {
    echo "📁 Dépôt existant trouvé\n\n";
}

// Se placer dans le repo
if (!chdir($repo_path)) {
    echo "❌ Impossible d'accéder à $repo_path\n";
    exit(1);
}

echo "📍 Working directory: " . getcwd() . "\n\n";

// Fetch et pull
run_command("git fetch origin", "Récupération des modifications");
run_command("git checkout $branch", "Changement de branche");
run_command("git reset --hard origin/$branch", "Reset sur la branche distante");

// Synchroniser les fichiers vers public_html
if ($public_path !== $repo_path . '/' . $source_folder) {
    $excludes = [
        'deploy.config.php',
        'deploy.php',
        '.git',
        '.gitignore',
        'admin/settings.json',
        'admin/messages.json',
        'admin/estimations.json',
        'admin/stats/',
        'admin/config.local.php',
        // Les données sont désormais hors du site (pertec-data/, voir includes/data.php) ;
        // ces exclusions protègent les anciennes copies le temps de la migration.
    ];
    $exclude_str = implode(' ', array_map(fn($e) => "--exclude='$e'", $excludes));
    $sync_cmd = "rsync -av --delete $exclude_str $repo_path/$source_folder/ $public_path/";
    run_command($sync_cmd, "Synchronisation vers public_html");
} else {
    echo "ℹ️  Pas de rsync nécessaire (même dossier)\n\n";
}

echo "=== DÉPLOIEMENT TERMINÉ ===\n";
echo "🚀 Site mis à jour avec succès !\n";
echo "\nURL: https://pertec.fr\n";
