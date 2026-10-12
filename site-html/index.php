<?php
$pageTitle = 'Création Site Internet | Clé en Main | Pertec.fr';
$pageDescription = 'Création de sites web clé en main, 100% en ligne. Domaine + hébergement + création + rédaction inclus. Pour artisans, commerces, restaurants. Devis gratuit 24h.';
$canonicalUrl = '/';
$activeMenu = '';
$ogData = [
    'title' => 'Création Site Internet Clé en Main | Pertec.fr',
    'description' => 'Création de sites web clé en main, 100% en ligne. Pour artisans, commerces, restaurants.',
    'type' => 'website',
    'url' => '/'
];
$schemaJson = '{
    "@context": "https://schema.org",
    "@type": "ProfessionalService",
    "name": "Pertec - Création de sites internet",
    "description": "Création de sites internet clé en main, 100% en ligne",
    "url": "https://pertec.fr",
    "image": "images/logo-pertec.svg?v=2",
    "areaServed": "FR",
    "serviceType": ["Création site internet", "Développement web", "Site e-commerce", "SEO"]
  }';

require_once __DIR__ . '/includes/header.php';
?>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <div class="hero-badge glass" data-animate>
          <span class="dot"></span>
          Création de sites internet professionnels
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
          <a href="/contact.php" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/services.php" class="btn btn-secondary">Découvrir les services</a>
        </div>

        <div class="hero-trust" data-animate>
          <div class="trust-item">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Site en 2 semaines
          </div>
          <div class="trust-item">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Formule tout inclus
          </div>
          <div class="trust-item">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            Support personnalisé
          </div>
          <div class="trust-item">
            <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            100% en ligne
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

  <!-- Why Choose Me -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi <span class="gradient-text">me choisir</span> ?</h2>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass">
          <span>🌐</span>
          <div><strong>En ligne</strong> · Partout en France</div>
        </div>
        <div class="advantage-item glass">
          <span>⚡</span>
          <div><strong>Rapide</strong> · Site en 2-3 semaines</div>
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
        <a href="/secteurs/artisan.php" class="secteur-img-card" data-animate>
          <img src="/images/artisans/electricien.jpg" alt="Artisan électricien au travail">
          <div class="secteur-img-overlay">
            <h3>Artisans</h3>
            <p>Plombiers, électriciens, maçons, menuisiers...</p>
            <span class="btn-link">Découvrir →</span>
          </div>
        </a>

        <a href="/secteurs/restaurant.php" class="secteur-img-card" data-animate>
          <img src="/images/restaurants/restaurant-1.jpg" alt="Intérieur de restaurant moderne">
          <div class="secteur-img-overlay">
            <h3>Restaurants</h3>
            <p>Bistrots, brasseries, cafés, traiteurs...</p>
            <span class="btn-link">Découvrir →</span>
          </div>
        </a>

        <a href="/services.php" class="secteur-img-card" data-animate>
          <img src="/images/commerces/boutique.jpg" alt="Devanture de boutique">
          <div class="secteur-img-overlay">
            <h3>Commerces</h3>
            <p>Boutiques, fleuristes, magasins...</p>
            <span class="btn-link">Découvrir →</span>
          </div>
        </a>

        <a href="/services.php" class="secteur-img-card" data-animate>
          <img src="/images/professions/bureau.jpg" alt="Bureau professionnel">
          <div class="secteur-img-overlay">
            <h3>Professions libérales</h3>
            <p>Avocats, comptables, kinés...</p>
            <span class="btn-link">Découvrir →</span>
          </div>
        </a>

        <a href="/services.php" class="secteur-img-card" data-animate>
          <img src="/images/beaute/coiffeur.jpg" alt="Salon de coiffure">
          <div class="secteur-img-overlay">
            <h3>Beauté & Bien-être</h3>
            <p>Coiffeurs, esthéticiennes, spas...</p>
            <span class="btn-link">Découvrir →</span>
          </div>
        </a>

        <a href="/services.php" class="secteur-img-card" data-animate>
          <img src="/images/hero/hero-workspace.jpg" alt="Espace de travail moderne">
          <div class="secteur-img-overlay">
            <h3>TPE / PME</h3>
            <p>Toutes entreprises locales</p>
            <span class="btn-link">Découvrir →</span>
          </div>
        </a>
      </div>

      <div style="text-align: center; margin-top: 3rem;" data-animate>
        <a href="/services.php" class="btn btn-secondary">Voir tous les services</a>
      </div>
    </div>
  </section>

  <!-- 100% en ligne -->
  <section>
    <div class="container">
      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <h2 class="section-title">100% <span class="gradient-text">en ligne</span></h2>
            <p style="color: var(--gray-400); font-size: 1.1rem; margin-bottom: 1.5rem;">
              Travaillons ensemble où que vous soyez. Échanges par visio, email ou téléphone — pas besoin de se déplacer.
            </p>

            <div class="villes-tags">
              <span class="ville-tag">Visioconférence</span>
              <span class="ville-tag">Téléphone</span>
              <span class="ville-tag">Email</span>
              <span class="ville-tag">WhatsApp</span>
            </div>

            <p style="color: var(--gray-500); font-size: 0.9rem;">
              Clients partout en France : Paris, Lyon, Marseille, Bordeaux, Lille, Nantes...
            </p>
          </div>

          <div class="zone-map">
            <span class="pin">🌐</span>
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

        <form id="contact-form" action="https://formspree.io/f/your-form-id" method="POST" class="form-grid" style="max-width: 600px; margin: 0 auto;">
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
            <label for="activite">Activité <span>*</span></label>
            <input type="text" id="activite" name="activite" placeholder="Plomberie, Restaurant..." required>
          </div>

          <div class="form-group full">
            <label for="message">Votre projet <span>*</span></label>
            <textarea id="message" name="message" placeholder="Décrivez brièvement votre projet..." required></textarea>
          </div>

          <div class="form-group full checkbox-group">
            <input type="checkbox" id="consent" name="consent" required>
            <label for="consent">J'accepte d'être recontacté concernant ma demande <span>*</span></label>
          </div>

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
