<?php
$pageTitle = 'Création site internet gîte et chambre d\'hôtes | Réservation | Pertec';
$pageDescription = 'Site internet pour gîte, chambre d\'hôtes ou location saisonnière. Photos, tarifs, réservation en ligne. Dès 990 €. Devis gratuit sous 24h.';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];

$canonicalUrl = '/secteurs/gite';
$activeMenu = '';

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('gîtes et chambres d\'hôtes', '/secteurs/gite');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour hébergement touristique
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">gîte et chambre d'hôtes</span></h1>
      <p data-animate>Un site qui donne envie de réserver. Photos immersives, disponibilités en temps réel, réservation directe sans commission.</p>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi un <span class="gradient-text">site internet</span> pour votre hébergement ?</h2>
        <p class="section-subtitle" data-animate>Réduisez les commissions des plateformes et créez une relation directe avec vos clients.</p>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem; font-size: 1.1rem;">
              <strong style="color: white;">Booking et Airbnb prennent des commissions</strong> qui réduisent votre marge. Votre propre site vous permet d'être trouvé directement.
            </p>
            <ul style="color: var(--gray-400); list-style: none;">
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Réservations directes sans commission
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Photos et vidéos qui font rêver
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Calendrier de disponibilités en temps réel
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                SEO local : "gîte + votre région"
              </li>
            </ul>
          </div>
          <div class="zone-map">
            <img src="<?= asset('/images/secteurs/gite.svg') ?>" alt="Illustration site gîte chambre d'hôtes" style="max-width: 280px; height: auto;">
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que contient votre <span class="gradient-text">site d'hébergement</span></h2>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📸</div>
          <h3>Galerie photos immersive</h3>
          <p>Photos grand format de vos chambres, espaces communs, extérieurs et environs.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📅</div>
          <h3>Calendrier disponibilités</h3>
          <p>Vos clients voient en un coup d'œil les dates disponibles.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">💳</div>
          <h3>Réservation en ligne</h3>
          <p>Formulaire de réservation avec paiement d'acompte sécurisé.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">💰</div>
          <h3>Tarifs et saisons</h3>
          <p>Grille tarifaire claire : basse saison, haute saison, week-ends...</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🗺️</div>
          <h3>À découvrir aux alentours</h3>
          <p>Activités, restaurants, sites touristiques pour donner envie de venir.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">⭐</div>
          <h3>Avis des voyageurs</h3>
          <p>Témoignages de vos anciens clients pour rassurer les nouveaux.</p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tous types <span class="gradient-text">d'hébergement</span></h2>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass"><span>🏡</span><div><strong>Gîte rural</strong></div></div>
        <div class="advantage-item glass"><span>🛏️</span><div><strong>Chambre d'hôtes</strong></div></div>
        <div class="advantage-item glass"><span>🏠</span><div><strong>Location saisonnière</strong></div></div>
        <div class="advantage-item glass"><span>🏕️</span><div><strong>Hébergement insolite</strong></div></div>
        <div class="advantage-item glass"><span>🍷</span><div><strong>Domaine viticole</strong></div></div>
        <div class="advantage-item glass"><span>🌾</span><div><strong>Ferme d'accueil</strong></div></div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tarifs site <span class="gradient-text">gîte</span></h2>
      </div>
      <div class="features-grid home-pricing">
        <div class="feature-card glass" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine5']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine5']['creation'], 0, ',', ' ') ?> €</p>
          <p>Accueil, chambres, tarifs, environs, contact.</p>
        </div>
        <div class="feature-card glass portfolio-featured" data-animate>
          <h3>Site + réservation</h3>
          <p class="home-price"><?= number_format($offres['vitrine5']['creation'] + 190, 0, ',', ' ') ?> €</p>
          <p>+ Calendrier et formulaire de réservation en ligne.</p>
        </div>
      </div>
      <p class="home-pricing-note" data-animate>Puis <?= $mensuel ?> €/mois : hébergement, mises à jour, modifications sous 48h. <?= htmlspecialchars($tarifs['config']['tva_mention']) ?>.</p>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Remplissez votre <span class="gradient-text">calendrier</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit sous 24h. Un site qui donne envie de réserver.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
