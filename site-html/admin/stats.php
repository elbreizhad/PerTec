<?php
require_once __DIR__ . '/config.php';
require_once dirname(__DIR__) . '/includes/stats.php';
require_login();

$periodes = [7 => '7 jours', 30 => '30 jours', 90 => '90 jours'];
$jours = (int) ($_GET['jours'] ?? 30);
if (!isset($periodes[$jours])) {
    $jours = 30;
}

$fin = new DateTimeImmutable('today');
$debut = $fin->modify('-' . ($jours - 1) . ' days');
$data = stats_load($debut, $fin);

// Totaux et série jour par jour (jours sans visite inclus)
$totaux = ['visites' => 0, 'vues' => 0, 'devis' => 0, 'estimations' => 0];
$sources = [];
$pages = [];
$entrees = [];
$serie = [];
for ($d = $debut; $d <= $fin; $d = $d->modify('+1 day')) {
    $cle = $d->format('Y-m-d');
    $jour = $data[$cle] ?? [];
    foreach ($totaux as $k => $v) {
        $totaux[$k] += $jour[$k] ?? 0;
    }
    foreach ($jour['sources'] ?? [] as $k => $v) $sources[$k] = ($sources[$k] ?? 0) + $v;
    foreach ($jour['pages'] ?? [] as $k => $v) $pages[$k] = ($pages[$k] ?? 0) + $v;
    foreach ($jour['entrees'] ?? [] as $k => $v) $entrees[$k] = ($entrees[$k] ?? 0) + $v;
    $sourcesJour = $jour['sources'] ?? [];
    arsort($sourcesJour);
    $serie[] = [
        'date' => $d,
        'visites' => $jour['visites'] ?? 0,
        'vues' => $jour['vues'] ?? 0,
        'demandes' => ($jour['devis'] ?? 0) + ($jour['estimations'] ?? 0),
        'source' => array_key_first($sourcesJour),
    ];
}
arsort($sources);
arsort($pages);
arsort($entrees);

$demandes = $totaux['devis'] + $totaux['estimations'];
$taux = $totaux['visites'] > 0 ? $demandes / $totaux['visites'] * 100 : null;
$maxVisites = max(1, max(array_column($serie, 'visites')));

// Graduation de l'axe : 4 intervalles « ronds » qui couvrent le maximum
$pas = 1;
foreach ([1, 2, 5, 10, 20, 25, 50, 100, 200, 250, 500, 1000] as $p) {
    $pas = $p;
    if ($maxVisites / $p <= 4) break;
}
$axeMax = $pas * 4;
$graduations = [$axeMax, $pas * 3, $pas * 2, $pas, 0];

// Période précédente de même durée, pour la comparaison
$finPrec = $debut->modify('-1 day');
$debutPrec = $finPrec->modify('-' . ($jours - 1) . ' days');
$visitesPrec = array_sum(array_map(fn($j) => $j['visites'] ?? 0, stats_load($debutPrec, $finPrec)));
$evolution = $visitesPrec > 0 ? ($totaux['visites'] - $visitesPrec) / $visitesPrec * 100 : null;
$moyenne = $totaux['visites'] / $jours;
$meilleurJour = null;
foreach ($serie as $j) {
    if ($meilleurJour === null || $j['visites'] > $meilleurJour['visites']) $meilleurJour = $j;
}

// Courbe : chaque point est centré sur sa colonne (repère 0-1000)
$nbPoints = count($serie);
$points = [];
foreach ($serie as $i => $j) {
    $points[] = [
        'x' => ($i + 0.5) / $nbPoints * 1000,
        'y' => 1000 - $j['visites'] / $axeMax * 1000,
    ];
}
$ligne = 'M' . implode(' L', array_map(fn($pt) => round($pt['x'], 1) . ',' . round($pt['y'], 1), $points));
$aire = $ligne . ' L' . round(end($points)['x'], 1) . ',1000 L' . round($points[0]['x'], 1) . ',1000 Z';
$joursFr = ['dim.', 'lun.', 'mar.', 'mer.', 'jeu.', 'ven.', 'sam.'];
$joursFrMaj = ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'];

$moisFr = ['', 'janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];
function date_fr(DateTimeImmutable $d, array $mois): string {
    return $d->format('j') . ' ' . $mois[(int) $d->format('n')];
}
function nombre($n): string {
    return number_format($n, 0, ',', ' ');
}
function pourcent(int $part, int $total): string {
    return $total > 0 ? round($part / $total * 100) . ' %' : '-';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Admin Pertec</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>

        <main class="admin-main">
            <header class="admin-header">
                <h1>Statistiques</h1>
                <nav class="header-actions periode-switch" aria-label="Période">
                    <?php foreach ($periodes as $n => $label): ?>
                        <a href="?jours=<?= $n ?>" class="btn btn-small <?= $n === $jours ? 'btn-primary' : 'btn-secondary' ?>"<?= $n === $jours ? ' aria-current="page"' : '' ?>><?= $label ?></a>
                    <?php endforeach; ?>
                </nav>
            </header>

            <p class="stats-periode">Du <?= date_fr($debut, $moisFr) ?> au <?= date_fr($fin, $moisFr) ?> <?= $fin->format('Y') ?>. Vos propres visites ne sont pas comptées sur ce navigateur.</p>

            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-content">
                        <span class="stat-value"><?= nombre($totaux['visites']) ?></span>
                        <span class="stat-label">Visites</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <span class="stat-value"><?= nombre($totaux['vues']) ?></span>
                        <span class="stat-label">Pages vues</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <span class="stat-value"><?= nombre($demandes) ?></span>
                        <span class="stat-label"><?= nombre($totaux['devis']) ?> devis · <?= nombre($totaux['estimations']) ?> estimations</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <span class="stat-value"><?= $taux === null ? '-' : number_format($taux, 1, ',', ' ') . ' %' ?></span>
                        <span class="stat-label">Visites qui font une demande</span>
                    </div>
                </div>
            </div>

            <div class="card trend-card">
                <div class="trend-head">
                    <div>
                        <h2>Visites par jour</h2>
                        <p class="trend-sub">Moyenne <?= number_format($moyenne, 1, ',', ' ') ?> par jour<?php if ($meilleurJour && $meilleurJour['visites'] > 0): ?> · record <?= $meilleurJour['visites'] ?> le <?= date_fr($meilleurJour['date'], $moisFr) ?><?php endif; ?></p>
                    </div>
                    <div class="trend-total">
                        <span class="trend-total-value"><?= nombre($totaux['visites']) ?></span>
                        <?php if ($evolution !== null): ?>
                            <span class="trend-delta <?= $evolution >= 0 ? 'up' : 'down' ?>"><?= $evolution >= 0 ? '▲ +' : '▼ ' ?><?= number_format($evolution, 0, ',', ' ') ?> % <span>vs <?= $jours ?> jours précédents</span></span>
                        <?php else: ?>
                            <span class="trend-delta neutral">visites sur <?= $jours ?> jours</span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($totaux['visites'] === 0): ?>
                    <p class="empty-state">Aucune visite enregistrée sur cette période. La courbe apparaîtra dès qu'un visiteur arrivera sur le site.</p>
                <?php else: ?>
                    <div class="trend" role="img" aria-label="Courbe des visites par jour du <?= date_fr($debut, $moisFr) ?> au <?= date_fr($fin, $moisFr) ?>, maximum <?= $maxVisites ?>">
                        <div class="trend-axis" aria-hidden="true">
                            <?php foreach ($graduations as $g): ?><span style="bottom: <?= $g / $axeMax * 100 ?>%"><?= $g ?></span><?php endforeach; ?>
                        </div>
                        <div class="trend-plot">
                            <?php foreach ($graduations as $g): ?><div class="trend-gridline<?= $g === 0 ? ' base' : '' ?>" style="bottom: <?= $g / $axeMax * 100 ?>%"></div><?php endforeach; ?>
                            <svg class="trend-svg" viewBox="0 0 1000 1000" preserveAspectRatio="none" aria-hidden="true">
                                <defs>
                                    <linearGradient id="trendFill" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#7c83f5" stop-opacity="0.35"/>
                                        <stop offset="100%" stop-color="#7c83f5" stop-opacity="0"/>
                                    </linearGradient>
                                </defs>
                                <path d="<?= $aire ?>" fill="url(#trendFill)"/>
                                <path d="<?= $ligne ?>" fill="none" stroke="#7c83f5" stroke-width="2" stroke-linejoin="round" stroke-linecap="round" vector-effect="non-scaling-stroke"/>
                            </svg>
                            <?php $dernier = end($points); ?>
                            <span class="trend-end" style="left: <?= $dernier['x'] / 10 ?>%; bottom: <?= 100 - $dernier['y'] / 10 ?>%"></span>
                            <div class="trend-cols" style="--n: <?= $nbPoints ?>">
                                <?php foreach ($serie as $i => $j): $pt = $points[$i]; ?>
                                    <div class="trend-col<?= $i < $nbPoints / 2 ? ' left' : ' right' ?>" tabindex="0">
                                        <?php if ($j['demandes'] > 0): ?><span class="trend-lead" style="bottom: <?= 100 - $pt['y'] / 10 ?>%"></span><?php endif; ?>
                                        <span class="trend-dot" style="bottom: <?= 100 - $pt['y'] / 10 ?>%"></span>
                                        <div class="trend-tip">
                                            <span class="trend-tip-date"><?= $joursFrMaj[(int) $j['date']->format('w')] ?> <?= date_fr($j['date'], $moisFr) ?></span>
                                            <span class="trend-tip-row"><b><?= $j['visites'] ?></b> visite<?= $j['visites'] > 1 ? 's' : '' ?> · <?= $j['vues'] ?> page<?= $j['vues'] > 1 ? 's' : '' ?> vue<?= $j['vues'] > 1 ? 's' : '' ?></span>
                                            <?php if ($j['demandes'] > 0): ?><span class="trend-tip-row lead"><?= $j['demandes'] ?> demande<?= $j['demandes'] > 1 ? 's' : '' ?> reçue<?= $j['demandes'] > 1 ? 's' : '' ?></span><?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div></div>
                        <div class="trend-dates" aria-hidden="true">
                            <?php
                            $etiquettes = $jours <= 7 ? range(0, $nbPoints - 1) : [0, (int) round(($nbPoints - 1) / 3), (int) round(($nbPoints - 1) * 2 / 3), $nbPoints - 1];
                            foreach ($etiquettes as $i): ?>
                                <span style="left: <?= $points[$i]['x'] / 10 ?>%"><?= $jours <= 7 ? $joursFr[(int) $serie[$i]['date']->format('w')] . ' ' . $serie[$i]['date']->format('j') : date_fr($serie[$i]['date'], $moisFr) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <p class="trend-legend"><span class="trend-legend-item"><span class="trend-legend-line"></span> Visites</span><span class="trend-legend-item"><span class="trend-legend-lead"></span> Jour avec une demande de devis ou d'estimation</span></p>
                <?php endif; ?>
            </div>

            <?php if ($totaux['visites'] > 0 || $demandes > 0): ?>
            <div class="card">
                <h2>Détail jour par jour</h2>
                <div class="day-table-wrap">
                    <table class="data-table day-table">
                        <thead>
                            <tr><th>Jour</th><th>Visites</th><th class="num">Pages vues</th><th class="num">Demandes</th><th>Provenance principale</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_reverse($serie) as $j): $weekend = in_array($j['date']->format('w'), ['0', '6'], true); ?>
                                <tr class="<?= $j['visites'] === 0 && $j['demandes'] === 0 ? 'is-empty' : '' ?><?= $weekend ? ' is-weekend' : '' ?>">
                                    <td class="day-date"><span><?= $joursFrMaj[(int) $j['date']->format('w')] ?></span> <?= date_fr($j['date'], $moisFr) ?></td>
                                    <td class="day-visits">
                                        <div class="day-visits-inner">
                                            <span class="day-bar"><span style="width: <?= round($j['visites'] / $maxVisites * 100) ?>%"></span></span>
                                            <b><?= $j['visites'] ?></b>
                                        </div>
                                    </td>
                                    <td class="num"><?= $j['vues'] ?></td>
                                    <td class="num"><?php if ($j['demandes'] > 0): ?><span class="day-lead"><?= $j['demandes'] ?></span><?php else: ?><span class="day-zero">-</span><?php endif; ?></td>
                                    <td class="day-source"><?= $j['source'] !== null ? e($j['source']) : '<span class="day-zero">-</span>' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr><td>Total</td><td><b><?= nombre($totaux['visites']) ?></b></td><td class="num"><?= nombre($totaux['vues']) ?></td><td class="num"><?= nombre($demandes) ?></td><td></td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <div class="stats-columns">
                <div class="card">
                    <h2>D'où viennent les visiteurs</h2>
                    <?php if (empty($sources)): ?>
                        <p class="empty-state">Pas encore de données.</p>
                    <?php else: ?>
                        <table class="data-table">
                            <thead><tr><th>Provenance</th><th class="num">Visites</th><th class="num">Part</th></tr></thead>
                            <tbody>
                                <?php foreach (array_slice($sources, 0, 10, true) as $nom => $n): ?>
                                    <tr><td><?= e($nom) ?></td><td class="num"><?= nombre($n) ?></td><td class="num"><?= pourcent($n, $totaux['visites']) ?></td></tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <div class="card">
                    <h2>Pages d'arrivée</h2>
                    <?php if (empty($entrees)): ?>
                        <p class="empty-state">Pas encore de données.</p>
                    <?php else: ?>
                        <table class="data-table">
                            <thead><tr><th>Page</th><th class="num">Visites</th></tr></thead>
                            <tbody>
                                <?php foreach (array_slice($entrees, 0, 10, true) as $page => $n): ?>
                                    <tr><td><a href="..<?= e($page) ?>" target="_blank"><?= e($page) ?></a></td><td class="num"><?= nombre($n) ?></td></tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card">
                <h2>Pages les plus vues</h2>
                <?php if (empty($pages)): ?>
                    <p class="empty-state">Pas encore de données.</p>
                <?php else: ?>
                    <table class="data-table">
                        <thead><tr><th>Page</th><th class="num">Vues</th></tr></thead>
                        <tbody>
                            <?php foreach (array_slice($pages, 0, 15, true) as $page => $n): ?>
                                <tr><td><a href="..<?= e($page) ?>" target="_blank"><?= e($page) ?></a></td><td class="num"><?= nombre($n) ?></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <p class="stats-note">Comptage sans cookie et sans donnée personnelle : seuls des totaux par jour sont enregistrés. Les robots (Google, aperçus de liens…) sont exclus. Une « visite » correspond à une arrivée sur le site depuis l'extérieur.</p>
        </main>
    </div>
</body>
</html>
