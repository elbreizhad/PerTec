<?php
$pageTitle = 'Création site internet thérapeute et praticien bien-être | Prise de RDV | Pertec';
$pageDescription = 'Site internet pour thérapeute, sophrologue, ostéopathe ou praticien bien-être. Présentation, tarifs, prise de RDV en ligne. Dès 490 €. Devis gratuit 24h.';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];

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
          <h3><?= htmlspecialchars($offres['vitrine3']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine3']['creation'], 0, ',', ' ') ?> €</p>
          <p>Présentation, pratiques, tarifs, contact.</p>
        </div>
        <div class="feature-card glass portfolio-featured" data-animate>
          <h3>Site + prise de RDV</h3>
          <p class="home-price"><?= number_format($offres['vitrine3']['creation'] + 190, 0, ',', ' ') ?> €</p>
          <p>+ Calendrier et réservation en ligne.</p>
        </div>
      </div>
      <p class="home-pricing-note" data-animate>Puis <?= $mensuel ?> €/mois : hébergement, mises à jour, modifications sous 48h. <?= htmlspecialchars($tarifs['config']['tva_mention']) ?>.</p>
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
