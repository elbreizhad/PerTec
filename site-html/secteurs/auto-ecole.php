<?php
$pageTitle = 'Création site internet auto-école | Réservation en ligne | Pertec';
$pageDescription = 'Site internet pour auto-école avec réservation de leçons en ligne. Présentez vos formations, tarifs et taux de réussite. Dès 990 €. Devis gratuit 24h.';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];

$canonicalUrl = '/secteurs/auto-ecole';
$activeMenu = '';

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('auto-écoles', '/secteurs/auto-ecole');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour auto-école
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">auto-école</span></h1>
      <p data-animate>Attirez plus d'élèves avec un site moderne. Réservation en ligne, tarifs clairs, taux de réussite affiché.</p>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi un <span class="gradient-text">site internet</span> pour votre auto-école ?</h2>
        <p class="section-subtitle" data-animate>Les jeunes comparent en ligne avant de s'inscrire. Soyez visible et convaincant.</p>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem; font-size: 1.1rem;">
              <strong style="color: white;">Les futurs conducteurs</strong> recherchent une auto-école sur Google, comparent les avis et les tarifs avant de se déplacer.
            </p>
            <ul style="color: var(--gray-400); list-style: none;">
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Afficher vos tarifs et formules clairement
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Mettre en avant votre taux de réussite
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Permettre la réservation de leçons en ligne
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Sortir en premier sur "auto-école + votre ville"
              </li>
            </ul>
          </div>
          <div class="zone-map">
            <span class="pin" style="font-size: 5rem;">🚗</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que contient votre <span class="gradient-text">site auto-école</span></h2>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">💰</div>
          <h3>Tarifs et formules</h3>
          <p>Forfaits code, conduite, accéléré... Tout est clair, pas de mauvaise surprise.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📊</div>
          <h3>Taux de réussite</h3>
          <p>Affichez vos statistiques pour rassurer les futurs élèves et leurs parents.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📅</div>
          <h3>Réservation en ligne</h3>
          <p>Vos élèves réservent leurs heures de conduite directement sur le site.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">👨‍🏫</div>
          <h3>Équipe de moniteurs</h3>
          <p>Présentez vos moniteurs diplômés pour créer un lien de confiance.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🚙</div>
          <h3>Véhicules</h3>
          <p>Photos de vos voitures, boîte manuelle ou automatique, simulateur...</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">⭐</div>
          <h3>Avis des élèves</h3>
          <p>Témoignages de vos anciens élèves pour convaincre les nouveaux.</p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Toutes les <span class="gradient-text">formations</span></h2>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass"><span>🚗</span><div><strong>Permis B</strong></div></div>
        <div class="advantage-item glass"><span>🏍️</span><div><strong>Permis moto</strong></div></div>
        <div class="advantage-item glass"><span>📝</span><div><strong>Code de la route</strong></div></div>
        <div class="advantage-item glass"><span>⚡</span><div><strong>Stage accéléré</strong></div></div>
        <div class="advantage-item glass"><span>🔄</span><div><strong>Conduite accompagnée</strong></div></div>
        <div class="advantage-item glass"><span>🅰️</span><div><strong>Boîte automatique</strong></div></div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tarifs site <span class="gradient-text">auto-école</span></h2>
      </div>
      <div class="features-grid home-pricing">
        <div class="feature-card glass" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine5']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine5']['creation'], 0, ',', ' ') ?> €</p>
          <p>Accueil, formations, tarifs, équipe, contact.</p>
        </div>
        <div class="feature-card glass portfolio-featured" data-animate>
          <h3>Site + réservation</h3>
          <p class="home-price"><?= number_format($offres['vitrine5']['creation'] + 190, 0, ',', ' ') ?> €</p>
          <p>+ Système de réservation de leçons en ligne.</p>
        </div>
      </div>
      <p class="home-pricing-note" data-animate>Puis <?= $mensuel ?> €/mois : hébergement, mises à jour, modifications sous 48h. <?= htmlspecialchars($tarifs['config']['tva_mention']) ?>.</p>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Attirez plus <span class="gradient-text">d'élèves</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit sous 24h. Site moderne qui convertit.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
