<?php
$pageTitle = 'Création site internet thérapeute et praticien bien-être | Prise de RDV | Pertec';
$pageDescription = 'Site internet pour thérapeute, sophrologue, ostéopathe ou praticien bien-être. Présentation, tarifs, prise de RDV en ligne. À partir de 59€/mois.';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$formules = $tarifs['formule']['options'];

$canonicalUrl = '/secteurs/therapeute';
$activeMenu = '';

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('thérapeutes et praticiens bien-être', '/secteurs/therapeute');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour thérapeute
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">thérapeute et praticien bien-être</span></h1>
      <p data-animate>Inspirez confiance dès le premier clic. Présentez vos pratiques, vos tarifs et permettez la prise de RDV en ligne.</p>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi un <span class="gradient-text">site internet</span> pour votre cabinet ?</h2>
        <p class="section-subtitle" data-animate>Les personnes recherchent un praticien en ligne et choisissent en fonction de la confiance dégagée.</p>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem; font-size: 1.1rem;">
              <strong style="color: white;">Vos futurs patients</strong> vous découvrent sur Google. Votre site doit rassurer et expliquer clairement ce que vous proposez.
            </p>
            <ul style="color: var(--gray-400); list-style: none;">
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Expliquer vos pratiques et votre approche
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Afficher vos tarifs et remboursements éventuels
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Permettre la prise de rendez-vous en ligne
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Être visible sur "[pratique] + votre ville"
              </li>
            </ul>
          </div>
          <div class="zone-map">
            <img src="<?= asset('/images/secteurs/therapeute.svg') ?>" alt="Illustration site thérapeute bien-être" style="max-width: 280px; height: auto;">
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que contient votre <span class="gradient-text">site de thérapeute</span></h2>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">👤</div>
          <h3>Présentation personnelle</h3>
          <p>Votre parcours, vos formations, votre approche pour créer un lien de confiance.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">✨</div>
          <h3>Vos pratiques</h3>
          <p>Description de chaque soin ou thérapie, pour qui, pour quoi.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">💰</div>
          <h3>Tarifs et durées</h3>
          <p>Grille tarifaire claire : durée des séances, forfaits, remboursements mutuelles.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📅</div>
          <h3>Prise de RDV en ligne</h3>
          <p>Calendrier de disponibilités avec réservation directe.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📍</div>
          <h3>Accès au cabinet</h3>
          <p>Adresse, plan d'accès, stationnement, accessibilité.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">❓</div>
          <h3>Questions fréquentes</h3>
          <p>Réponses aux questions que se posent vos futurs patients.</p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Toutes les <span class="gradient-text">pratiques</span></h2>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass"><span>🧘</span><div><strong>Sophrologue</strong></div></div>
        <div class="advantage-item glass"><span>💆</span><div><strong>Ostéopathe</strong></div></div>
        <div class="advantage-item glass"><span>🌿</span><div><strong>Naturopathe</strong></div></div>
        <div class="advantage-item glass"><span>🧠</span><div><strong>Hypnothérapeute</strong></div></div>
        <div class="advantage-item glass"><span>💪</span><div><strong>Kinésithérapeute</strong></div></div>
        <div class="advantage-item glass"><span>🌸</span><div><strong>Réflexologue</strong></div></div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tarifs site <span class="gradient-text">thérapeute</span></h2>
      </div>
      <div class="features-grid home-pricing">
        <div class="feature-card glass" data-animate>
          <h3>Site Vitrine</h3>
          <p class="home-price"><?= $formules['vitrine']['mensuel'] ?> €<span style="font-size: 1rem; font-weight: 400;">/mois</span></p>
          <p>Présentation, pratiques, tarifs, contact.</p>
        </div>
        <div class="feature-card glass portfolio-featured" data-animate>
          <h3>Site Complet</h3>
          <p class="home-price"><?= $formules['complet']['mensuel'] ?> €<span style="font-size: 1rem; font-weight: 400;">/mois</span></p>
          <p>+ Prise de RDV en ligne, FAQ dynamique.</p>
        </div>
      </div>
      <p class="home-pricing-note" data-animate>Engagement 24 mois, tout inclus. <a href="/tarifs" style="color: var(--primary);">Voir tous les tarifs →</a></p>
    </div>
  </section>

  <!-- Aperçu démo -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Aperçu d'un <span class="gradient-text">site de thérapeute</span></h2>
        <p class="section-subtitle" data-animate>Voici à quoi pourrait ressembler votre futur site.</p>
      </div>

      <div class="demo-preview glass-strong" data-animate style="border-radius: 1rem; overflow: hidden; position: relative;">
        <div style="background: linear-gradient(135deg, #7c9885, #5f8a6d); padding: 0.5rem; text-align: center; font-size: 0.85rem; color: white;">
          <strong>Sophie Martin · Sophrologue</strong> — Exemple de site thérapeute
        </div>
        <div style="background: #faf9f7; padding: 3rem; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center;">
          <div>
            <p style="color: #7c9885; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Sophrologue certifiée</p>
            <h3 style="font-size: 1.75rem; color: #3d3d3d; margin-bottom: 1rem; font-weight: 400; font-family: 'Libre Baskerville', Georgia, serif;">Sophrologue à <span style="color: #7c9885;">Évreux</span></h3>
            <p style="color: #7a7a7a; margin-bottom: 1.5rem;">Gestion du stress, confiance en soi, préparation mentale. Séances individuelles ou en groupe...</p>
            <a href="/demos/therapeute" target="_blank" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg>
              Voir la démo complète
            </a>
          </div>
          <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
              <div style="font-size: 1.5rem;">🌿</div>
              <span style="color: #3d3d3d;">Approche douce et personnalisée</span>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
              <div style="font-size: 1.5rem;">💰</div>
              <span style="color: #3d3d3d;">Tarifs clairs · Dès 45 €</span>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
              <div style="font-size: 1.5rem;">📅</div>
              <span style="color: #3d3d3d;">Prise de RDV en ligne</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Développez votre <span class="gradient-text">clientèle</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit sous 24h. Un site qui inspire confiance.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
