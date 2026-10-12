<?php
$pageTitle = "Création Site Internet Électricien | Clients Locaux | Pertec";
$pageDescription = "Site internet pour électricien. Attirez des clients locaux avec un site professionnel et un référencement optimisé. Site clé en main à partir de 490€.";
$canonicalUrl = "/sites-metiers/electricien";
$activeMenu = "services";

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('électriciens', '/sites-metiers/electricien');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <section class="hero"><div class="container"><div class="hero-content">
    <div class="hero-badge glass" data-animate><span class="dot"></span>Site pour électriciens</div>
    <h1 data-animate>Site internet pour<br><span class="gradient-text">électricien</span></h1>
    <p data-animate>Installations, dépannages, domotique : montrez votre expertise et attirez des clients qualifiés. Votre site travaille pour vous 24h/24.</p>
    <div class="hero-buttons" data-animate><a href="/contact" class="btn btn-primary">Demander un devis gratuit</a><a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a></div>
    <div class="hero-trust" data-animate>
      <div class="trust-item"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Certifications mises en avant</div>
      <div class="trust-item"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>SEO local</div>
      <div class="trust-item"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>À partir de 490€</div>
    </div>
  </div></div></section>

  <section><div class="container">
    <div class="section-header"><h2 class="section-title" data-animate>Pourquoi un site <span class="gradient-text">pour électricien</span> ?</h2></div>
    <div class="features-grid">
      <div class="feature-card glass" data-animate><div class="feature-icon">🔍</div><h3>Référencement local</h3><p>"Électricien Évreux", "installation électrique Rouen"... Soyez trouvé par les clients de votre zone.</p></div>
      <div class="feature-card glass" data-animate><div class="feature-icon">📜</div><h3>Certifications</h3><p>Qualifelec, RGE, assurances... Affichez vos qualifications pour rassurer les prospects.</p></div>
      <div class="feature-card glass" data-animate><div class="feature-icon">🏠</div><h3>Domotique</h3><p>Mettez en avant vos compétences en domotique et maison connectée.</p></div>
      <div class="feature-card glass" data-animate><div class="feature-icon">📸</div><h3>Réalisations</h3><p>Galerie photos de vos chantiers. Tableaux électriques, installations... Montrez votre savoir-faire.</p></div>
    </div>
  </div></section>

  <section><div class="container">
    <div class="section-header"><h2 class="section-title" data-animate>Contenu de votre <span class="gradient-text">site électricien</span></h2></div>
    <div class="features-grid">
      <div class="feature-card glass" data-animate><div class="feature-icon">⚡</div><h3>Services détaillés</h3><p>Installation, rénovation, dépannage, mise aux normes, domotique... Pages dediees pour le SEO.</p></div>
      <div class="feature-card glass" data-animate><div class="feature-icon">🏆</div><h3>Certifications</h3><p>Logos Qualifelec, RGE, assurances bien visibles. Confiance immédiate.</p></div>
      <div class="feature-card glass" data-animate><div class="feature-icon">📍</div><h3>Zone couverte</h3><p>Liste des villes, carte interactive. Référencement local renforcé.</p></div>
      <div class="feature-card glass" data-animate><div class="feature-icon">📞</div><h3>Contact rapide</h3><p>Numéro cliquable, formulaire de devis. Conversion optimisee.</p></div>
    </div>
  </div></section>

  <section class="cta-section"><div class="container"><div class="cta-box glass-strong" data-animate>
    <h2 class="section-title">Votre site <span class="gradient-text">électricien</span></h2>
    <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit sous 24h.</p>
    <div class="hero-buttons" style="justify-content: center;"><a href="/contact" class="btn btn-primary">Demander un devis</a></div>
  </div></div></section>

  <section><div class="container">
    <div class="section-header"><h2 class="section-title" data-animate>Autres <span class="gradient-text">métiers</span></h2></div>
    <div class="villes-tags" style="justify-content: center; max-width: 800px; margin: 0 auto;" data-animate>
      <a href="/sites-metiers/plombier" class="ville-tag" style="text-decoration: none;">Plombier</a>
      <a href="/sites-metiers/coiffeur" class="ville-tag" style="text-decoration: none;">Coiffeur</a>
      <a href="/secteurs/artisan" class="ville-tag" style="text-decoration: none;">Artisans du bâtiment</a>
    </div>
  </div></section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
