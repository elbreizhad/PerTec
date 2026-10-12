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
    $serie[] = [
        'date' => $d,
        'visites' => $jour['visites'] ?? 0,
        'demandes' => ($jour['devis'] ?? 0) + ($jour['estimations'] ?? 0),
    ];
}
arsort($sources);
arsort($pages);
arsort($entrees);

$demandes = $totaux['devis'] + $totaux['estimations'];
$taux = $totaux['visites'] > 0 ? $demandes / $totaux['visites'] * 100 : null;
$maxVisites = max(1, max(array_column($serie, 'visites')));

// Graduation de l'axe : un pas « rond » qui couvre le maximum
$pas = 1;
foreach ([1, 2, 5, 10, 20, 50, 100, 200, 500, 1000] as $p) {
    $pas = $p;
    if ($maxVisites / $p <= 4) break;
}
$axeMax = (int) (ceil($maxVisites / $pas) * $pas);

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

            <div class="card">
                <h2>Visites par jour</h2>
                <?php if ($totaux['visites'] === 0): ?>
                    <p class="empty-state">Aucune visite enregistrée sur cette période. Les visites s'affichent ici dès qu'un visiteur arrive sur le site.</p>
                <?php else: ?>
                    <div class="chart" role="img" aria-label="Visites par jour, maximum <?= $maxVisites ?> sur la période">
                        <div class="chart-axis" aria-hidden="true">
                            <span><?= $axeMax ?></span>
                            <span><?= $axeMax / 2 == (int) ($axeMax / 2) ? $axeMax / 2 : '' ?></span>
                            <span>0</span>
                        </div>
                        <div class="chart-plot">
                            <div class="chart-grid" aria-hidden="true"><span></span><span></span><span></span></div>
                            <div class="chart-bars" style="--n: <?= count($serie) ?>">
                                <?php foreach ($serie as $j): ?>
                                    <div class="chart-col" tabindex="0">
                                        <div class="chart-bar" style="height: <?= round($j['visites'] / $axeMax * 100, 2) ?>%"></div>
                                        <?php if ($j['demandes'] > 0): ?><span class="chart-mark" title="Demande reçue"></span><?php endif; ?>
                                        <div class="chart-tip"><strong><?= date_fr($j['date'], $moisFr) ?></strong><br><?= $j['visites'] ?> visite<?= $j['visites'] > 1 ? 's' : '' ?><?php if ($j['demandes'] > 0): ?><br><?= $j['demandes'] ?> demande<?= $j['demandes'] > 1 ? 's' : '' ?><?php endif; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="chart-dates" aria-hidden="true">
                                <span><?= date_fr($debut, $moisFr) ?></span>
                                <span><?= date_fr($fin, $moisFr) ?></span>
                            </div>
                        </div>
                    </div>
                    <p class="chart-legend"><span class="chart-mark chart-mark-legend"></span> jour avec au moins une demande de devis ou d'estimation</p>
                <?php endif; ?>
            </div>

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
