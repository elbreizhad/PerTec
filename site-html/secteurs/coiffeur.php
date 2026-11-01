<?php
$pageTitle = 'Création site internet coiffeur et salon de coiffure | Réservation | Pertec';
$pageDescription = 'Site internet pour salon de coiffure. Prestations, tarifs, équipe et réservation en ligne. Design élégant. Dès 490 €. Devis gratuit sous 24h.';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$formules = $tarifs['formule']['options'];

$canonicalUrl = '/secteurs/coiffeur';
$activeMenu = '';

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('salons de coiffure', '/secteurs/coiffeur');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour salon de coiffure
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">coiffeur</span></h1>
      <p data-animate>Un site élégant qui reflète votre savoir-faire. Présentez vos prestations, vos tarifs et permettez la prise de RDV en ligne.</p>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi un <span class="gradient-text">site internet</span> pour votre salon ?</h2>
        <p class="section-subtitle" data-animate>Les clients recherchent un coiffeur en ligne et comparent avant de se déplacer.</p>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem; font-size: 1.1rem;">
              <strong style="color: white;">Vos futurs clients</strong> regardent les avis Google, les photos de réalisations et les tarifs avant de réserver.
            </p>
            <ul style="color: var(--gray-400); list-style: none;">
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Présenter vos prestations et tarifs
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Montrer votre équipe et votre salon
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Permettre la réservation en ligne
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Apparaître sur "coiffeur + votre ville"
              </li>
            </ul>
          </div>
          <div class="zone-map">
            <img src="<?= asset('/images/secteurs/coiffeur.svg') ?>" alt="Illustration site salon de coiffure" style="max-width: 280px; height: auto;">
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que contient votre <span class="gradient-text">site de coiffeur</span></h2>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">✂️</div>
          <h3>Carte des prestations</h3>
          <p>Coupes, couleurs, soins, brushings... Tout est présenté avec les prix.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">👥</div>
          <h3>Votre équipe</h3>
          <p>Présentez vos coiffeurs et leurs spécialités pour rassurer les clients.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📅</div>
          <h3>Réservation en ligne</h3>
          <p>Vos clients choisissent leur créneau et le coiffeur directement sur le site.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📸</div>
          <h3>Galerie de réalisations</h3>
          <p>Photos avant/après pour montrer votre savoir-faire.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🕐</div>
          <h3>Horaires et accès</h3>
          <p>Horaires d'ouverture, adresse, plan d'accès et parking.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">⭐</div>
          <h3>Avis clients</h3>
          <p>Témoignages pour convaincre les nouveaux clients.</p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tous types de <span class="gradient-text">salons</span></h2>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass"><span>💇‍♀️</span><div><strong>Salon mixte</strong></div></div>
        <div class="advantage-item glass"><span>💈</span><div><strong>Barbier</strong></div></div>
        <div class="advantage-item glass"><span>🎨</span><div><strong>Coloriste</strong></div></div>
        <div class="advantage-item glass"><span>👰</span><div><strong>Coiffure mariage</strong></div></div>
        <div class="advantage-item glass"><span>🏠</span><div><strong>Coiffeur à domicile</strong></div></div>
        <div class="advantage-item glass"><span>🌿</span><div><strong>Salon bio</strong></div></div>
      </div>
    </div>
  </section>

  <!-- Aperçu démo -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Aperçu d'un <span class="gradient-text">site de coiffeur</span></h2>
        <p class="section-subtitle" data-animate>Voici à quoi pourrait ressembler votre futur site.</p>
      </div>

      <div class="demo-preview glass-strong" data-animate style="border-radius: 1rem; overflow: hidden; position: relative;">
        <div style="background: linear-gradient(135deg, #c9a86c, #b8956a); padding: 0.5rem; text-align: center; font-size: 0.85rem; color: #1a1a1a;">
          <strong>Élégance</strong> — Exemple de site salon de coiffure
        </div>
        <div style="background: #faf9f7; padding: 3rem; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center;">
          <div>
            <p style="color: #c9a86c; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 0.5rem;">Salon de coiffure</p>
            <h3 style="font-size: 1.75rem; color: #1a1a1a; margin-bottom: 1rem; font-weight: 400; font-family: 'Cormorant Garamond', Georgia, serif;">L'art de sublimer <span style="color: #c9a86c;">votre beauté</span></h3>
            <p style="color: #666; margin-bottom: 1.5rem;">Coupes, couleurs, soins. Une équipe passionnée à votre service...</p>
            <a href="/demos/coiffeur" target="_blank" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg>
              Voir la démo complète
            </a>
          </div>
          <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
              <div style="font-size: 1.5rem;">✂️</div>
              <span style="color: #1a1a1a;">Coupe femme · 45 €</span>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
              <div style="font-size: 1.5rem;">🎨</div>
              <span style="color: #1a1a1a;">Balayage · 75 €</span>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
              <div style="font-size: 1.5rem;">👥</div>
              <span style="color: #1a1a1a;">3 coiffeurs expérimentés</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tarifs site <span class="gradient-text">coiffeur</span></h2>
      </div>
      <div class="features-grid home-pricing">
        <div class="feature-card glass" data-animate>
          <h3>Site Vitrine</h3>
          <p class="home-price"><?= $formules['vitrine']['mensuel'] ?> €<span style="font-size: 1rem; font-weight: 400;">/mois</span></p>
          <p>Accueil, prestations, tarifs, équipe, contact.</p>
        </div>
        <div class="feature-card glass portfolio-featured" data-animate>
          <h3>Site Complet</h3>
          <p class="home-price"><?= $formules['complet']['mensuel'] ?> €<span style="font-size: 1rem; font-weight: 400;">/mois</span></p>
          <p>+ Réservation en ligne, galerie avancée.</p>
        </div>
      </div>
      <p class="home-pricing-note" data-animate>Engagement 24 mois, tout inclus. <a href="/tarifs" style="color: var(--primary);">Voir tous les tarifs →</a></p>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Attirez plus de <span class="gradient-text">clients</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit sous 24h. Un site élégant qui vous ressemble.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
