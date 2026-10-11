<?php
/**
 * Script de déploiement automatique depuis GitHub
 *
 * Usage: https://pertec.fr/deploy.php?key=VOTRE_CLE_SECRETE
 *
 * Configuration sur PlanetHoster:
 * 1. Cloner le repo dans un dossier (ex: /home/user/repo)
 * 2. Créer un lien symbolique ou copier les fichiers vers public_html
 * 3. Appeler ce script pour mettre à jour
 */

// ============================================
// CONFIGURATION - À MODIFIER
// ============================================

// Clé secrète pour sécuriser le déploiement (changez-la !)
$secret_key = 'CHANGEZ_CETTE_CLE_SECRETE_123';

// Branche à déployer
$branch = 'claude/analyze-requirements-IB5yN';

// Chemin vers le dépôt git sur le serveur
$repo_path = '/home/pertecfr/repo';  // Adaptez selon votre hébergement

// Chemin vers le dossier public
$public_path = '/home/pertecfr/public_html';  // Adaptez selon votre hébergement

// Dossier source dans le repo
$source_folder = 'site-html';

// ============================================
// SÉCURITÉ
// ============================================

// Vérification de la clé
if (!isset($_GET['key']) || $_GET['key'] !== $secret_key) {
    http_response_code(403);
    die('Accès refusé. Clé invalide.');
}

// ============================================
// DÉPLOIEMENT
// ============================================

header('Content-Type: text/plain; charset=utf-8');

echo "=== DÉPLOIEMENT PERTEC.FR ===\n\n";
echo "Date: " . date('Y-m-d H:i:s') . "\n";
echo "Branche: $branch\n\n";

// Fonction pour exécuter une commande et afficher le résultat
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

// Vérifier si le repo existe
if (!is_dir($repo_path)) {
    echo "📁 Le dépôt n'existe pas, clonage...\n\n";

    // Cloner le repo
    $clone_cmd = "cd " . dirname($repo_path) . " && git clone https://github.com/elbreizhad/PerTec.git " . basename($repo_path);
    run_command($clone_cmd, "Clonage du dépôt");
}

// Se placer dans le repo
chdir($repo_path);

// Fetch les dernières modifications
run_command("git fetch origin", "Récupération des modifications");

// Checkout de la branche
run_command("git checkout $branch", "Changement de branche");

// Pull des modifications
run_command("git pull origin $branch", "Mise à jour du code");

// Synchroniser les fichiers vers public_html
$sync_cmd = "rsync -av --delete $repo_path/$source_folder/ $public_path/";
run_command($sync_cmd, "Synchronisation vers public_html");

// Définir les permissions
run_command("chmod -R 755 $public_path/", "Mise à jour des permissions");

echo "=== DÉPLOIEMENT TERMINÉ ===\n";
echo "🚀 Site mis à jour avec succès !\n";
?>
