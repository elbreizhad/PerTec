<?php
$pageTitle = 'Création site internet pompes funèbres | Discret et professionnel | Pertec';
$pageDescription = 'Site internet pour pompes funèbres et services funéraires. Design sobre, disponibilité 24h/24, devis en ligne. Dès 990 €. Devis gratuit sous 24h.';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];

$canonicalUrl = '/secteurs/pompes-funebres';
$activeMenu = '';

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('pompes funèbres', '/secteurs/pompes-funebres');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour pompes funèbres
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">pompes funèbres</span></h1>
      <p data-animate>Un site sobre et professionnel, disponible quand les familles en ont besoin. Accessible 24h/24, rassurant et clair.</p>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi un <span class="gradient-text">site internet</span> pour votre entreprise funéraire ?</h2>
        <p class="section-subtitle" data-animate>Les familles cherchent des informations dans un moment difficile. Votre site doit les rassurer.</p>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem; font-size: 1.1rem;">
              <strong style="color: white;">Dans ces moments délicats</strong>, les familles recherchent une entreprise de confiance, souvent en urgence et en dehors des heures d'ouverture.
            </p>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem;">
              Un site internet professionnel vous permet de :
            </p>
            <ul style="color: var(--gray-400); list-style: none;">
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Être joignable 24h/24 (numéro d'urgence visible)
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Présenter vos services avec clarté et dignité
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Rassurer les familles sur votre professionnalisme
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Apparaître sur Google quand on cherche un service funéraire
              </li>
            </ul>
          </div>
          <div class="zone-map">
            <span class="pin" style="font-size: 5rem;">🕯️</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que contient votre <span class="gradient-text">site funéraire</span></h2>
        <p class="section-subtitle" data-animate>Un site complet, sobre et adapté à votre activité.</p>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📞</div>
          <h3>Numéro d'urgence 24h/24</h3>
          <p>Numéro de téléphone toujours visible, cliquable sur mobile, pour être contacté à tout moment.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📋</div>
          <h3>Services détaillés</h3>
          <p>Organisation des obsèques, transport, soins, cérémonie, monuments funéraires, fleurs...</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🏛️</div>
          <h3>Présentation des locaux</h3>
          <p>Photos de vos chambres funéraires, salons de recueillement et véhicules.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">💐</div>
          <h3>Catalogue articles</h3>
          <p>Cercueils, urnes, plaques, fleurs... Présentation sobre et respectueuse.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📍</div>
          <h3>Zone d'intervention</h3>
          <p>Communes desservies, hôpitaux, EHPAD, domiciles. Référencement local optimisé.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📝</div>
          <h3>Devis en ligne</h3>
          <p>Formulaire de contact discret pour les familles qui préfèrent écrire.</p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tous les <span class="gradient-text">services funéraires</span></h2>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass"><span>⚱️</span><div><strong>Pompes funèbres</strong></div></div>
        <div class="advantage-item glass"><span>🪦</span><div><strong>Marbrerie</strong></div></div>
        <div class="advantage-item glass"><span>💐</span><div><strong>Fleurs de deuil</strong></div></div>
        <div class="advantage-item glass"><span>🚐</span><div><strong>Transport funéraire</strong></div></div>
        <div class="advantage-item glass"><span>🏛️</span><div><strong>Chambre funéraire</strong></div></div>
        <div class="advantage-item glass"><span>📜</span><div><strong>Contrats obsèques</strong></div></div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tarifs site <span class="gradient-text">pompes funèbres</span></h2>
        <p class="section-subtitle" data-animate>Un site sobre, professionnel, qui inspire confiance.</p>
      </div>
      <div class="features-grid home-pricing">
        <div class="feature-card glass" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine5']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine5']['creation'], 0, ',', ' ') ?> €</p>
          <p>Accueil, services, équipe, zone d'intervention, contact.</p>
        </div>
        <div class="feature-card glass portfolio-featured" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine10']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine10']['creation'], 0, ',', ' ') ?> €</p>
          <p>+ Catalogue articles, galerie, témoignages, contrats obsèques.</p>
        </div>
      </div>
      <p class="home-pricing-note" data-animate>Puis <?= $mensuel ?> €/mois : hébergement, mises à jour, modifications sous 48h. <?= htmlspecialchars($tarifs['config']['tva_mention']) ?>.</p>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Un site qui <span class="gradient-text">inspire confiance</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit sous 24h. Design sobre et professionnel garanti.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
