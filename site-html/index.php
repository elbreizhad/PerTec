<?php
require_once __DIR__ . '/config/site.php';
require_once __DIR__ . '/includes/form.php';

// Prix lus depuis la grille tarifaire (source unique)
$tarifs = require __DIR__ . '/config/tarifs.php';
$typesSite = $tarifs['type_site']['options'];
$suiviMensuel = $tarifs['suivi']['inclus']['mensuel'];
$pageTitle = 'Création Site Internet | Clé en Main | Pertec.fr';
$pageDescription = 'Création de sites internet à Évreux et en Normandie. Formule clé en main pour artisans, commerces et restaurants. Devis gratuit sous 24h.';
$canonicalUrl = '/';
$activeMenu = '';
$ogData = [
    'title' => 'Création Site Internet Clé en Main | Pertec.fr',
    'description' => 'Création de sites internet à Évreux et en Normandie. Pour artisans, commerces et restaurants.',
    'type' => 'website',
    'url' => '/'
];
$schemaJson = schema_entreprise_json();

require_once __DIR__ . '/includes/header.php';
?>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <div class="hero-badge glass" data-animate>
          <span class="dot"></span>
          Basé à Évreux · Création de sites internet
        </div>

        <h1 data-animate>
          Votre site internet<br>
          <span class="gradient-text">clé en main</span>
        </h1>

        <p data-animate>
          Domaine, hébergement, création et rédaction inclus.
          Concentrez-vous sur votre métier, je m'occupe de tout le reste.
        </p>

        <div class="hero-buttons" data-animate>
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/services" class="btn btn-secondary">Découvrir les services</a>
        </div>

        <div class="hero-trust" data-animate>
          <div class="trust-item">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Site en 3-4 semaines
          </div>
          <div class="trust-item">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            À partir de <?= $typesSite['page_unique']['creation'] ?> € tout inclus
          </div>
          <div class="trust-item">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Support personnalisé
          </div>
          <div class="trust-item">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Évreux &amp; environs
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tout inclus dans <span class="gradient-text">votre site</span></h2>
        <p class="section-subtitle" data-animate>Vous ne vous occupez de rien. Je gère l'intégralité de votre projet web.</p>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🌐</div>
          <h3>Nom de domaine inclus</h3>
          <p>Réservation et configuration de votre domaine .fr, .com ou .net. Votre identité en ligne sécurisée.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🚀</div>
          <h3>Hébergement performant</h3>
          <p>Hébergement rapide et sécurisé avec certificat SSL, sauvegardes automatiques et maintenance incluse.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🎨</div>
          <h3>Design sur-mesure</h3>
          <p>Création d'un site moderne et responsive, parfaitement adapté à votre image et à vos besoins.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">✍️</div>
          <h3>Rédaction SEO incluse</h3>
          <p>Textes optimisés pour Google rédigés par mes soins. Vous ne vous occupez de rien.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Tarifs -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Des prix <span class="gradient-text">affichés</span></h2>
        <p class="section-subtitle" data-animate>Création payée une fois, puis <?= $suiviMensuel ?> €/mois pour l'hébergement, le nom de domaine, les mises à jour et vos modifications.</p>
      </div>

      <div class="features-grid home-pricing">
<?php foreach (['page_unique', 'vitrine5', 'vitrine10'] as $i => $cle): $offre = $typesSite[$cle]; ?>
        <div class="feature-card glass<?= $i === 1 ? ' portfolio-featured' : '' ?>" data-animate>
          <h3><?= htmlspecialchars($offre['label']) ?></h3>
          <p class="home-price"><?= number_format($offre['creation'], 0, ',', ' ') ?> €</p>
          <p><?= htmlspecialchars($offre['description']) ?></p>
        </div>
<?php endforeach; ?>
      </div>

      <p class="home-pricing-note" data-animate><?= htmlspecialchars($tarifs['config']['tva_mention']) ?> · Boutique en ligne et sur-mesure : sur devis</p>

      <div style="text-align: center; margin-top: 2rem;" data-animate>
        <a href="/tarifs" class="btn btn-primary">Estimer mon projet en 1 minute</a>
      </div>
    </div>
  </section>

  <!-- Réalisations -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Sites <span class="gradient-text">en ligne</span></h2>
        <p class="section-subtitle" data-animate>Quelques sites réalisés pour des entreprises de la région. Allez les voir, ils sont en ligne.</p>
      </div>

      <div class="portfolio-grid">
<?php
$realisations = [
    ['nom' => 'CFSSIS', 'image' => 'cfssis.jpg', 'categorie' => 'Formation', 'url' => 'https://cfssis.fr', 'texte' => 'Site vitrine complet pour un centre de formation en sécurité incendie et sûreté.'],
    ['nom' => 'Il Manque 1', 'image' => 'ilmanque1.jpg', 'categorie' => 'E-commerce', 'url' => 'https://ilmanque1.fr', 'texte' => 'Boutique en ligne avec catalogue produits, panier et commande en ligne.'],
    ['nom' => 'Solution Sécurité', 'image' => 'solution-securite.jpg', 'categorie' => 'Sécurité privée', 'url' => 'https://solution-securite.fr', 'texte' => 'Site professionnel pour une entreprise de gardiennage et de surveillance.'],
];
foreach ($realisations as $r):
?>
        <div class="portfolio-card glass" data-animate>
          <div class="portfolio-image" style="background-image: url('/images/portfolio/<?= $r['image'] ?>'); background-size: cover; background-position: top center;">
            <span class="portfolio-category glass"><?= htmlspecialchars($r['categorie']) ?></span>
          </div>
          <div class="portfolio-content">
            <h3><?= htmlspecialchars($r['nom']) ?></h3>
            <p><?= htmlspecialchars($r['texte']) ?></p>
            <a href="<?= $r['url'] ?>" target="_blank" rel="noopener" class="btn btn-secondary portfolio-btn">Voir le site →</a>
          </div>
        </div>
<?php endforeach; ?>
      </div>

      <div style="text-align: center; margin-top: 2rem;" data-animate>
        <a href="/realisations" class="btn btn-secondary">Toutes les réalisations</a>
      </div>
    </div>
  </section>

<?php if (!empty($avisClients)): ?>
  <!-- Avis clients (affichés uniquement s'il existe de vrais avis) -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ils en <span class="gradient-text">parlent</span></h2>
      </div>
      <div class="features-grid">
<?php foreach ($avisClients as $avis): ?>
        <figure class="feature-card glass home-avis" data-animate>
          <div class="home-avis-note" aria-label="Note : <?= (int) $avis['note'] ?> sur 5"><?= str_repeat('★', (int) $avis['note']) ?></div>
          <blockquote><?= htmlspecialchars($avis['texte']) ?></blockquote>
          <figcaption><strong><?= htmlspecialchars($avis['nom']) ?></strong><?= !empty($avis['entreprise']) ? ' · ' . htmlspecialchars($avis['entreprise']) : '' ?></figcaption>
        </figure>
<?php endforeach; ?>
      </div>
      <div style="text-align: center; margin-top: 2rem;" data-animate>
        <a href="<?= GOOGLE_BUSINESS_URL ?>" target="_blank" rel="noopener" class="btn btn-secondary">Voir tous les avis sur Google</a>
      </div>
    </div>
  </section>
<?php endif; ?>

  <!-- Why Choose Me -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi <span class="gradient-text">me choisir</span> ?</h2>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass">
          <span>🌐</span>
          <div><strong>Local</strong> · Basé à Évreux, rendez-vous possible</div>
        </div>
        <div class="advantage-item glass">
          <span>⚡</span>
          <div><strong>Rapide</strong> · Site en 3-4 semaines</div>
        </div>
        <div class="advantage-item glass">
          <span>💎</span>
          <div><strong>Transparent</strong> · Pas de frais cachés</div>
        </div>
        <div class="advantage-item glass">
          <span>🎯</span>
          <div><strong>Clé en main</strong> · Je gère tout</div>
        </div>
        <div class="advantage-item glass">
          <span>🛠️</span>
          <div><strong>Support</strong> · Après la livraison</div>
        </div>
      </div>
    </div>
  </section>

  <!-- Secteurs -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pour <span class="gradient-text">toutes activités</span></h2>
        <p class="section-subtitle" data-animate>Artisan, commerçant, restaurant ou profession libérale : une solution adaptée à votre métier.</p>
      </div>

      <div class="secteurs-img-grid">
        <a href="/secteurs/artisan" class="secteur-img-card" data-animate>
          <img src="/images/artisans/electricien.jpg" alt="Artisan électricien au travail">
          <div class="secteur-img-overlay">
            <h3>Artisans</h3>
            <p>Plombiers, électriciens, maçons, menuisiers...</p>
            <span class="btn-link">Découvrir →</span>
          </div>
        </a>

        <a href="/secteurs/restaurant" class="secteur-img-card" data-animate>
          <img src="/images/restaurants/restaurant-1.jpg" alt="Intérieur de restaurant moderne">
          <div class="secteur-img-overlay">
            <h3>Restaurants</h3>
            <p>Bistrots, brasseries, cafés, traiteurs...</p>
            <span class="btn-link">Découvrir →</span>
          </div>
        </a>

        <a href="/services" class="secteur-img-card" data-animate>
          <img src="/images/commerces/boutique.jpg" alt="Devanture de boutique">
          <div class="secteur-img-overlay">
            <h3>Commerces</h3>
            <p>Boutiques, fleuristes, magasins...</p>
            <span class="btn-link">Découvrir →</span>
          </div>
        </a>

        <a href="/services" class="secteur-img-card" data-animate>
          <img src="/images/professions/bureau.jpg" alt="Bureau professionnel">
          <div class="secteur-img-overlay">
            <h3>Professions libérales</h3>
            <p>Avocats, comptables, kinés...</p>
            <span class="btn-link">Découvrir →</span>
          </div>
        </a>

        <a href="/sites-metiers/coiffeur" class="secteur-img-card" data-animate>
          <img src="/images/beaute/coiffeur.jpg" alt="Salon de coiffure">
          <div class="secteur-img-overlay">
            <h3>Beauté & Bien-être</h3>
            <p>Coiffeurs, esthéticiennes, spas...</p>
            <span class="btn-link">Découvrir →</span>
          </div>
        </a>

        <a href="/services" class="secteur-img-card" data-animate>
          <img src="/images/hero/hero-workspace.jpg" alt="Espace de travail moderne">
          <div class="secteur-img-overlay">
            <h3>TPE / PME</h3>
            <p>Toutes entreprises locales</p>
            <span class="btn-link">Découvrir →</span>
          </div>
        </a>
      </div>

      <div style="text-align: center; margin-top: 3rem;" data-animate>
        <a href="/services" class="btn btn-secondary">Voir tous les services</a>
      </div>
    </div>
  </section>

  <!-- Zone d'intervention -->
  <section>
    <div class="container">
      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <h2 class="section-title">Basé à <span class="gradient-text">Évreux</span></h2>
            <p style="color: var(--gray-400); font-size: 1.1rem; margin-bottom: 1.5rem;">
              J'accompagne les artisans, commerçants et TPE de l'Eure et de Normandie. Échanges en présentiel ou à distance selon vos préférences.
            </p>

            <div class="villes-tags">
              <a href="/creation-site-internet/evreux" class="ville-tag" style="text-decoration: none;">Évreux</a>
              <a href="/creation-site-internet/louviers" class="ville-tag" style="text-decoration: none;">Louviers</a>
              <a href="/creation-site-internet/vernon" class="ville-tag" style="text-decoration: none;">Vernon</a>
              <a href="/creation-site-internet/bernay" class="ville-tag" style="text-decoration: none;">Bernay</a>
              <a href="/creation-site-internet/les-andelys" class="ville-tag" style="text-decoration: none;">Les Andelys</a>
            </div>

            <p style="color: var(--gray-500); font-size: 0.9rem;">
              <a href="/zone-intervention" style="color: inherit; text-decoration: underline;">Voir toutes les villes →</a>
            </p>
          </div>

          <div class="zone-map">
            <span class="pin">📍</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Prêt à <span class="gradient-text">lancer</span> votre site ?</h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Demandez votre devis gratuit sans engagement. Je vous réponds sous 24h.</p>

        <form id="contact-form" action="/contact-handler.php" method="POST" class="form-grid" style="max-width: 600px; margin: 0 auto;">
          <?= form_hidden_fields() ?>

          <div class="form-group">
            <label for="nom">Nom <span>*</span></label>
            <input type="text" id="nom" name="nom" placeholder="Jean Dupont" required>
          </div>

          <div class="form-group">
            <label for="email">Email <span>*</span></label>
            <input type="email" id="email" name="email" placeholder="jean@exemple.fr" required>
          </div>

          <div class="form-group">
            <label for="telephone">Téléphone <span>*</span></label>
            <input type="tel" id="telephone" name="telephone" placeholder="06 12 34 56 78" required>
          </div>

          <div class="form-group">
            <label for="entreprise">Activité <span>*</span></label>
            <input type="text" id="entreprise" name="entreprise" placeholder="Plomberie, Restaurant..." required>
          </div>

          <div class="form-group full">
            <label for="message">Votre projet <span>*</span></label>
            <textarea id="message" name="message" placeholder="Décrivez brièvement votre projet..." required></textarea>
          </div>

          <div class="form-group full checkbox-group">
            <input type="checkbox" id="consent" name="consent" required>
            <label for="consent">J'accepte d'être recontacté concernant ma demande <span>*</span></label>
          </div>

          <?= form_turnstile() ?>

          <div class="form-group full">
            <button type="submit" class="btn btn-primary form-submit">Envoyer ma demande</button>
          </div>

          <div class="form-group full">
            <p class="form-trust">
              <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
              Réponse sous 24h · Devis gratuit et sans engagement
            </p>
          </div>
        </form>
      </div>
    </div>
  </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
