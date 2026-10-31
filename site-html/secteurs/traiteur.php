<?php
$pageTitle = 'Création site internet traiteur | Devis et menus en ligne | Pertec';
$pageDescription = 'Site internet pour traiteur et service traiteur événementiel. Menus, prestations, galerie photos, devis en ligne. Dès 490 €. Devis gratuit 24h.';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];

$canonicalUrl = '/secteurs/traiteur';
$activeMenu = '';

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('traiteurs', '/secteurs/traiteur');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour traiteur
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">traiteur</span></h1>
      <p data-animate>Mettez en appétit vos futurs clients. Menus, photos de réalisations, demande de devis pour mariages et événements.</p>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi un <span class="gradient-text">site internet</span> pour votre activité traiteur ?</h2>
        <p class="section-subtitle" data-animate>Les clients préparent leurs événements en ligne et comparent les traiteurs avant de demander un devis.</p>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem; font-size: 1.1rem;">
              <strong style="color: white;">Mariage, baptême, séminaire...</strong> Les clients recherchent un traiteur sur Google et choisissent celui dont le site leur met l'eau à la bouche.
            </p>
            <ul style="color: var(--gray-400); list-style: none;">
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Présenter vos menus et formules
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Montrer vos réalisations en photos
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Recevoir des demandes de devis détaillées
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Être visible sur "traiteur + votre région"
              </li>
            </ul>
          </div>
          <div class="zone-map">
            <img src="<?= asset('/images/secteurs/traiteur.svg') ?>" alt="Illustration site traiteur événementiel" style="max-width: 280px; height: auto;">
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que contient votre <span class="gradient-text">site traiteur</span></h2>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📜</div>
          <h3>Menus et formules</h3>
          <p>Cocktails, buffets, menus assis, formules par personne ou par événement.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📸</div>
          <h3>Galerie photos</h3>
          <p>Photos de vos plats, buffets, mises en place pour donner envie.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🎉</div>
          <h3>Types d'événements</h3>
          <p>Mariages, anniversaires, baptêmes, séminaires, cocktails d'entreprise...</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📝</div>
          <h3>Demande de devis</h3>
          <p>Formulaire détaillé : date, nombre de convives, type de prestation.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📍</div>
          <h3>Zone d'intervention</h3>
          <p>Départements et distances maximales pour vos prestations.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">⭐</div>
          <h3>Avis clients</h3>
          <p>Témoignages de mariés et organisateurs d'événements.</p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tous types de <span class="gradient-text">prestations</span></h2>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass"><span>💒</span><div><strong>Traiteur mariage</strong></div></div>
        <div class="advantage-item glass"><span>🏢</span><div><strong>Traiteur entreprise</strong></div></div>
        <div class="advantage-item glass"><span>🥂</span><div><strong>Cocktails</strong></div></div>
        <div class="advantage-item glass"><span>🍱</span><div><strong>Plateaux repas</strong></div></div>
        <div class="advantage-item glass"><span>🎂</span><div><strong>Événements privés</strong></div></div>
        <div class="advantage-item glass"><span>🌍</span><div><strong>Cuisine du monde</strong></div></div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tarifs site <span class="gradient-text">traiteur</span></h2>
      </div>
      <div class="features-grid home-pricing">
        <div class="feature-card glass" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine5']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine5']['creation'], 0, ',', ' ') ?> €</p>
          <p>Présentation, menus, galerie, zone d'intervention, contact.</p>
        </div>
        <div class="feature-card glass portfolio-featured" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine10']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine10']['creation'], 0, ',', ' ') ?> €</p>
          <p>+ Pages événements détaillées, témoignages, formulaire devis avancé.</p>
        </div>
      </div>
      <p class="home-pricing-note" data-animate>Puis <?= $mensuel ?> €/mois : hébergement, mises à jour, modifications sous 48h. <?= htmlspecialchars($tarifs['config']['tva_mention']) ?>.</p>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Remplissez votre <span class="gradient-text">carnet de commandes</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit sous 24h. Un site qui met l'eau à la bouche.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
