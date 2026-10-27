<?php
require_once dirname(__DIR__) . '/config/site.php';
$pageTitle = 'Création de site internet à Rouen | Devis 24h | Pertec';
$pageDescription = 'Création de site professionnel à Rouen pour artisans et PME : site vitrine, e-commerce, référencement local. Dès 490 €, devis gratuit en 24h.';
$canonicalUrl = '/creation-site-internet/rouen';
$activeMenu = '';

$ogData = [
    'title' => 'Création Site Internet Rouen | Pertec.fr',
    'description' => 'Votre site internet clé en main à Rouen. Artisans, commerces, restaurants : obtenez un site pro qui vous apporte des clients.',
    'type' => 'website',
    'url' => '/creation-site-internet/rouen'
];

$schemaJson = schema_service_ville('Rouen', 'rouen');

require_once dirname(__DIR__) . '/includes/header.php';
?>

  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <div class="hero-badge glass" data-animate>
          <span class="dot"></span>
          Webmaster Normandie
        </div>

        <h1 data-animate>
          Création de site internet<br>
          <span class="gradient-text">à Rouen</span>
        </h1>

        <p data-animate>
          Capitale de la Normandie, Rouen compte plus de 110 000 habitants.
          Démarquez-vous de la concurrence avec un site internet professionnel
          qui vous positionne en tête des recherches locales.
        </p>

        <div class="hero-buttons" data-animate>
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>

        <div class="hero-trust" data-animate>
          <div class="trust-item">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Intervention sur Rouen
          </div>
          <div class="trust-item">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Site livré en 3-4 semaines
          </div>
          <div class="trust-item">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Devis gratuit 24h
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi créer un site internet <span class="gradient-text">à Rouen</span> ?</h2>
        <p class="section-subtitle" data-animate>Rouen est un bassin économique majeur en Normandie. La concurrence y est forte : démarquez-vous !</p>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🏛️</div>
          <h3>Marché dynamique</h3>
          <p>Rouen et sa métropole comptent près de 500 000 habitants. Un immense bassin de clients potentiels pour votre activité.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🎯</div>
          <h3>Référencement local</h3>
          <p>Positionnez-vous sur les recherches "votre métier + Rouen". Captez les clients qui cherchent un professionnel près de chez eux.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">⚡</div>
          <h3>Concurrence féroce</h3>
          <p>À Rouen, la concurrence est rude. Un site professionnel vous différencie des concurrents avec un site amateur ou sans site.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📈</div>
          <h3>Croissance garantie</h3>
          <p>Un site bien référencé sur Rouen génère des demandes de devis en continu. Investissement rentabilisé rapidement.</p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <h2 class="section-title">Quartiers de <span class="gradient-text">Rouen</span> couverts</h2>
            <p style="color: var(--gray-400); font-size: 1.1rem; margin-bottom: 1.5rem;">
              J'accompagne les professionnels de tous les quartiers de Rouen et de la métropole.
            </p>

            <div class="villes-tags">
              <span class="ville-tag">Centre historique</span>
              <span class="ville-tag">Rive gauche</span>
              <span class="ville-tag">Saint-Sever</span>
              <span class="ville-tag">Sotteville</span>
              <span class="ville-tag">Mont-Saint-Aignan</span>
              <span class="ville-tag">Bois-Guillaume</span>
              <span class="ville-tag">Darnétal</span>
              <span class="ville-tag">Petit-Quevilly</span>
            </div>
          </div>

          <div class="zone-map">
            <span class="pin">📍</span>
          </div>
        </div>
      </div>
    </div>
  </section>

<?php $villeSlug = 'rouen'; require dirname(__DIR__) . '/includes/ville-details.php'; ?>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Prêt à lancer votre site <span class="gradient-text">à Rouen</span> ?</h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Demandez votre devis gratuit. Réponse sous 24h, sans engagement.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Autres villes <span class="gradient-text">en Normandie</span></h2>
      </div>
      <div class="villes-tags" style="justify-content: center; max-width: 800px; margin: 0 auto;" data-animate>
        <a href="/creation-site-internet/evreux" class="ville-tag" style="text-decoration: none;">Evreux</a>
        <a href="/creation-site-internet/le-havre" class="ville-tag" style="text-decoration: none;">Le Havre</a>
        <a href="/creation-site-internet/louviers" class="ville-tag" style="text-decoration: none;">Louviers</a>
        <a href="/creation-site-internet/vernon" class="ville-tag" style="text-decoration: none;">Vernon</a>
        <a href="/creation-site-internet/val-de-reuil" class="ville-tag" style="text-decoration: none;">Val-de-Reuil</a>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
