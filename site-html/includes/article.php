<?php
/**
 * Mise en page commune des articles de la rubrique Conseils - Pertec.fr
 *
 * Usage dans conseils/<slug>.php :
 *   require_once dirname(__DIR__) . '/includes/article.php';
 *   $article = article_debut('mon-slug', 'Titre Google | Pertec', 'Titre <span class="gradient-text">affiché</span>', 7);
 *   ... contenu ...
 *   article_fin('Titre de l'appel', 'Texte de l'appel', [['/url', 'Lien'], ...]);
 */

require_once dirname(__DIR__) . '/config/site.php';
require_once __DIR__ . '/conseils.php';

/**
 * Affiche l'en-tête du site et de l'article. Renvoie l'article (registre).
 * $h1 est du HTML de confiance (écrit dans le fichier de l'article).
 */
function article_debut(string $slug, string $titreGoogle, string $h1, int $minutes): array {
    global $pageTitle, $pageDescription, $canonicalUrl, $activeMenu, $ogData, $schemaJson, $noindex;

    $article = conseil($slug);

    $pageTitle = $titreGoogle;
    $pageDescription = $article['description'];
    $canonicalUrl = '/conseils/' . $slug;
    $activeMenu = 'conseils';
    $ogData = ['type' => 'article', 'title' => $article['titre']];
    $schemaJson = json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $article['titre'],
        'description' => $article['description'],
        'datePublished' => $article['date'],
        'dateModified' => $article['modifie'],
        'inLanguage' => 'fr-FR',
        'mainEntityOfPage' => 'https://pertec.fr' . $canonicalUrl,
        'image' => 'https://pertec.fr' . ($article['image'] ?? '/images/og-pertec.jpg'),
        'author' => ['@type' => 'Person', 'name' => 'Fabien Perrin', 'url' => 'https://pertec.fr/a-propos'],
        'publisher' => ['@id' => 'https://pertec.fr/#entreprise', '@type' => 'ProfessionalService', 'name' => 'Pertec'],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

    require dirname(__DIR__) . '/includes/header.php';
    ?>

  <article class="article">
    <header class="page-header article-header">
      <div class="container">
<?php if ($article['apercu']): ?>
        <p class="article-apercu">Aperçu : article programmé, visible par tous le <?= date_longue_fr($article['date']) ?>.</p>
<?php endif; ?>
        <nav class="article-breadcrumb" aria-label="Fil d'Ariane"><a href="/">Accueil</a> › <a href="/conseils/">Conseils</a> › <span><?= htmlspecialchars($article['categorie']) ?></span></nav>
        <h1 data-animate><?= $h1 ?></h1>
        <p class="article-meta" data-animate>Par <a href="/a-propos">Fabien Perrin</a>, créateur de sites à Évreux · <time datetime="<?= $article['date'] ?>"><?= date_longue_fr($article['date']) ?></time> · <?= $minutes ?> min de lecture</p>
      </div>
    </header>

    <div class="container">
      <div class="article-prose">
<?php
    return $article;
}

/**
 * Encadré d'appel au devis, liens « À lire aussi » et pied de page.
 * Les liens vers des articles pas encore publiés sont ignorés automatiquement.
 */
function article_fin(string $ctaTitre, string $ctaTexte, array $liens = []): void {
    $articles = conseils_tous();
    $liensOk = [];
    foreach ($liens as [$url, $texte]) {
        if (preg_match('#^/conseils/([a-z0-9-]+)$#', $url, $m) && isset($articles[$m[1]]) && !conseil_est_publie($articles[$m[1]])) {
            continue;
        }
        $liensOk[] = '<a href="' . htmlspecialchars($url) . '">' . htmlspecialchars($texte) . '</a>';
    }
    ?>
        <div class="article-cta glass-strong">
          <h2><?= htmlspecialchars($ctaTitre) ?></h2>
          <p><?= htmlspecialchars($ctaTexte) ?></p>
          <div class="hero-buttons">
            <a href="/contact" class="btn btn-primary">Demander un devis</a>
            <a href="/tarifs" class="btn btn-secondary">Estimer le prix</a>
          </div>
        </div>
<?php if ($liensOk): ?>

        <p class="article-related">À lire aussi : <?= implode(' · ', $liensOk) ?></p>
<?php endif; ?>
      </div>
    </div>
  </article>

<?php
    require dirname(__DIR__) . '/includes/footer.php';
}

/**
 * Bloc FAQ : [[question, réponse], ...] (texte brut, échappé).
 */
function article_faq(array $questions): void {
    echo "        <div class=\"faq-list article-faq\">\n";
    foreach ($questions as [$q, $r]) {
        echo '          <div class="faq-item glass">' . "\n"
           . '            <button class="faq-question"><span>' . htmlspecialchars($q) . '</span><span>+</span></button>' . "\n"
           . '            <div class="faq-answer"><p>' . htmlspecialchars($r) . '</p></div>' . "\n"
           . "          </div>\n";
    }
    echo "        </div>\n";
}

/**
 * Figure avec image, texte alternatif et légende.
 */
function article_figure(string $src, int $w, int $h, string $alt, string $legende): void {
    echo '        <figure class="article-figure">' . "\n"
       . '          <img src="' . htmlspecialchars($src) . '" width="' . $w . '" height="' . $h . '" loading="lazy" decoding="async" alt="' . htmlspecialchars($alt) . '">' . "\n"
       . '          <figcaption>' . htmlspecialchars($legende) . "</figcaption>\n"
       . "        </figure>\n";
}
