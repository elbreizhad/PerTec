<?php
/**
 * Script de déploiement automatique depuis GitHub
 *
 * Usage: https://pertec.fr/deploy.php?secret=VOTRE_SECRET
 *
 * Configuration:
 * 1. Copiez deploy.config.example.php en deploy.config.php
 * 2. Renseignez vos valeurs dans deploy.config.php
 * 3. Appelez /deploy.php?secret=VOTRE_SECRET
 */

// ============================================
// CONFIGURATION
// ============================================

$config = [];

// Charger la config depuis le fichier (prioritaire)
if (file_exists(__DIR__ . '/deploy.config.php')) {
    $config = require __DIR__ . '/deploy.config.php';
}

// Valeurs par défaut / fallback sur variables d'environnement
$secret_key   = $config['DEPLOY_SECRET'] ?? getenv('DEPLOY_SECRET') ?: 'CHANGEZ_MOI';
$github_token = $config['GITHUB_TOKEN'] ?? getenv('GITHUB_TOKEN') ?: '';
$github_repo  = $config['GITHUB_REPO'] ?? getenv('GITHUB_REPO') ?: 'elbreizhad/PerTec';
$branch       = $config['DEPLOY_BRANCH'] ?? getenv('DEPLOY_BRANCH') ?: 'claude/analyze-requirements-IB5yN';
$repo_path    = $config['REPO_PATH'] ?? getenv('REPO_PATH') ?: '/home/pertecfr/repo';
$public_path  = $config['PUBLIC_PATH'] ?? getenv('PUBLIC_PATH') ?: '/home/pertecfr/public_html';
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
echo "Branche: $branch\n\n";

// Fonction pour exécuter une commande
function run_command($cmd, $description) {
    echo ">> $description\n";
    echo "$ $cmd\n";

    $output = [];
    $return_var = 0;
    exec($cmd . ' 2>&1', $output, $return_var);

    echo implode("\n", $output) . "\n";

    if ($return_var !== 0) {
        echo "⚠️  Erreur (code: $return_var)\n";
    } else {
        echo "✅ OK\n";
    }
    echo "\n";

    return $return_var === 0;
}

// Construire l'URL du repo (avec token si privé)
$repo_url = "https://github.com/$github_repo.git";
if (!empty($github_token)) {
    $repo_url = "https://$github_token@github.com/$github_repo.git";
}

// Vérifier si le repo existe
if (!is_dir($repo_path)) {
    echo "📁 Le dépôt n'existe pas, clonage...\n\n";

    $parent_dir = dirname($repo_path);
    $repo_name = basename($repo_path);

    run_command("cd $parent_dir && git clone $repo_url $repo_name", "Clonage du dépôt");
}

// Se placer dans le repo
chdir($repo_path);

// Fetch les dernières modifications
run_command("git fetch origin", "Récupération des modifications");

// Checkout de la branche
run_command("git checkout $branch", "Changement de branche");

// Reset hard pour éviter les conflits
run_command("git reset --hard origin/$branch", "Reset sur la branche distante");

// Pull des modifications
run_command("git pull origin $branch", "Mise à jour du code");

// Synchroniser les fichiers vers public_html
$sync_cmd = "rsync -av --delete --exclude='deploy.config.php' --exclude='.git' $repo_path/$source_folder/ $public_path/";
run_command($sync_cmd, "Synchronisation vers public_html");

// Définir les permissions
run_command("chmod -R 755 $public_path/", "Mise à jour des permissions");

echo "=== DÉPLOIEMENT TERMINÉ ===\n";
echo "🚀 Site mis à jour avec succès !\n";
echo "\nURL: https://pertec.fr\n";
