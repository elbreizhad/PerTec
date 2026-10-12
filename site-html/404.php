<?php
http_response_code(404);
$pageTitle = 'Page introuvable | Pertec.fr';
$pageDescription = 'Cette page n\'existe pas ou a été déplacée.';
$canonicalUrl = '/';
$activeMenu = '';
$noindex = true;

require_once __DIR__ . '/includes/header.php';
?>

  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Erreur 404
      </div>
      <h1 data-animate>Cette page <span class="gradient-text">n'existe pas</span></h1>
      <p data-animate>Le lien est peut-être ancien, ou l'adresse contient une faute de frappe. Voici où aller :</p>
      <div class="hero-buttons" style="justify-content: center; margin-top: 2rem;" data-animate>
        <a href="/" class="btn btn-primary">Retour à l'accueil</a>
        <a href="/contact" class="btn btn-secondary">Demander un devis</a>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="features-grid">
        <a href="/tarifs" class="feature-card glass" style="text-decoration: none; color: inherit;" data-animate>
          <div class="feature-icon">💶</div>
          <h3>Tarifs</h3>
          <p>Estimez le prix de votre site en une minute.</p>
        </a>
        <a href="/realisations" class="feature-card glass" style="text-decoration: none; color: inherit;" data-animate>
          <div class="feature-icon">🖥️</div>
          <h3>Réalisations</h3>
          <p>Des sites créés pour des entreprises de la région.</p>
        </a>
        <a href="/services" class="feature-card glass" style="text-decoration: none; color: inherit;" data-animate>
          <div class="feature-icon">🧰</div>
          <h3>Services</h3>
          <p>Ce qui est inclus dans un site clé en main.</p>
        </a>
        <a href="/zone-intervention" class="feature-card glass" style="text-decoration: none; color: inherit;" data-animate>
          <div class="feature-icon">📍</div>
          <h3>Zone d'intervention</h3>
          <p>Évreux, l'Eure et la Normandie.</p>
        </a>
      </div>
    </div>
  </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
