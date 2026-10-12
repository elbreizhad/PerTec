<?php
$articles = require dirname(__DIR__) . '/includes/articles.php';

$pageTitle = 'Conseils site internet pour artisans et commerces | Pertec.fr';
$pageDescription = 'Prix, visibilité sur Google, avis clients, obligations légales : des conseils clairs pour les artisans, commerçants et indépendants qui veulent un site internet.';
$canonicalUrl = '/conseils/';
$activeMenu = 'conseils';

$moisFr = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];

require_once dirname(__DIR__) . '/includes/header.php';
?>

  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Conseils
      </div>
      <h1 data-animate>Conseils pour <span class="gradient-text">votre site internet</span></h1>
      <p data-animate>Des réponses claires aux questions qu'on se pose avant de se lancer : prix, visibilité sur Google, avis clients, obligations.</p>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="articles-grid">
<?php foreach ($articles as $slug => $a):
    $date = new DateTimeImmutable($a['date']); ?>
        <a href="/conseils/<?= $slug ?>" class="article-card glass" data-animate>
          <div class="article-card-image" style="background-image: url('<?= htmlspecialchars($a['image']) ?>');">
            <span class="portfolio-category glass"><?= htmlspecialchars($a['categorie']) ?></span>
          </div>
          <div class="article-card-content">
            <time datetime="<?= $a['date'] ?>"><?= $date->format('j') ?> <?= $moisFr[(int) $date->format('n')] ?> <?= $date->format('Y') ?></time>
            <h2><?= htmlspecialchars($a['titre']) ?></h2>
            <p><?= htmlspecialchars($a['resume']) ?></p>
            <span class="btn-link">Lire l'article →</span>
          </div>
        </a>
<?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Une question sur <span class="gradient-text">votre projet</span> ?</h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Je vous réponds sous 24h, sans engagement.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Poser ma question</a>
          <a href="/tarifs" class="btn btn-secondary">Estimer le prix</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
