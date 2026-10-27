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

// ============================================
// UN SEUL DÉPLOIEMENT À LA FOIS
// ============================================
// Deux appels simultanés (double clic, webhook + appel manuel) se marchent
// dessus et laissent des verrous Git (index.lock, refs/*.lock).

$lock = fopen(sys_get_temp_dir() . '/pertec-deploy.lock', 'c');
if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) {
    echo "⏳ Un déploiement est déjà en cours. Réessayez dans une minute.\n";
    exit(1);
}

// Vérifier si le repo existe
if (!is_dir($repo_path . '/.git')) {
    echo "📁 Le dépôt n'existe pas, clonage...\n\n";

    // Supprimer le dossier s'il existe mais n'est pas un repo git
    if (is_dir($repo_path)) {
        run_command("rm -rf " . escapeshellarg($repo_path), "Suppression du dossier existant");
    }

    // Cloner le repo
    $clone_cmd = "git clone --branch " . escapeshellarg($branch) . " " . escapeshellarg($repo_url) . " " . escapeshellarg($repo_path);
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

// Verrous Git abandonnés par un déploiement interrompu : on les retire
// (aucun autre déploiement ne tourne, grâce au verrou ci-dessus).
$stale = [];
$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($repo_path . '/.git', FilesystemIterator::SKIP_DOTS));
foreach ($it as $file) {
    if (substr($file->getFilename(), -5) === '.lock' && @unlink($file->getPathname())) {
        $stale[] = substr($file->getPathname(), strlen($repo_path) + 1);
    }
}
if ($stale) {
    echo "🧹 Verrous Git abandonnés supprimés : " . implode(', ', $stale) . "\n\n";
}

// Récupérer uniquement la branche déployée, puis s'aligner dessus.
// Si une étape échoue, on s'arrête : pas question de publier l'ancienne version.
$b = escapeshellarg($branch);
run_command("git fetch --prune origin +refs/heads/$branch:refs/remotes/origin/$branch", "Récupération des modifications", true);
run_command("git checkout -f $b", "Changement de branche", true);
run_command("git reset --hard " . escapeshellarg("origin/$branch"), "Reset sur la branche distante", true);

$commit = trim(shell_exec('git log -1 --format="%h · %ad · %s" --date=format:"%d/%m/%Y %H:%M" 2>/dev/null') ?? '');
echo "📌 Version déployée : $commit\n\n";

// Synchroniser les fichiers vers public_html
if ($public_path !== $repo_path . '/' . $source_folder) {
    // Tout ce qui est listé ici n'est ni écrasé ni supprimé dans public_html.
    $excludes = [
        'deploy.config.php',
        'deploy.php',
        '.git',
        '.gitignore',
        // Données enregistrées par le site (désormais dans ../pertec-data, voir includes/data.php ;
        // les anciennes copies restent protégées le temps de la migration)
        'admin/settings.json',
        'admin/messages.json',
        'admin/estimations.json',
        'admin/stats/',
        'admin/config.local.php',
        // Dossiers et fichiers de l'hébergement qui ne viennent pas du dépôt
        '/old site/',
        '/.well-known/',
        '/cgi-bin/',
        '/.user.ini',
        '/php.ini',
        '/error_log',
    ];
    foreach ($config['PROTECT'] ?? [] as $extra) {
        $excludes[] = $extra;
    }
    $exclude_str = implode(' ', array_map(fn($e) => '--exclude=' . escapeshellarg($e), $excludes));
    $sync_cmd = "rsync -a --delete --itemize-changes $exclude_str " . escapeshellarg("$repo_path/$source_folder/") . " " . escapeshellarg("$public_path/");
    run_command($sync_cmd, "Synchronisation vers public_html", true);
} else {
    echo "ℹ️  Pas de rsync nécessaire (même dossier)\n\n";
}

flock($lock, LOCK_UN);

echo "=== DÉPLOIEMENT TERMINÉ ===\n";
echo "🚀 Site mis à jour : $commit\n";
echo "\nURL: https://pertec.fr\n";
