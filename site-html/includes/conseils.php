<?php
/**
 * Rubrique Conseils - Pertec.fr
 *
 * Les articles sont déclarés dans includes/articles.php avec leur date de publication.
 * Un article daté dans le futur est programmé : il reste invisible (liste, sitemap,
 * page en 404) jusqu'à ce jour-là, puis apparaît tout seul.
 */

function conseils_tous(): array {
    static $articles = null;
    if ($articles === null) {
        $articles = require __DIR__ . '/articles.php';
    }
    return $articles;
}

function conseil_est_publie(array $article): bool {
    return $article['date'] <= date('Y-m-d');
}

/**
 * Articles publiés, du plus récent au plus ancien.
 */
function conseils_publies(): array {
    $publies = array_filter(conseils_tous(), 'conseil_est_publie');
    uasort($publies, fn($a, $b) => strcmp($b['date'], $a['date']));
    return $publies;
}

/**
 * Article demandé par une page ; page 404 s'il n'existe pas ou n'est pas encore publié.
 */
function conseil(string $slug): array {
    $article = conseils_tous()[$slug] ?? null;
    if ($article === null || !conseil_est_publie($article)) {
        require dirname(__DIR__) . '/404.php';
        exit;
    }
    $article['modifie'] = $article['modifie'] ?? $article['date'];
    return $article;
}

/**
 * 2026-10-13 -> « 13 octobre 2026 »
 */
function date_longue_fr(string $ymd): string {
    $mois = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    [$a, $m, $j] = array_map('intval', explode('-', $ymd));
    return $j . ' ' . $mois[$m] . ' ' . $a;
}
