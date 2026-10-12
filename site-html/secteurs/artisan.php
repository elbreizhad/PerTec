<?php
$pageTitle = 'Création Site Internet Artisan | Plombier, Électricien | Pertec.fr';
$pageDescription = 'Créez votre site internet d\'artisan. Site clé en main pour plombiers, électriciens, maçons, menuisiers. Devis gratuit, livraison 3-4 semaines.';
$canonicalUrl = '/secteurs/artisan';
$activeMenu = '';

require_once dirname(__DIR__) . '/includes/form.php';
require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('artisans', '/secteurs/artisan');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour artisans
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">pour artisans</span></h1>
      <p data-animate>Plombier, électricien, maçon, menuisier... Obtenez un site professionnel qui vous apporte des clients.</p>
    </div>
  </section>

  <!-- Pourquoi un site -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi un <span class="gradient-text">site internet</span> pour votre activité ?</h2>
        <p class="section-subtitle" data-animate>En 2025, vos futurs clients cherchent d'abord sur Google.</p>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem; font-size: 1.1rem;">
              <strong style="color: white;">87% des Français</strong> recherchent un artisan sur internet avant de le contacter. Sans site web, vous êtes invisible pour la majorité de vos clients potentiels.
            </p>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem;">
              Un site internet professionnel vous permet de :
            </p>
            <ul style="color: var(--gray-400); list-style: none;">
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Être trouvé par les clients de votre zone
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Présenter vos services et réalisations
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Recevoir des demandes de devis 24h/24
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Vous démarquer de la concurrence
              </li>
            </ul>
          </div>
          <div class="zone-map">
            <span class="pin" style="font-size: 5rem;">🔧</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Avantages -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que vous <span class="gradient-text">obtenez</span></h2>
        <p class="section-subtitle" data-animate>Un site complet, optimisé pour attirer des clients dans votre zone.</p>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📍</div>
          <h3>SEO local</h3>
          <p>Votre site optimisé pour apparaître sur "plombier Évreux", "électricien Louviers" et toutes vos recherches cibles.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📸</div>
          <h3>Galerie de chantiers</h3>
          <p>Présentez vos réalisations en photos pour rassurer vos prospects et montrer votre savoir-faire.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📋</div>
          <h3>Formulaire de devis</h3>
          <p>Recevez des demandes de devis détaillées directement dans votre boîte mail, même le week-end.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">⭐</div>
          <h3>Avis clients</h3>
          <p>Affichez vos avis Google et témoignages pour créer la confiance avec vos nouveaux prospects.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📱</div>
          <h3>Click-to-call</h3>
          <p>Bouton d'appel direct sur mobile pour que vos clients vous contactent en un clic.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🗺️</div>
          <h3>Zone d'intervention</h3>
          <p>Carte interactive de votre zone de chalandise avec les villes où vous intervenez.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Métiers -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pour tous les <span class="gradient-text">métiers</span></h2>
        <p class="section-subtitle" data-animate>J'accompagne tous les artisans du bâtiment et des services.</p>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass">
          <span>🔧</span>
          <div><strong><a href="/sites-metiers/plombier" style="color: inherit; text-decoration: none;">Plombiers →</a></strong></div>
        </div>
        <div class="advantage-item glass">
          <span>⚡</span>
          <div><strong><a href="/sites-metiers/electricien" style="color: inherit; text-decoration: none;">Électriciens →</a></strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🧱</span>
          <div><strong>Maçons</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🪵</span>
          <div><strong>Menuisiers</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🎨</span>
          <div><strong>Peintres</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🏠</span>
          <div><strong>Couvreurs</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🔥</span>
          <div><strong>Chauffagistes</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🚿</span>
          <div><strong>Carreleurs</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🌳</span>
          <div><strong>Paysagistes</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🔒</span>
          <div><strong>Serruriers</strong></div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Questions <span class="gradient-text">fréquentes</span></h2>
      </div>

      <div class="faq-list">
        <div class="faq-item glass" data-animate>
          <button class="faq-question">
            <span>Je n'ai pas le temps de m'occuper d'un site, comment ça se passe ?</span>
            <span>+</span>
          </button>
          <div class="faq-answer">
            <p>C'est justement l'avantage du clé en main ! Un simple échange de 30 minutes suffit pour que je comprenne votre activité. Je m'occupe ensuite de tout : textes, photos, mise en ligne. Vous n'avez rien à faire.</p>
          </div>
        </div>

        <div class="faq-item glass" data-animate>
          <button class="faq-question">
            <span>Combien de temps avant de recevoir mes premiers contacts ?</span>
            <span>+</span>
          </button>
          <div class="faq-answer">
            <p>En général, les premiers appels arrivent dans les 2-4 semaines suivant la mise en ligne. Le SEO local prend quelques semaines pour être efficace, mais les résultats sont durables.</p>
          </div>
        </div>

        <div class="faq-item glass" data-animate>
          <button class="faq-question">
            <span>Est-ce que je peux modifier mon site moi-même ?</span>
            <span>+</span>
          </button>
          <div class="faq-answer">
            <p>Oui, je vous forme à la gestion simple de votre site : ajout de photos de chantiers, modification des textes, mise à jour des tarifs. C'est très simple, même sans connaissance technique.</p>
          </div>
        </div>

        <div class="faq-item glass" data-animate>
          <button class="faq-question">
            <span>J'ai déjà une page Facebook, ça ne suffit pas ?</span>
            <span>+</span>
          </button>
          <div class="faq-answer">
            <p>Une page Facebook est un bon complément, mais elle ne remplace pas un site. Sur Google, les gens cherchent "plombier + ville", et seul un site web bien optimisé vous permet d'apparaître dans ces recherches.</p>
          </div>
        </div>

        <div class="faq-item glass" data-animate>
          <button class="faq-question">
            <span>Que se passe-t-il si j'ai un problème avec mon site ?</span>
            <span>+</span>
          </button>
          <div class="faq-answer">
            <p>La maintenance est incluse pendant 1 an : je gère les mises à jour, la sécurité et les sauvegardes. En cas de souci, je suis joignable et réactif. Votre site est entre de bonnes mains.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Formulaire -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Demandez votre <span class="gradient-text">devis gratuit</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Décrivez votre activité, je vous réponds sous 24h avec une proposition adaptée.</p>

        <form id="contact-form" action="/contact-handler.php" method="POST" class="form-grid" style="max-width: 600px; margin: 0 auto;">
          <?= form_hidden_fields() ?>
          <div class="form-group">
            <label for="nom">Nom <span>*</span></label>
            <input type="text" id="nom" name="nom" placeholder="Jean Dupont" required>
          </div>

          <div class="form-group">
            <label for="telephone">Téléphone <span>*</span></label>
            <input type="tel" id="telephone" name="telephone" placeholder="06 12 34 56 78" required>
          </div>

          <div class="form-group">
            <label for="email">Email <span>*</span></label>
            <input type="email" id="email" name="email" placeholder="jean@exemple.fr" required>
          </div>

          <div class="form-group">
            <label for="metier">Votre métier <span>*</span></label>
            <input type="text" id="metier" name="entreprise" placeholder="Plombier, Électricien..." required>
          </div>

          <div class="form-group full">
            <label for="ville">Ville / Zone d'intervention</label>
            <input type="text" id="ville" name="ville" placeholder="Évreux et environs">
          </div>

          <div class="form-group full">
            <label for="message">Parlez-moi de votre activité</label>
            <textarea id="message" name="message" placeholder="Vos services, votre zone, vos besoins..."></textarea>
          </div>

          <div class="form-group full checkbox-group">
            <input type="checkbox" id="consent" name="consent" required>
            <label for="consent">J'accepte d'être recontacté concernant ma demande <span>*</span></label>
          </div>

          <?= form_turnstile() ?>

          <div class="form-group full">
            <button type="submit" class="btn btn-primary form-submit">Recevoir mon devis gratuit</button>
          </div>

          <div class="form-group full">
            <p class="form-trust">
              <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
              Réponse sous 24h - Devis gratuit et sans engagement
            </p>
          </div>
        </form>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
