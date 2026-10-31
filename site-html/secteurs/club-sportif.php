<?php
$pageTitle = 'Création site internet club sportif et association | Inscriptions | Pertec';
$pageDescription = 'Site internet pour club sportif ou association. Inscriptions en ligne, planning, actualités, résultats. Dès 490 €. Devis gratuit sous 24h.';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];

$canonicalUrl = '/secteurs/club-sportif';
$activeMenu = '';

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('clubs sportifs et associations', '/secteurs/club-sportif');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour club sportif
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">club sportif et association</span></h1>
      <p data-animate>Recrutez des adhérents, communiquez avec vos licenciés. Planning, inscriptions, résultats au même endroit.</p>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi un <span class="gradient-text">site internet</span> pour votre club ?</h2>
        <p class="section-subtitle" data-animate>Facilitez les inscriptions et tenez vos membres informés toute l'année.</p>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem; font-size: 1.1rem;">
              <strong style="color: white;">Les parents cherchent une activité</strong> pour leurs enfants et comparent les clubs en ligne. Votre site est votre vitrine permanente.
            </p>
            <ul style="color: var(--gray-400); list-style: none;">
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Présenter les activités et catégories d'âge
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Permettre l'inscription en ligne
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Publier le calendrier des matchs et entraînements
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Être trouvé sur "club [sport] + votre ville"
              </li>
            </ul>
          </div>
          <div class="zone-map">
            <img src="<?= asset('/images/secteurs/club-sportif.svg') ?>" alt="Illustration site club sportif" style="max-width: 280px; height: auto;">
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que contient votre <span class="gradient-text">site de club</span></h2>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📋</div>
          <h3>Présentation du club</h3>
          <p>Histoire, valeurs, équipe dirigeante, installations sportives.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">👥</div>
          <h3>Sections et catégories</h3>
          <p>Toutes les sections avec horaires, tarifs et encadrants.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📝</div>
          <h3>Inscription en ligne</h3>
          <p>Formulaire d'inscription avec pièces à fournir et paiement.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📅</div>
          <h3>Calendrier</h3>
          <p>Planning des entraînements, matchs, tournois et événements.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📰</div>
          <h3>Actualités</h3>
          <p>Résultats, convocations, informations importantes pour les licenciés.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📸</div>
          <h3>Galerie photos</h3>
          <p>Photos des équipes, des matchs, des événements du club.</p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tous les <span class="gradient-text">sports</span></h2>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass"><span>⚽</span><div><strong>Football</strong></div></div>
        <div class="advantage-item glass"><span>🏀</span><div><strong>Basketball</strong></div></div>
        <div class="advantage-item glass"><span>🎾</span><div><strong>Tennis</strong></div></div>
        <div class="advantage-item glass"><span>🥋</span><div><strong>Arts martiaux</strong></div></div>
        <div class="advantage-item glass"><span>🏊</span><div><strong>Natation</strong></div></div>
        <div class="advantage-item glass"><span>🤸</span><div><strong>Gymnastique</strong></div></div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tarifs site <span class="gradient-text">club sportif</span></h2>
      </div>
      <div class="features-grid home-pricing">
        <div class="feature-card glass" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine5']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine5']['creation'], 0, ',', ' ') ?> €</p>
          <p>Présentation, sections, planning, galerie, contact.</p>
        </div>
        <div class="feature-card glass portfolio-featured" data-animate>
          <h3>Site + inscriptions</h3>
          <p class="home-price"><?= number_format($offres['vitrine5']['creation'] + 190, 0, ',', ' ') ?> €</p>
          <p>+ Formulaire d'inscription en ligne avec paiement.</p>
        </div>
      </div>
      <p class="home-pricing-note" data-animate>Puis <?= $mensuel ?> €/mois : hébergement, mises à jour, modifications sous 48h. <?= htmlspecialchars($tarifs['config']['tva_mention']) ?>.</p>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Recrutez plus <span class="gradient-text">d'adhérents</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit sous 24h. Un site qui facilite la vie du club.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
