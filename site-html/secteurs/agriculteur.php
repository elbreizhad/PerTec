<?php
$pageTitle = 'Création site internet agriculteur et producteur local | Vente directe | Pertec';
$pageDescription = 'Site internet pour agriculteur, producteur local ou exploitation agricole. Vente directe, horaires, produits de saison. Dès 490 €. Devis gratuit 24h.';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];

$canonicalUrl = '/secteurs/agriculteur';
$activeMenu = '';

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('agriculteurs et producteurs locaux', '/secteurs/agriculteur');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour agriculteur
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">agriculteur et producteur local</span></h1>
      <p data-animate>Vendez en direct, sans intermédiaire. Présentez votre exploitation, vos produits de saison et vos points de vente.</p>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi un <span class="gradient-text">site internet</span> pour votre exploitation ?</h2>
        <p class="section-subtitle" data-animate>Les consommateurs cherchent des produits locaux et veulent savoir d'où vient ce qu'ils mangent.</p>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem; font-size: 1.1rem;">
              <strong style="color: white;">La demande de produits locaux</strong> ne cesse de croître. Un site internet vous permet d'être trouvé par les consommateurs de votre région.
            </p>
            <ul style="color: var(--gray-400); list-style: none;">
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Présenter vos produits et vos méthodes
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Indiquer vos points de vente et horaires
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Annoncer les produits disponibles selon la saison
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Être visible sur "producteur local + votre commune"
              </li>
            </ul>
          </div>
          <div class="zone-map">
            <img src="<?= asset('/images/secteurs/agriculteur.svg') ?>" alt="Illustration site agriculteur producteur local" style="max-width: 280px; height: auto;">
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que contient votre <span class="gradient-text">site agricole</span></h2>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🥬</div>
          <h3>Catalogue produits</h3>
          <p>Photos de vos légumes, fruits, viandes, fromages... avec indication de disponibilité.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📍</div>
          <h3>Points de vente</h3>
          <p>Ferme, marchés, AMAP, magasins de producteurs où vous trouver.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🕐</div>
          <h3>Horaires d'ouverture</h3>
          <p>Jours et heures de vente à la ferme, marchés hebdomadaires.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🚜</div>
          <h3>Présentation exploitation</h3>
          <p>Histoire, valeurs, méthodes de culture ou d'élevage.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📅</div>
          <h3>Actualités saison</h3>
          <p>Annoncez les récoltes, les nouveautés, les événements portes ouvertes.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📦</div>
          <h3>Commande en ligne</h3>
          <p>Formulaire de commande pour paniers, colis ou produits spécifiques.</p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tous types de <span class="gradient-text">producteurs</span></h2>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass"><span>🥬</span><div><strong>Maraîcher</strong></div></div>
        <div class="advantage-item glass"><span>🍎</span><div><strong>Arboriculteur</strong></div></div>
        <div class="advantage-item glass"><span>🐄</span><div><strong>Éleveur</strong></div></div>
        <div class="advantage-item glass"><span>🧀</span><div><strong>Fromager</strong></div></div>
        <div class="advantage-item glass"><span>🍯</span><div><strong>Apiculteur</strong></div></div>
        <div class="advantage-item glass"><span>🍷</span><div><strong>Viticulteur</strong></div></div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tarifs site <span class="gradient-text">agriculteur</span></h2>
      </div>
      <div class="features-grid home-pricing">
        <div class="feature-card glass" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine3']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine3']['creation'], 0, ',', ' ') ?> €</p>
          <p>Accueil, produits, points de vente, contact.</p>
        </div>
        <div class="feature-card glass portfolio-featured" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine5']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine5']['creation'], 0, ',', ' ') ?> €</p>
          <p>+ Actualités saison, galerie photos, formulaire commande.</p>
        </div>
      </div>
      <p class="home-pricing-note" data-animate>Puis <?= $mensuel ?> €/mois : hébergement, mises à jour, modifications sous 48h. <?= htmlspecialchars($tarifs['config']['tva_mention']) ?>.</p>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Vendez en <span class="gradient-text">direct</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit sous 24h. Un site qui met en valeur votre savoir-faire.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
