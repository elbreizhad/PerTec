<?php
/**
 * Sitemap généré à chaque appel - Pertec.fr (servi à l'adresse /sitemap.xml)
 * Pages fixes ci-dessous ; les articles de la rubrique Conseils sont ajoutés
 * automatiquement dès leur date de publication (includes/articles.php).
 */
require_once __DIR__ . '/includes/conseils.php';

$pages = [
    // [chemin, fréquence de mise à jour, priorité]
    ['/', 'weekly', '1.0'],
    ['/services', 'monthly', '0.9'],
    ['/tarifs', 'monthly', '0.9'],
    ['/realisations', 'monthly', '0.8'],
    ['/a-propos', 'monthly', '0.7'],
    ['/contact', 'monthly', '0.8'],
    ['/zone-intervention', 'monthly', '0.7'],
    ['/creation-site-internet/evreux', 'monthly', '0.8'],
    ['/creation-site-internet/louviers', 'monthly', '0.7'],
    ['/creation-site-internet/vernon', 'monthly', '0.7'],
    ['/creation-site-internet/bernay', 'monthly', '0.7'],
    ['/creation-site-internet/pont-audemer', 'monthly', '0.7'],
    ['/creation-site-internet/val-de-reuil', 'monthly', '0.7'],
    ['/creation-site-internet/gisors', 'monthly', '0.7'],
    ['/creation-site-internet/les-andelys', 'monthly', '0.7'],
    ['/creation-site-internet/rouen', 'monthly', '0.7'],
    ['/creation-site-internet/le-havre', 'monthly', '0.7'],
    ['/secteurs/artisan', 'monthly', '0.8'],
    ['/secteurs/restaurant', 'monthly', '0.8'],
    ['/secteurs/commerces', 'monthly', '0.8'],
    ['/secteurs/professions-liberales', 'monthly', '0.8'],
    ['/secteurs/securite-privee', 'monthly', '0.8'],
    ['/secteurs/eglise', 'monthly', '0.8'],
    ['/sites-metiers/plombier', 'monthly', '0.7'],
    ['/sites-metiers/electricien', 'monthly', '0.7'],
    ['/sites-metiers/coiffeur', 'monthly', '0.7'],
    ['/mentions-legales', 'yearly', '0.3'],
    ['/politique-confidentialite', 'yearly', '0.3'],
    ['/conseils/', 'weekly', '0.7'],
];

header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($pages as [$chemin, $frequence, $priorite]) {
    echo "  <url>\n    <loc>https://pertec.fr$chemin</loc>\n    <changefreq>$frequence</changefreq>\n    <priority>$priorite</priority>\n  </url>\n";
}
foreach (conseils_publies() as $slug => $article) {
    $maj = $article['modifie'] ?? $article['date'];
    echo "  <url>\n    <loc>https://pertec.fr/conseils/$slug</loc>\n    <lastmod>$maj</lastmod>\n    <changefreq>monthly</changefreq>\n    <priority>0.8</priority>\n  </url>\n";
}
echo "</urlset>\n";
