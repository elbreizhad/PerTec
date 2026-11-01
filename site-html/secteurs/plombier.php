<?php
$pageTitle = 'Création Site Internet Plombier | Devis en ligne | Pertec.fr';
$pageDescription = 'Créez votre site internet de plombier. Formulaire de devis, zone d\'intervention, avis clients. Site clé en main pour plombiers, chauffagistes, artisans.';
$canonicalUrl = '/secteurs/plombier';
$activeMenu = '';

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('plombier-chauffagiste', '/secteurs/plombier');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour plombiers
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">pour plombiers</span></h1>
      <p data-animate>Plombier, chauffagiste, dépanneur... Un site qui rassure et génère des demandes de devis.</p>
    </div>
  </section>

  <!-- Pourquoi un site -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Soyez le <span class="gradient-text">premier</span> à être contacté</h2>
        <p class="section-subtitle" data-animate>Quand un tuyau fuit, vos clients cherchent un plombier en ligne. Soyez celui qu'ils trouvent.</p>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem; font-size: 1.1rem;">
              <strong style="color: white;">En cas d'urgence</strong>, les clients recherchent "plombier + ville" sur Google. Un site professionnel vous positionne en première page.
            </p>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem;">
              Un site de plombier efficace, c'est :
            </p>
            <ul style="color: var(--gray-400); list-style: none;">
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Un numéro de téléphone bien visible
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Un formulaire de devis rapide
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Vos services clairement listés
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Votre zone d'intervention
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

  <!-- Fonctionnalités -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tout ce dont vous avez <span class="gradient-text">besoin</span></h2>
        <p class="section-subtitle" data-animate>Des fonctionnalités pensées pour les artisans du bâtiment.</p>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📝</div>
          <h3>Formulaire de devis</h3>
          <p>Vos clients décrivent leur besoin et vous recevez la demande par email. Plus de prospects qualifiés.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📞</div>
          <h3>Appel en un clic</h3>
          <p>Bouton d'appel direct sur mobile. Parfait pour les urgences - vos clients vous contactent instantanément.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🗺️</div>
          <h3>Zone d'intervention</h3>
          <p>Carte interactive de votre secteur. Vos clients savent immédiatement si vous intervenez chez eux.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">⭐</div>
          <h3>Avis clients</h3>
          <p>Intégration de vos avis Google pour rassurer les nouveaux clients et booster votre crédibilité.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🛠️</div>
          <h3>Services détaillés</h3>
          <p>Présentez chaque prestation : plomberie, chauffage, débouchage, rénovation... avec des descriptions claires.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🏆</div>
          <h3>Certifications</h3>
          <p>Mettez en avant vos labels (RGE, Qualibat), votre garantie décennale et vos assurances.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Aperçu démo -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Aperçu d'un <span class="gradient-text">site plombier</span></h2>
        <p class="section-subtitle" data-animate>Voici à quoi pourrait ressembler votre futur site.</p>
      </div>

      <div class="demo-preview glass-strong" data-animate style="border-radius: 1rem; overflow: hidden; position: relative;">
        <div style="background: linear-gradient(135deg, #0ea5e9, #0284c7); padding: 0.5rem; text-align: center; font-size: 0.85rem; color: white;">
          <strong>Durand Plomberie</strong> — Exemple de site artisan
        </div>
        <div style="background: #f8fafc; padding: 3rem; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: center;">
          <div>
            <p style="color: #0ea5e9; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 0.5rem;">Artisan plombier</p>
            <h3 style="font-size: 1.75rem; color: #1e293b; margin-bottom: 1rem; font-weight: 700;">Votre plombier de confiance à <span style="color: #0ea5e9;">Évreux</span></h3>
            <p style="color: #64748b; margin-bottom: 1.5rem;">Dépannage, installation, rénovation. Intervention rapide, devis gratuit, garantie décennale...</p>
            <a href="/demos/plombier" target="_blank" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 0.5rem;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M19 19H5V5h7V3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2v-7h-2v7zM14 3v2h3.59l-9.83 9.83 1.41 1.41L19 6.41V10h2V3h-7z"/></svg>
              Voir la démo complète
            </a>
          </div>
          <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
              <div style="background: #22c55e; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="white"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              </div>
              <span style="color: #1e293b;">Intervention sous 1h</span>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
              <div style="background: #22c55e; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="white"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              </div>
              <span style="color: #1e293b;">Devis gratuit</span>
            </div>
            <div style="display: flex; align-items: center; gap: 1rem; background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
              <div style="background: #22c55e; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="white"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
              </div>
              <span style="color: #1e293b;">Garantie décennale</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Avantages -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Les <span class="gradient-text">avantages</span> concrets</h2>
      </div>

      <div class="testimonials-grid">
        <div class="testimonial-card glass" data-animate>
          <div class="feature-icon" style="margin-bottom: 1rem;">📈</div>
          <h3 style="margin-bottom: 0.5rem;">Plus de visibilité</h3>
          <p style="color: var(--gray-400);">Apparaissez dans les recherches "plombier + ville" et captez les clients qui cherchent un artisan proche.</p>
        </div>

        <div class="testimonial-card glass" data-animate>
          <div class="feature-icon" style="margin-bottom: 1rem;">💰</div>
          <h3 style="margin-bottom: 0.5rem;">Plus de chantiers</h3>
          <p style="color: var(--gray-400);">Recevez des demandes de devis qualifiées directement dans votre boîte mail, 24h/24.</p>
        </div>

        <div class="testimonial-card glass" data-animate>
          <div class="feature-icon" style="margin-bottom: 1rem;">⭐</div>
          <h3 style="margin-bottom: 0.5rem;">Crédibilité renforcée</h3>
          <p style="color: var(--gray-400);">Un site professionnel rassure vos prospects. Vos avis Google intégrés font le reste.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Prêt à remplir <span class="gradient-text">votre agenda</span> ?</h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Demandez votre devis gratuit et obtenez un site qui génère des demandes.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
