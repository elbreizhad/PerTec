<?php
$pageTitle = 'Réalisations | Portfolio Sites Internet | Pertec.fr';
$pageDescription = 'Découvrez mes réalisations de sites internet pour artisans, restaurants et commerces. Portfolio de création web clé en main.';
$canonicalUrl = '/realisations';
$activeMenu = 'realisations';

require_once __DIR__ . '/includes/header.php';
?>

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Portfolio
      </div>
      <h1 data-animate>Mes <span class="gradient-text">réalisations</span></h1>
      <p data-animate>Découvrez quelques exemples de sites internet créés pour des entrepreneurs partout en France.</p>
    </div>
  </section>

  <!-- Portfolio Grid -->
  <section>
    <div class="container">
      <div class="portfolio-grid">
        <div class="portfolio-card glass portfolio-featured" data-animate>
          <div class="portfolio-image" style="background-image: url('/images/portfolio/cfssis.jpg'); background-size: cover; background-position: top center;">
            <span class="portfolio-category glass">Sécurité</span>
          </div>
          <div class="portfolio-content">
            <h3>CFSSIS</h3>
            <p>Site vitrine complet pour un centre de formation spécialisé dans la sécurité incendie et la sûreté. Design professionnel et formulaires de contact.</p>
            <div class="portfolio-tags">
              <span>Formation</span>
              <span>Sécurité</span>
              <span>Multi-pages</span>
            </div>
            <a href="https://cfssis.fr" target="_blank" rel="noopener" class="btn btn-secondary portfolio-btn">Voir le site →</a>
          </div>
        </div>

        <div class="portfolio-card glass portfolio-featured" data-animate>
          <div class="portfolio-image" style="background-image: url('/images/portfolio/ilmanque1.jpg'); background-size: cover; background-position: top center;">
            <span class="portfolio-category glass">E-commerce</span>
          </div>
          <div class="portfolio-content">
            <h3>Il Manque 1</h3>
            <p>Site e-commerce moderne et dynamique. Interface intuitive avec catalogue produits, panier et système de commande en ligne.</p>
            <div class="portfolio-tags">
              <span>E-commerce</span>
              <span>Boutique</span>
              <span>Responsive</span>
            </div>
            <a href="https://ilmanque1.fr" target="_blank" rel="noopener" class="btn btn-secondary portfolio-btn">Voir le site →</a>
          </div>
        </div>

        <div class="portfolio-card glass" data-animate>
          <div class="portfolio-image" style="background-image: url('/images/portfolio/solution-securite.jpg'); background-size: cover; background-position: top center;">
            <span class="portfolio-category glass">Sécurité</span>
          </div>
          <div class="portfolio-content">
            <h3>Solution Sécurité</h3>
            <p>Site professionnel pour une entreprise de sécurité privée. Présentation des services de gardiennage, surveillance et protection.</p>
            <div class="portfolio-tags">
              <span>Gardiennage</span>
              <span>SEO local</span>
              <span>Responsive</span>
            </div>
            <a href="https://solution-securite.fr" target="_blank" rel="noopener" class="btn btn-secondary portfolio-btn">Voir le site →</a>
          </div>
        </div>

        <div class="portfolio-card glass" data-animate>
          <div class="portfolio-image" style="background-image: url('/images/portfolio/sts-securite.jpg'); background-size: cover; background-position: top center;">
            <span class="portfolio-category glass">Sécurité</span>
          </div>
          <div class="portfolio-content">
            <h3>STS Sécurité</h3>
            <p>Site vitrine pour une entreprise de services de sécurité. Design épuré, présentation des équipes et des services proposés.</p>
            <div class="portfolio-tags">
              <span>Sécurité privée</span>
              <span>Site vitrine</span>
              <span>Devis en ligne</span>
            </div>
            <a href="https://sts-securite.fr" target="_blank" rel="noopener" class="btn btn-secondary portfolio-btn">Voir le site →</a>
          </div>
        </div>

        <div class="portfolio-card glass" data-animate>
          <div class="portfolio-image" style="background-image: url('/images/portfolio/vigiprestance.jpg'); background-size: cover; background-position: top center;">
            <span class="portfolio-category glass">Sécurité</span>
          </div>
          <div class="portfolio-content">
            <h3>Vigiprestance</h3>
            <p>Site web moderne pour une société de surveillance et de gardiennage. Interface claire avec présentation des prestations et zone d'intervention.</p>
            <div class="portfolio-tags">
              <span>Surveillance</span>
              <span>Corporate</span>
              <span>Formulaire</span>
            </div>
            <a href="https://vigiprestance.fr" target="_blank" rel="noopener" class="btn btn-secondary portfolio-btn">Voir le site →</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Votre projet <span class="gradient-text">vous attend</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Vous aussi, obtenez un site professionnel qui vous ressemble. Devis gratuit sous 24h.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact.php" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/services.html" class="btn btn-secondary">Découvrir les services</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
