<?php
require_once __DIR__ . '/config.php';
require_once dirname(__DIR__) . '/includes/stats.php';
require_login();

$periodes = [1 => 'Aujourd\'hui', 7 => '7 jours', 30 => '30 jours'];
$jours = (int) ($_GET['jours'] ?? 7);
if (!isset($periodes[$jours])) $jours = 7;
$vues = ['parcours' => 'Parcours', 'entonnoirs' => 'Étapes vers le devis', 'interets' => 'Ce qui intéresse', 'robots' => 'Robots'];
$vue = $_GET['vue'] ?? 'parcours';
if (!isset($vues[$vue])) $vue = 'parcours';
$filtre = ($_GET['type'] ?? '') === 'suspect' ? 'suspect' : 'humain';

$fin = new DateTimeImmutable('today');
$debut = $fin->modify('-' . ($jours - 1) . ' days');
$visites = suivi_visites(suivi_charger($debut, $fin));

$parType = ['humain' => [], 'robot' => [], 'suspect' => []];
foreach ($visites as $v) $parType[$v['type']][] = $v;
$humains = $parType['humain'];
$nbDemandes = array_sum(array_map(fn($v) => count($v['conversions']), $humains));
$entonnoirs = suivi_entonnoirs_compter($humains);

// Temps pour remplir le formulaire de contact (médiane) : début de saisie -> demande reçue
$tempsSaisie = [];
foreach ($humains as $v) {
    if (!$v['tConversion']) continue;
    foreach ($v['pages'] as $p) {
        if ($p['u'] === '/contact' && $p['saisies']) { $tempsSaisie[] = $v['tConversion'] - $p['saisies'][0]['t']; break; }
    }
}
sort($tempsSaisie);
$medianeSaisie = $tempsSaisie ? $tempsSaisie[intdiv(count($tempsSaisie), 2)] : null;

function lien(array $params): string {
    return '?' . http_build_query(array_merge(['jours' => $GLOBALS['jours'], 'vue' => $GLOBALS['vue']], $params));
}
function duree(int $s): string {
    if ($s < 60) return $s . ' s';
    if ($s < 3600) return floor($s / 60) . ' min ' . str_pad((string) ($s % 60), 2, '0', STR_PAD_LEFT);
    return floor($s / 3600) . ' h ' . str_pad((string) floor($s % 3600 / 60), 2, '0', STR_PAD_LEFT);
}
function duree_visite(array $v): int {
    $actif = array_sum(array_map(fn($p) => $p['du'] ?? 0, $v['pages']));
    return max($actif, $v['fin'] - $v['debut']);
}
function quand(int $t, int $jours): string {
    $jf = ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'];
    return ($jours > 1 ? $jf[(int) date('w', $t)] . ' ' . date('j/m', $t) . ' · ' : '') . date('H:i', $t);
}
function destination(string $h): string {
    return match (true) {
        $h === 'tel:' => '📞 Appel',
        $h === 'mailto:' => '✉️ E-mail',
        $h === 'formulaire' => '📨 Envoi du formulaire',
        $h === '' => '',
        default => $h,
    };
}
function nb($n): string {
    return number_format($n, 0, ',', ' ');
}

// Ce qui intéresse (visiteurs humains)
$pagesStats = [];
$clics = [];
$entrees = [];
$sorties = [];
foreach ($humains as $v) {
    $entrees[$v['pages'][0]['u']] = ($entrees[$v['pages'][0]['u']] ?? 0) + 1;
    $derniere = end($v['pages'])['u'];
    $sorties[$derniere] = ($sorties[$derniere] ?? 0) + 1;
    foreach ($v['pages'] as $p) {
        $s = &$pagesStats[$p['u']];
        $s['vues'] = ($s['vues'] ?? 0) + 1;
        if ($p['du'] !== null) { $s['duree'] = ($s['duree'] ?? 0) + $p['du']; $s['mesures'] = ($s['mesures'] ?? 0) + 1; }
        if ($p['sc'] !== null) { $s['scroll'] = ($s['scroll'] ?? 0) + $p['sc']; $s['nbScroll'] = ($s['nbScroll'] ?? 0) + 1; $s['lus'] = ($s['lus'] ?? 0) + ($p['sc'] >= 90 ? 1 : 0); }
        unset($s);
        foreach ($p['clics'] as $c) {
            $cle = $c['x'] . '|' . $c['h'];
            $clics[$cle] ??= ['x' => $c['x'], 'h' => $c['h'], 'n' => 0, 'pages' => []];
            $clics[$cle]['n']++;
            $clics[$cle]['pages'][$p['u']] = true;
        }
    }
}
uasort($pagesStats, fn($a, $b) => $b['vues'] <=> $a['vues']);
uasort($clics, fn($a, $b) => $b['n'] <=> $a['n']);
arsort($entrees);
arsort($sorties);
$contacts = array_sum(array_map(fn($c) => in_array($c['h'], ['tel:', 'mailto:', '/contact', 'formulaire'], true) ? $c['n'] : 0, $clics));

// Robots
$robots = [];
$categories = [];
$scans = [];
foreach ($parType['robot'] as $v) {
    $r = &$robots[$v['robot']];
    $r['categorie'] = $v['categorie'];
    $r['requetes'] = ($r['requetes'] ?? 0) + count($v['pages']);
    $r['derniere'] = max($r['derniere'] ?? 0, $v['fin']);
    foreach ($v['pages'] as $p) $r['pages'][$p['u']] = true;
    unset($r);
    $categories[$v['categorie']] = ($categories[$v['categorie']] ?? 0) + count($v['pages']);
}
uasort($robots, fn($a, $b) => $b['requetes'] <=> $a['requetes']);
arsort($categories);
$introuvables = [];
foreach ($visites as $v) {
    foreach ($v['pages'] as $p) {
        if (preg_match(SUIVI_MOTIF_SCAN, $p['u'])) {
            $scans[$p['u']] = ($scans[$p['u']] ?? 0) + 1;
        } elseif ($p['s'] === 404) {
            $introuvables[$p['u']] ??= ['n' => 0, 'personnes' => 0];
            $introuvables[$p['u']]['n']++;
            if ($v['type'] === 'humain') $introuvables[$p['u']]['personnes']++;
        }
    }
}
arsort($scans);
uasort($introuvables, fn($a, $b) => [$b['personnes'], $b['n']] <=> [$a['personnes'], $a['n']]);
$suspects = array_reverse($parType['suspect']);
$liste = array_slice(array_reverse($filtre === 'suspect' ? $parType['suspect'] : $humains), 0, 150);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visiteurs - Admin Pertec</title>
    <link rel="stylesheet" href="style.css?v=<?= filemtime(__DIR__ . '/style.css') ?>">
</head>
<body>
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>

        <main class="admin-main">
            <header class="admin-header">
                <h1>Visiteurs</h1>
                <nav class="header-actions periode-switch" aria-label="Période">
                    <?php foreach ($periodes as $n => $label): ?>
                        <a href="<?= e(lien(['jours' => $n])) ?>" class="btn btn-small <?= $n === $jours ? 'btn-primary' : 'btn-secondary' ?>"<?= $n === $jours ? ' aria-current="page"' : '' ?>><?= $label ?></a>
                    <?php endforeach; ?>
                </nav>
            </header>

            <div class="dashboard-grid">
                <a class="stat-card stat-link" href="<?= e(lien(['vue' => 'parcours', 'type' => null])) ?>">
                    <div class="stat-content">
                        <span class="stat-value"><?= nb(count($humains)) ?></span>
                        <span class="stat-label">Visites de personnes</span>
                    </div>
                </a>
                <div class="stat-card">
                    <div class="stat-content">
                        <span class="stat-value"><?= nb($contacts) ?> · <?= nb($nbDemandes) ?></span>
                        <span class="stat-label">Clics pour contacter · demandes envoyées</span>
                    </div>
                </div>
                <a class="stat-card stat-link" href="<?= e(lien(['vue' => 'robots'])) ?>">
                    <div class="stat-content">
                        <span class="stat-value"><?= nb(count($parType['robot'])) ?></span>
                        <span class="stat-label">Passages de robots connus</span>
                    </div>
                </a>
                <a class="stat-card stat-link" href="<?= e(lien(['vue' => 'parcours', 'type' => 'suspect'])) ?>">
                    <div class="stat-content">
                        <span class="stat-value"><?= nb(count($parType['suspect'])) ?></span>
                        <span class="stat-label">Visites suspectes (robots probables)</span>
                    </div>
                </a>
            </div>

            <nav class="vue-tabs" aria-label="Affichage">
                <?php foreach ($vues as $cle => $label): ?>
                    <a href="<?= e(lien(['vue' => $cle, 'type' => null])) ?>" class="<?= $cle === $vue ? 'active' : '' ?>"<?= $cle === $vue ? ' aria-current="page"' : '' ?>><?= $label ?></a>
                <?php endforeach; ?>
            </nav>

<?php if ($vue === 'parcours'): ?>
            <div class="card">
                <div class="trend-head">
                    <div>
                        <h2><?= $filtre === 'suspect' ? 'Visites suspectes' : 'Parcours des visiteurs' ?></h2>
                        <p class="trend-sub">
                            <?= $filtre === 'suspect' ? 'Navigateurs qui se font passer pour des personnes : à vérifier, mais très probablement des robots.' : 'Une ligne par visite, la plus récente en haut. Cliquez pour voir les pages et les clics dans l\'ordre.' ?>
                        </p>
                    </div>
                    <div class="periode-switch">
                        <a href="<?= e(lien(['type' => null])) ?>" class="btn btn-small <?= $filtre === 'humain' ? 'btn-primary' : 'btn-secondary' ?>">Personnes (<?= count($humains) ?>)</a>
                        <a href="<?= e(lien(['type' => 'suspect'])) ?>" class="btn btn-small <?= $filtre === 'suspect' ? 'btn-primary' : 'btn-secondary' ?>">Suspects (<?= count($parType['suspect']) ?>)</a>
                    </div>
                </div>

                <?php if (!$liste): ?>
                    <p class="empty-state">Aucune visite sur cette période pour le moment.</p>
                <?php endif; ?>

                <div class="parcours-liste">
                <?php foreach ($liste as $v):
                    $nbClics = array_sum(array_map(fn($p) => count($p['clics']), $v['pages']));
                ?>
                    <details class="parcours">
                        <summary>
                            <span class="parcours-heure"><?= quand($v['debut'], $jours) ?></span>
                            <span class="parcours-source"><?= e($v['source'] ?? 'Navigation interne') ?></span>
                            <span class="parcours-entree"><?= e($v['pages'][0]['u']) ?></span>
                            <span class="parcours-chiffres"><?= count($v['pages']) ?> page<?= count($v['pages']) > 1 ? 's' : '' ?> · <?= duree(duree_visite($v)) ?><?= $nbClics ? ' · ' . $nbClics . ' clic' . ($nbClics > 1 ? 's' : '') : '' ?></span>
                            <span class="parcours-appareil"><?= e($v['appareil']) ?> · <?= e($v['navigateur']) ?></span>
                            <?php foreach ($v['conversions'] as $c): ?>
                                <span class="status status-live"><?= $c === 'devis' ? 'Demande de devis' : 'Estimation envoyée' ?></span>
                            <?php endforeach; ?>
                            <?php foreach ($v['raisons'] as $r): ?>
                                <span class="status status-scheduled"><?= e($r) ?></span>
                            <?php endforeach; ?>
                        </summary>
                        <ol class="parcours-etapes">
                            <?php foreach ($v['pages'] as $p): ?>
                            <li>
                                <div class="etape-page">
                                    <span class="etape-heure"><?= date('H:i:s', $p['t']) ?></span>
                                    <a href="<?= e($p['u']) ?>" target="_blank" rel="noopener"><?= e($p['u']) ?></a>
                                    <?php if ($p['s'] !== 200): ?><span class="status status-scheduled"><?= (int) $p['s'] ?></span><?php endif; ?>
                                    <span class="etape-mesures">
                                        <?= $p['du'] !== null ? duree($p['du']) : '' ?>
                                        <?php if ($p['sc'] !== null): ?>
                                            <span class="etape-scroll" title="Défilement maximal"><span style="width: <?= (int) $p['sc'] ?>%"></span></span> <?= (int) $p['sc'] ?> %
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <?php foreach ($p['saisies'] as $sa): ?>
                                    <div class="etape-clic etape-saisie">✎ <?= date('H:i:s', $sa['t']) ?> commence à remplir le formulaire</div>
                                <?php endforeach; ?>
                                <?php foreach ($p['clics'] as $c): ?>
                                    <div class="etape-clic">↳ <?= date('H:i:s', $c['t']) ?> clic sur « <?= e($c['x'] ?: '(sans texte)') ?> »<?= $c['h'] !== '' ? ' → ' . e(destination($c['h'])) : '' ?></div>
                                <?php endforeach; ?>
                            </li>
                            <?php endforeach; ?>
                        </ol>
                        <?php if ($filtre === 'suspect'): ?>
                            <p class="parcours-ua"><?= e($v['ua'] ?: '(navigateur non indiqué)') ?></p>
                        <?php endif; ?>
                    </details>
                <?php endforeach; ?>
                </div>
            </div>

<?php elseif ($vue === 'entonnoirs'): ?>
            <div class="card">
                <p class="trend-sub entonnoir-intro"><?= nb(count($humains)) ?> visite(s) de personnes sur la période. Chaque étape ne compte que les visites qui ont franchi toutes les précédentes ; le pourcentage rouge, c'est la part perdue depuis l'étape d'avant.</p>
                <?php foreach ($entonnoirs as $nom => $etapes):
                    $premier = max(1, reset($etapes));
                    $avant = null;
                ?>
                    <h2 class="entonnoir-titre"><?= e($nom) ?></h2>
                    <div class="entonnoir">
                    <?php foreach ($etapes as $etape => $n):
                        $perte = $avant ? round(($avant - $n) / $avant * 100) : 0;
                    ?>
                        <div class="entonnoir-etape">
                            <div class="entonnoir-ligne">
                                <span><?= e($etape) ?></span>
                                <span><strong><?= nb($n) ?></strong><?php if ($avant !== null && $perte > 0): ?> <span class="entonnoir-perte">−<?= $perte ?> %</span><?php endif; ?></span>
                            </div>
                            <div class="entonnoir-barre"><span style="width: <?= round($n / $premier * 100, 1) ?>%"></span></div>
                        </div>
                    <?php $avant = $n; endforeach; ?>
                    </div>
                    <?php if ($nom === 'Demande de devis' && $medianeSaisie !== null): ?>
                        <p class="stats-note">Temps pour remplir le formulaire de contact (médiane, <?= count($tempsSaisie) ?> demande(s)) : <strong><?= duree($medianeSaisie) ?></strong></p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

<?php elseif ($vue === 'interets'): ?>
                <div class="card">
                    <h2>Pages consultées</h2>
                    <?php if (!$pagesStats): ?><p class="empty-state">Pas encore de données.</p><?php else: ?>
                    <div class="articles-table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Page</th><th class="num">Vues</th><th class="num">Temps moyen</th><th class="num">Défilement</th><th class="num">Lue en entier</th></tr></thead>
                        <tbody>
                        <?php foreach (array_slice($pagesStats, 0, 40, true) as $page => $s): ?>
                            <tr>
                                <td><?= e($page) ?></td>
                                <td class="num"><?= nb($s['vues']) ?></td>
                                <td class="num"><?= !empty($s['mesures']) ? duree((int) round($s['duree'] / $s['mesures'])) : '-' ?></td>
                                <td class="num"><?= !empty($s['nbScroll']) ? round($s['scroll'] / $s['nbScroll']) . ' %' : '-' ?></td>
                                <td class="num"><?= !empty($s['nbScroll']) ? round($s['lus'] / $s['nbScroll'] * 100) . ' %' : '-' ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="card">
                    <h2>Ce sur quoi ils cliquent</h2>
                    <?php if (!$clics): ?><p class="empty-state">Pas encore de clic enregistré.</p><?php else: ?>
                    <div class="articles-table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Élément cliqué</th><th>Vers</th><th class="num">Clics</th></tr></thead>
                        <tbody>
                        <?php foreach (array_slice($clics, 0, 40) as $c): ?>
                            <tr>
                                <td><?= e($c['x'] ?: '(sans texte)') ?><span class="clic-pages"><?= e(implode(', ', array_slice(array_keys($c['pages']), 0, 3))) ?></span></td>
                                <td><?= e(destination($c['h'])) ?></td>
                                <td class="num"><?= nb($c['n']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                    <?php endif; ?>
                </div>

            <div class="stats-columns">
                <div class="card">
                    <h2>Pages d'arrivée</h2>
                    <table class="data-table">
                        <?php foreach (array_slice($entrees, 0, 10, true) as $page => $n): ?>
                            <tr><td><?= e($page) ?></td><td class="num"><?= nb($n) ?></td></tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <div class="card">
                    <h2>Pages de départ</h2>
                    <table class="data-table">
                        <?php foreach (array_slice($sorties, 0, 10, true) as $page => $n): ?>
                            <tr><td><?= e($page) ?></td><td class="num"><?= nb($n) ?></td></tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
            <p class="stats-note">« Lue en entier » : visites qui ont fait défiler la page jusqu'à au moins 90 %. Une page de départ souvent quittée sans clic mérite un appel à l'action plus visible.</p>

<?php else: ?>
                <div class="card">
                    <h2>Robots connus</h2>
                    <?php if (!$robots): ?><p class="empty-state">Aucun robot sur cette période.</p><?php else: ?>
                    <div class="robot-categories">
                        <?php foreach ($categories as $cat => $n): ?>
                            <span class="badge-info"><?= e($cat) ?> : <?= nb($n) ?></span>
                        <?php endforeach; ?>
                    </div>
                    <div class="articles-table-wrap">
                    <table class="data-table">
                        <thead><tr><th>Robot</th><th>Catégorie</th><th class="num">Requêtes</th><th class="num">Pages</th><th>Dernier passage</th></tr></thead>
                        <tbody>
                        <?php foreach ($robots as $nom => $r): ?>
                            <tr>
                                <td><strong><?= e($nom) ?></strong></td>
                                <td><?= e($r['categorie']) ?></td>
                                <td class="num"><?= nb($r['requetes']) ?></td>
                                <td class="num"><?= nb(count($r['pages'])) ?></td>
                                <td class="nowrap"><?= quand($r['derniere'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div>
                    <?php endif; ?>
                </div>

            <div class="stats-columns">
                <div class="card">
                    <h2>Adresses visées par les scanners</h2>
                    <?php if (!$scans): ?><p class="empty-state">Rien de suspect.</p><?php else: ?>
                    <table class="data-table">
                        <thead><tr><th>Adresse demandée</th><th class="num">Fois</th></tr></thead>
                        <tbody>
                        <?php foreach (array_slice($scans, 0, 20, true) as $u => $n): ?>
                            <tr><td><code><?= e($u) ?></code></td><td class="num"><?= nb($n) ?></td></tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                    <p class="stats-note">Des programmes testent des adresses de WordPress ou de fichiers sensibles qui n'existent pas sur votre site : c'est courant et sans danger ici, ils reçoivent une page 404.</p>
                    <?php endif; ?>
                </div>

            <div class="card">
                <h2>Pages ou fichiers introuvables</h2>
                <?php if (!$introuvables): ?><p class="empty-state">Aucun lien cassé repéré.</p><?php else: ?>
                <table class="data-table">
                    <thead><tr><th>Adresse demandée</th><th class="num">Fois</th><th class="num">Dont personnes</th></tr></thead>
                    <tbody>
                    <?php foreach (array_slice($introuvables, 0, 20, true) as $u => $i): ?>
                        <tr><td><code><?= e($u) ?></code></td><td class="num"><?= nb($i['n']) ?></td><td class="num"><?= nb($i['personnes']) ?></td></tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <p class="stats-note">Une adresse demandée par des personnes est probablement un lien ou une image cassée sur le site, ou un ancien lien encore présent ailleurs : à corriger ou à rediriger.</p>
                <?php endif; ?>
            </div>
            </div>

            <div class="card">
                <h2>Visites suspectes</h2>
                <?php if (!$suspects): ?><p class="empty-state">Aucune visite suspecte.</p><?php else: ?>
                <div class="articles-table-wrap">
                <table class="data-table articles-table">
                    <thead><tr><th>Quand</th><th>Pourquoi</th><th class="num">Pages</th><th>Navigateur annoncé</th></tr></thead>
                    <tbody>
                    <?php foreach (array_slice($suspects, 0, 100) as $v): ?>
                        <tr>
                            <td class="nowrap"><?= quand($v['debut'], $jours) ?></td>
                            <td><?php foreach ($v['raisons'] as $r): ?><span class="status status-scheduled"><?= e($r) ?></span> <?php endforeach; ?>
                                <span class="clic-pages"><?= e(implode(', ', array_slice(array_unique(array_column($v['pages'], 'u')), 0, 4))) ?></span></td>
                            <td class="num"><?= count($v['pages']) ?></td>
                            <td class="ua-cell"><?= e($v['ua'] ?: '(non indiqué)') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
                <?php endif; ?>
            </div>
<?php endif; ?>

            <p class="stats-note">Sans cookie et sans adresse IP enregistrée : une visite est reconnue le temps d'une journée par une empreinte chiffrée qui change chaque jour. Les visiteurs qui ont activé « Ne pas me suivre » ne sont pas suivis. Vos propres visites ne sont pas comptées. Journal conservé <?= SUIVI_JOURS_CONSERVES ?> jours.</p>
        </main>
    </div>
</body>
</html>
