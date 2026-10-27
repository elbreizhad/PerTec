<?php
require_once __DIR__ . '/config.php';
require_once dirname(__DIR__) . '/includes/conseils.php';
require_once dirname(__DIR__) . '/includes/stats.php';
require_login();

$aujourdhui = new DateTimeImmutable('today');
$articles = conseils_tous();
uasort($articles, fn($a, $b) => strcmp($b['date'], $a['date']));

// Vues et entrées de chaque article depuis la publication du plus ancien
$vues = [];
$entrees = [];
$publies = array_filter($articles, 'conseil_est_publie');
if ($publies) {
    $debut = new DateTimeImmutable(min(array_column($publies, 'date')));
    foreach (stats_load($debut, $aujourdhui) as $jour) {
        foreach ($jour['pages'] ?? [] as $page => $n) $vues[$page] = ($vues[$page] ?? 0) + $n;
        foreach ($jour['entrees'] ?? [] as $page => $n) $entrees[$page] = ($entrees[$page] ?? 0) + $n;
    }
}

$programmes = array_diff_key($articles, $publies);
$prochain = null;
foreach ($programmes as $slug => $a) {
    if ($prochain === null || $a['date'] < $prochain['date']) $prochain = $a + ['slug' => $slug];
}
$totalVues = 0;
foreach ($publies as $slug => $a) $totalVues += $vues['/conseils/' . $slug] ?? 0;

$moisFr = ['', 'janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.'];
function date_courte(string $ymd, array $mois): string {
    [$a, $m, $j] = array_map('intval', explode('-', $ymd));
    return $j . ' ' . $mois[$m] . ' ' . $a;
}
function dans_jours(string $ymd, DateTimeImmutable $aujourdhui): string {
    $n = (int) $aujourdhui->diff(new DateTimeImmutable($ymd))->days;
    return $n === 1 ? 'demain' : 'dans ' . $n . ' jours';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles - Admin Pertec</title>
    <link rel="stylesheet" href="style.css?v=<?= filemtime(__DIR__ . '/style.css') ?>">
</head>
<body>
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>

        <main class="admin-main">
            <header class="admin-header">
                <h1>Articles</h1>
                <div class="header-actions">
                    <a href="/conseils/" class="btn btn-small btn-secondary" target="_blank" rel="noopener">Voir la rubrique Conseils</a>
                </div>
            </header>

            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-content">
                        <span class="stat-value"><?= count($publies) ?></span>
                        <span class="stat-label">Publiés</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <span class="stat-value"><?= count($programmes) ?></span>
                        <span class="stat-label">Programmés</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <span class="stat-value"><?= $prochain ? date_courte($prochain['date'], $moisFr) : '-' ?></span>
                        <span class="stat-label"><?= $prochain ? 'Prochaine parution, ' . dans_jours($prochain['date'], $aujourdhui) : 'Aucun article programmé' ?></span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-content">
                        <span class="stat-value"><?= number_format($totalVues, 0, ',', ' ') ?></span>
                        <span class="stat-label">Vues des articles</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="articles-table-wrap">
                    <table class="data-table articles-table">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Statut</th>
                                <th>Publication</th>
                                <th>Mise à jour</th>
                                <th class="num">Vues</th>
                                <th class="num">Entrées</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($articles as $slug => $a):
                                $publie = conseil_est_publie($a);
                                $url = '/conseils/' . $slug;
                            ?>
                            <tr class="<?= $publie ? '' : 'is-scheduled' ?>">
                                <td class="article-cell">
                                    <strong><?= e($a['titre']) ?></strong>
                                    <span><?= e($a['categorie']) ?> · <?= e($url) ?></span>
                                </td>
                                <td>
                                    <?php if ($publie): ?>
                                        <span class="status status-live">Publié</span>
                                    <?php else: ?>
                                        <span class="status status-scheduled">Programmé</span>
                                        <span class="status-sub"><?= dans_jours($a['date'], $aujourdhui) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="nowrap"><?= date_courte($a['date'], $moisFr) ?></td>
                                <td class="nowrap"><?= !empty($a['modifie']) && $a['modifie'] !== $a['date'] ? date_courte($a['modifie'], $moisFr) : '<span class="muted">-</span>' ?></td>
                                <td class="num"><?= $publie ? number_format($vues[$url] ?? 0, 0, ',', ' ') : '<span class="muted">-</span>' ?></td>
                                <td class="num"><?= $publie ? number_format($entrees[$url] ?? 0, 0, ',', ' ') : '<span class="muted">-</span>' ?></td>
                                <td><a href="<?= e($url) ?>" class="btn btn-small" target="_blank" rel="noopener"><?= $publie ? 'Voir' : 'Aperçu' ?></a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="stats-note">Les articles programmés apparaissent seuls sur le site à leur date (liste, sitemap, page). Avant, ils sont invisibles pour les visiteurs et Google ; vous seul pouvez les ouvrir en aperçu tant que vous êtes connecté ici. « Entrées » : visites arrivées directement sur l'article depuis l'extérieur (Google, réseaux, lien direct). Vos propres visites ne sont pas comptées.</p>
            </div>
        </main>
    </div>
</body>
</html>
