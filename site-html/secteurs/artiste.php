<?php
$pageTitle = 'Création site internet artiste et musicien | Portfolio | Pertec';
$pageDescription = 'Site internet pour artiste, musicien, groupe ou créateur. Portfolio, dates de concert, écoute en ligne, booking. Dès 490 €. Devis gratuit 24h.';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];

$canonicalUrl = '/secteurs/artiste';
$activeMenu = '';

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('artistes et musiciens', '/secteurs/artiste');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour artiste
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">artiste et musicien</span></h1>
      <p data-animate>Votre univers artistique en ligne. Portfolio, musique, vidéos, dates de concert et contact booking.</p>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi un <span class="gradient-text">site internet</span> pour votre art ?</h2>
        <p class="section-subtitle" data-animate>Les réseaux sociaux ne vous appartiennent pas. Votre site, c'est votre espace.</p>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem; font-size: 1.1rem;">
              <strong style="color: white;">Les programmateurs, labels et médias</strong> consultent votre site pour vous découvrir, écouter votre musique et vous contacter.
            </p>
            <ul style="color: var(--gray-400); list-style: none;">
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Présenter votre univers avec votre identité visuelle
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Centraliser musique, vidéos, photos au même endroit
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Afficher vos prochaines dates de concert
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Faciliter le contact booking et presse
              </li>
            </ul>
          </div>
          <div class="zone-map">
            <img src="<?= asset('/images/secteurs/artiste.svg') ?>" alt="Illustration site artiste musicien" style="max-width: 280px; height: auto;">
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que contient votre <span class="gradient-text">site d'artiste</span></h2>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🎧</div>
          <h3>Lecteur audio intégré</h3>
          <p>Vos morceaux écoutables directement sur le site, liés à Spotify, Deezer, SoundCloud...</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🎬</div>
          <h3>Vidéos</h3>
          <p>Clips, lives, sessions acoustiques intégrés depuis YouTube ou Vimeo.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📅</div>
          <h3>Dates de concert</h3>
          <p>Agenda des prochaines dates avec lieu, heure et liens de billetterie.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📸</div>
          <h3>Galerie photos</h3>
          <p>Photos de scène, portraits, visuels d'album pour la presse.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📰</div>
          <h3>Revue de presse</h3>
          <p>Articles, interviews, chroniques qui parlent de vous.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">✉️</div>
          <h3>Contact booking</h3>
          <p>Formulaire dédié pour les programmateurs, labels et médias.</p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tous les <span class="gradient-text">artistes</span></h2>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass"><span>🎤</span><div><strong>Chanteur / Rappeur</strong></div></div>
        <div class="advantage-item glass"><span>🎸</span><div><strong>Groupe / Duo</strong></div></div>
        <div class="advantage-item glass"><span>🎹</span><div><strong>DJ / Producteur</strong></div></div>
        <div class="advantage-item glass"><span>🎨</span><div><strong>Artiste visuel</strong></div></div>
        <div class="advantage-item glass"><span>📷</span><div><strong>Photographe</strong></div></div>
        <div class="advantage-item glass"><span>🎭</span><div><strong>Comédien / Humoriste</strong></div></div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tarifs site <span class="gradient-text">artiste</span></h2>
      </div>
      <div class="features-grid home-pricing">
        <div class="feature-card glass" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine3']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine3']['creation'], 0, ',', ' ') ?> €</p>
          <p>Bio, musique/vidéos, galerie, contact.</p>
        </div>
        <div class="feature-card glass portfolio-featured" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine5']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine5']['creation'], 0, ',', ' ') ?> €</p>
          <p>+ Agenda concerts, revue de presse, newsletter.</p>
        </div>
      </div>
      <p class="home-pricing-note" data-animate>Puis <?= $mensuel ?> €/mois : hébergement, mises à jour, modifications sous 48h. <?= htmlspecialchars($tarifs['config']['tva_mention']) ?>.</p>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Créez votre <span class="gradient-text">univers</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit sous 24h. Un site à votre image.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
