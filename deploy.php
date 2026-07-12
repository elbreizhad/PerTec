<?php
/**
 * Déploiement du site depuis GitHub — 100 % PHP, sans commande shell.
 * ------------------------------------------------------------------
 * Pensé pour les hébergements mutualisés où exec()/shell_exec() sont
 * désactivés (cas de PlanetHoster N0C). Le script :
 *   1. télécharge l'archive ZIP de la branche voulue depuis GitHub ;
 *   2. l'extrait dans un dossier temporaire ;
 *   3. recopie les fichiers par-dessus le site, en préservant votre
 *      config/config.php (identifiants de base de données).
 *
 * INSTALLATION
 *   - Placez ce fichier à la racine du site (à côté de index.php).
 *   - Créez le fichier  config/deploy_token.txt  contenant UNE ligne :
 *     votre jeton secret (ex. une longue chaîne aléatoire).
 *     Le dossier config/ est déjà protégé par le .htaccess.
 *
 * UTILISATION (dans le navigateur)
 *   https://maloc.pertec.fr/deploy.php?token=VOTRE_TOKEN
 *   (optionnel) &branch=main   pour choisir la branche déployée.
 */

header('Content-Type: text/plain; charset=utf-8');

const GH_OWNER      = 'elbreizhad';
const GH_REPO       = 'PerTec';
const DEFAULT_BRANCH = 'main';

// Fichiers/dossiers à NE JAMAIS écraser (spécifiques au serveur).
$PRESERVE = [
    'config/config.php',
    'config/deploy_token.txt',
    '.htaccess',            // votre .htaccess de prod (retirez cette ligne si vous voulez le mettre à jour depuis git)
];

// --- 1. Token ----------------------------------------------------------
$tokenFile = __DIR__ . '/config/deploy_token.txt';
if (!is_file($tokenFile)) {
    http_response_code(500);
    exit("Configuration manquante : créez le fichier config/deploy_token.txt\n"
       . "avec votre jeton secret à l'intérieur (une seule ligne).\n");
}
$expected = trim((string) file_get_contents($tokenFile));
$given    = trim((string) ($_GET['token'] ?? ''));
if ($expected === '' || !hash_equals($expected, $given)) {
    http_response_code(403);
    exit("403 — token invalide.\n");
}

// --- 2. Pré-requis PHP -------------------------------------------------
if (!class_exists('ZipArchive')) {
    http_response_code(500);
    exit("Erreur : l'extension PHP « zip » (ZipArchive) est absente sur cet hébergement.\n");
}

$branch = preg_replace('#[^a-zA-Z0-9._/-]#', '', $_GET['branch'] ?? DEFAULT_BRANCH);
if ($branch === '') $branch = DEFAULT_BRANCH;

$root = __DIR__;
$tmp  = sys_get_temp_dir() . '/pertec_deploy_' . bin2hex(random_bytes(4));
@mkdir($tmp, 0700, true);

echo "=== Déploiement PerTec ===\n";
echo "Dépôt   : " . GH_OWNER . '/' . GH_REPO . "\n";
echo "Branche : $branch\n";
echo "Cible   : $root\n";
echo "Date    : " . date('Y-m-d H:i:s') . "\n\n";

// --- 3. Téléchargement du ZIP -----------------------------------------
$url = 'https://codeload.github.com/' . GH_OWNER . '/' . GH_REPO . '/zip/refs/heads/' . $branch;
$zipPath = $tmp . '/repo.zip';
echo "→ Téléchargement de l'archive…\n";
$data = http_get($url);
if ($data === null || strlen($data) < 100) {
    cleanup($tmp);
    http_response_code(502);
    exit("Erreur : téléchargement impossible (branche « $branch » introuvable ou réseau bloqué).\n");
}
file_put_contents($zipPath, $data);
echo "  " . number_format(strlen($data)) . " octets reçus.\n\n";

// --- 4. Extraction -----------------------------------------------------
echo "→ Extraction…\n";
$zip = new ZipArchive();
if ($zip->open($zipPath) !== true) {
    cleanup($tmp);
    http_response_code(500);
    exit("Erreur : archive ZIP illisible.\n");
}
$zip->extractTo($tmp);
$topDir = trim((string) $zip->getNameIndex(0), '/'); // ex: PerTec-main
$zip->close();
$src = $tmp . '/' . $topDir;
if (!is_dir($src)) {
    cleanup($tmp);
    http_response_code(500);
    exit("Erreur : dossier extrait introuvable ($topDir).\n");
}
echo "  OK ($topDir)\n\n";

// --- 5. Copie par-dessus le site --------------------------------------
echo "→ Mise à jour des fichiers…\n";
$preserveSet = array_fill_keys($PRESERVE, true);
$stats = ['copiés' => 0, 'préservés' => 0];
copy_tree($src, $root, '', $preserveSet, $stats);

echo "\n=== Terminé ===\n";
echo "Fichiers mis à jour : {$stats['copiés']}\n";
echo "Fichiers préservés  : {$stats['préservés']} (config, .htaccess…)\n";

// Version déployée (SHA du HEAD de la branche via l'API GitHub).
$meta = @json_decode((string) http_get(
    'https://api.github.com/repos/' . GH_OWNER . '/' . GH_REPO . '/commits/' . rawurlencode($branch)
), true);
if (isset($meta['sha'])) {
    echo "Commit déployé      : " . substr($meta['sha'], 0, 7)
       . ' — ' . ($meta['commit']['message'] ?? '') . "\n";
}

cleanup($tmp);
echo "\nSécurité : gardez config/deploy_token.txt secret. Changez le token en cas de doute.\n";

// ======================================================================
// Fonctions utilitaires
// ======================================================================
function http_get(string $url): ?string
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_USERAGENT      => 'PerTec-Deploy',
            CURLOPT_HTTPHEADER     => ['Accept: */*'],
        ]);
        $res = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($res !== false && $code >= 200 && $code < 400) ? $res : null;
    }
    if (ini_get('allow_url_fopen')) {
        $ctx = stream_context_create(['http' => [
            'timeout'    => 60,
            'user_agent' => 'PerTec-Deploy',
        ]]);
        $res = @file_get_contents($url, false, $ctx);
        return $res === false ? null : $res;
    }
    return null;
}

/** Copie récursive de $src vers $dst, en sautant les chemins préservés. */
function copy_tree(string $src, string $dst, string $rel, array $preserve, array &$stats): void
{
    foreach (scandir($src) as $entry) {
        if ($entry === '.' || $entry === '..') continue;
        $relPath = ltrim($rel . '/' . $entry, '/');
        $from = $src . '/' . $entry;
        $to   = $dst . '/' . $entry;

        // Ne jamais toucher .git ni les fichiers préservés.
        if ($entry === '.git' || isset($preserve[$relPath])) {
            $stats['préservés']++;
            continue;
        }
        if (is_dir($from)) {
            if (!is_dir($to)) @mkdir($to, 0755, true);
            copy_tree($from, $to, $relPath, $preserve, $stats);
        } else {
            if (@copy($from, $to)) {
                $stats['copiés']++;
            } else {
                echo "  ⚠ échec copie : $relPath\n";
            }
        }
    }
}

function cleanup(string $dir): void
{
    if (!is_dir($dir)) return;
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    foreach ($it as $f) {
        $f->isDir() ? @rmdir($f->getPathname()) : @unlink($f->getPathname());
    }
    @rmdir($dir);
}
