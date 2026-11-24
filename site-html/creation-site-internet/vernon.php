<?php
require_once dirname(__DIR__) . '/config/site.php';
$pageTitle = 'Création site internet Vernon (27) | Dès 59 €/mois | Pertec';
$pageDescription = 'Création de site internet à Vernon et Giverny : vitrine, e-commerce, catalogue ou institutionnel, référencement local inclus. Dès 59 €/mois, devis en 24 h.';
$canonicalUrl = '/creation-site-internet/vernon';
$activeMenu = '';

$ogData = [
    'title' => 'Création de site internet à Vernon | Pertec',
    'description' => 'Site vitrine, e-commerce ou catalogue pour les entreprises de Vernon et Giverny, référencement local inclus. Dès 59 €/mois.',
    'type' => 'website',
    'url' => '/creation-site-internet/vernon'
];

$schemaJson = schema_service_ville('Vernon', 'vernon');

require_once dirname(__DIR__) . '/includes/header.php';
?>

  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <div class="hero-badge glass" data-animate><span class="dot"></span>Webmaster Eure</div>
        <h1 data-animate>Création de site internet<br><span class="gradient-text">à Vernon</span></h1>
        <p data-animate>
          Vernon, porte de Giverny et ville dynamique de l'Eure. Attirez les clients locaux et les touristes
          avec un site internet professionnel optimisé pour le référencement local.
        </p>
        <div class="hero-buttons" data-animate>
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
        <div class="hero-trust" data-animate>
          <div class="trust-item"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Proche Giverny</div>
          <div class="trust-item"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Site en 3-4 semaines</div>
          <div class="trust-item"><svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>Devis gratuit</div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi un site internet <span class="gradient-text">à Vernon</span> ?</h2>
        <p class="section-subtitle" data-animate>Vernon bénéficie du tourisme de Giverny : plus de 700 000 visiteurs par an à proximité.</p>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🎨</div>
          <h3>Tourisme Giverny</h3>
          <p>Captez les touristes qui visitent Giverny. Restaurants, hôtels, commerces : soyez visibles sur leurs recherches.</p>
        </div>
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🚄</div>
          <h3>Gare TGV</h3>
          <p>Vernon est connecté à Paris en 45 minutes. Attirez aussi une clientèle parisienne qui s'installe ou investit.</p>
        </div>
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🏘️</div>
          <h3>Bassin local</h3>
          <p>25 000 habitants à Vernon + les communes voisines. Un marché local à conquérir avec un bon référencement.</p>
        </div>
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📱</div>
          <h3>Mobile first</h3>
          <p>Les touristes cherchent sur mobile. Votre site sera parfaitement adapté aux smartphones et tablettes.</p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <h2 class="section-title">Zone de <span class="gradient-text">Vernon</span></h2>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem;">Vernon et les communes du secteur Seine Normandie Agglomération.</p>
            <div class="villes-tags">
              <span class="ville-tag">Vernon</span>
              <span class="ville-tag">Giverny</span>
              <span class="ville-tag">Pacy-sur-Eure</span>
              <span class="ville-tag">Gaillon</span>
              <span class="ville-tag">Les Andelys</span>
              <span class="ville-tag">Saint-Marcel</span>
              <span class="ville-tag">Tilly</span>
            </div>
          </div>
          <div class="zone-map"><span class="pin">📍</span></div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Être trouvé sur Google <span class="gradient-text">à Vernon</span></h2>
        <p class="section-subtitle" data-animate>Vos clients cherchent « votre métier + Vernon ». Votre site doit être la meilleure réponse.</p>
      </div>
      <div class="features-grid features-grid-4">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📍</div>
          <h3>Une page par service</h3>
          <p>« Plombier Vernon », « fleuriste Vernon », « restaurant Giverny » : chaque prestation importante a sa page, avec un vrai texte. C'est ce qui permet de ressortir sur plusieurs recherches à la fois, pas seulement sur le nom de l'entreprise.</p>
        </div>
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🗺️</div>
          <h3>Votre secteur, pas seulement la ville</h3>
          <p>Centre-ville, Vernonnet, Saint-Marcel, Pacy-sur-Eure, Gaillon, Bonnières : on cite les communes où vous travaillez vraiment, sans fausses adresses ni pages copiées-collées, que Google repère et ignore.</p>
        </div>
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">⭐</div>
          <h3>Fiche Google et avis</h3>
          <p>La carte qui s'affiche au-dessus des résultats dépend surtout de votre fiche Google Business Profile et de vos avis. Je relie la fiche au site et vous explique comment obtenir des avis régulièrement.</p>
        </div>
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🇬🇧</div>
          <h3>Les visiteurs de Giverny</h3>
          <p>Hébergements, restaurants, artisans d'art et commerces touristiques reçoivent beaucoup d'étrangers. Une page en anglais (horaires, accès depuis la gare de Vernon-Giverny, réservation) leur évite de passer par une plateforme.</p>
        </div>
      </div>
      <div class="zone-card glass-strong" style="margin-top: 2rem;" data-animate>
        <div>
          <h3 style="margin-bottom: 0.75rem;">Les recherches qui comptent à Vernon</h3>
          <p style="color: var(--gray-400); margin-bottom: 1rem;">À Vernon, on cherche un professionnel de la même façon qu'ailleurs, mais avec moins de concurrence qu'à Rouen ou à Paris. Un site bien construit peut donc se faire une place plus vite, à condition de répondre précisément à ce que tapent vos clients :</p>
          <div class="villes-tags">
            <span class="ville-tag">création site internet Vernon</span>
            <span class="ville-tag">site vitrine Vernon</span>
            <span class="ville-tag">site e-commerce Vernon</span>
            <span class="ville-tag">référencement Vernon</span>
            <span class="ville-tag">artisan Vernon</span>
            <span class="ville-tag">restaurant Giverny</span>
          </div>
          <p style="color: var(--gray-400); margin-top: 1rem;">Pour aller plus loin : <a href="/conseils/referencement-local-eure-normandie" style="color: var(--primary-light);">le référencement local expliqué simplement</a>, <a href="/conseils/site-vitrine-catalogue-ecommerce" style="color: var(--primary-light);">site vitrine, catalogue ou e-commerce : que choisir ?</a> et <a href="/conseils/prix-site-internet-artisan" style="color: var(--primary-light);">combien coûte un site internet</a>.</p>
        </div>
      </div>
    </div>
  </section>
<?php $villeSlug = 'vernon'; require dirname(__DIR__) . '/includes/ville-details.php'; ?>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Lancez votre site <span class="gradient-text">à Vernon</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit sous 24h, sans engagement.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis</a>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header"><h2 class="section-title" data-animate>Autres <span class="gradient-text">villes</span></h2></div>
      <div class="villes-tags" style="justify-content: center; max-width: 800px; margin: 0 auto;" data-animate>
        <a href="/creation-site-internet/evreux" class="ville-tag" style="text-decoration: none;">Evreux</a>
        <a href="/creation-site-internet/les-andelys" class="ville-tag" style="text-decoration: none;">Les Andelys</a>
        <a href="/creation-site-internet/gisors" class="ville-tag" style="text-decoration: none;">Gisors</a>
        <a href="/creation-site-internet/rouen" class="ville-tag" style="text-decoration: none;">Rouen</a>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
