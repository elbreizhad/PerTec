<?php
$pageTitle = 'Création de site internet pour église ou paroisse | Pertec';
$pageDescription = 'Création de site internet pour église, paroisse ou association religieuse. Horaires, événements, dons en ligne. Site clé en main dès 490 €, devis en 24h.';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];

$canonicalUrl = '/secteurs/eglise';
$activeMenu = '';

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('églises et paroisses', '/secteurs/eglise');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour églises
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">pour église ou paroisse</span></h1>
      <p data-animate>Un site simple et efficace pour informer vos fidèles, annoncer vos événements et accueillir les nouveaux venus.</p>
    </div>
  </section>

  <!-- Pourquoi un site -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi un <span class="gradient-text">site internet</span> pour votre église ?</h2>
        <p class="section-subtitle" data-animate>Votre présence en ligne facilite la vie de votre communauté.</p>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem; font-size: 1.1rem;">
              <strong style="color: white;">Les fidèles et les visiteurs</strong> cherchent vos horaires, votre adresse et vos activités en ligne avant de venir.
            </p>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem;">
              Un site internet pour votre église vous permet de :
            </p>
            <ul style="color: var(--gray-400); list-style: none;">
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Afficher les horaires des messes et célébrations
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Annoncer vos événements et activités
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Recevoir les dons en ligne facilement
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Accueillir les nouveaux venus dans la foi
              </li>
            </ul>
          </div>
          <div class="zone-map">
            <span class="pin" style="font-size: 5rem;">⛪</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Fonctionnalités -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que vous <span class="gradient-text">obtenez</span></h2>
        <p class="section-subtitle" data-animate>Un site complet, adapté à la vie de votre communauté.</p>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🕐</div>
          <h3>Horaires des célébrations</h3>
          <p>Messes, cultes, offices... Vos horaires toujours à jour, faciles à trouver et à modifier.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📅</div>
          <h3>Agenda des événements</h3>
          <p>Concerts, retraites, catéchisme, groupes de prière... Tout au même endroit.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">💝</div>
          <h3>Dons en ligne</h3>
          <p>Bouton de don sécurisé pour soutenir votre paroisse facilement.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📍</div>
          <h3>Plan d'accès</h3>
          <p>Carte Google Maps intégrée pour trouver votre église facilement.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">👥</div>
          <h3>Présentation de l'équipe</h3>
          <p>Curé, pasteur, équipe pastorale, bénévoles... Mettez des visages sur les noms.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📧</div>
          <h3>Formulaire de contact</h3>
          <p>Pour les demandes de baptême, mariage, inscription au catéchisme...</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Types de communautés -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pour toutes les <span class="gradient-text">communautés</span></h2>
        <p class="section-subtitle" data-animate>Quelle que soit votre confession.</p>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass">
          <span>⛪</span>
          <div><strong>Paroisses catholiques</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>✝️</span>
          <div><strong>Églises protestantes</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🙏</span>
          <div><strong>Églises évangéliques</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>☦️</span>
          <div><strong>Églises orthodoxes</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🕊️</span>
          <div><strong>Communautés religieuses</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>📖</span>
          <div><strong>Associations cultuelles</strong></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Tarifs -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Combien coûte un site <span class="gradient-text">pour une église</span> ?</h2>
        <p class="section-subtitle" data-animate>Des formules adaptées aux budgets des associations.</p>
      </div>
      <div class="features-grid home-pricing">
        <div class="feature-card glass" data-animate>
          <h3><?= htmlspecialchars($offres['page_unique']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['page_unique']['creation'], 0, ',', ' ') ?> €</p>
          <p>Une page complète avec horaires, adresse, contact. L'essentiel pour être trouvé.</p>
        </div>
        <div class="feature-card glass portfolio-featured" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine5']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine5']['creation'], 0, ',', ' ') ?> €</p>
          <p>Accueil, horaires, événements, équipe pastorale, contact et dons.</p>
        </div>
      </div>
      <p class="home-pricing-note" data-animate>Puis <?= $mensuel ?> €/mois : hébergement, nom de domaine, mises à jour et modifications sous 48 h, sans engagement. <?= htmlspecialchars($tarifs['config']['tva_mention']) ?>.</p>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Prêt à accueillir <span class="gradient-text">en ligne</span> ?</h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Demandez votre devis gratuit pour un site au service de votre communauté.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
