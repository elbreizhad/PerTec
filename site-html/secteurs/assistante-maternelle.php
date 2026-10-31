<?php
$pageTitle = 'Création site internet assistante maternelle | Présentation et contact | Pertec';
$pageDescription = 'Site internet pour assistante maternelle ou micro-crèche. Présentation, agrément, disponibilités, contact parents. Dès 490 €. Devis gratuit 24h.';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];

$canonicalUrl = '/secteurs/assistante-maternelle';
$activeMenu = '';

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('assistantes maternelles', '/secteurs/assistante-maternelle');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour assistante maternelle
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">assistante maternelle</span></h1>
      <p data-animate>Présentez-vous aux parents en recherche de garde. Votre agrément, vos disponibilités, votre approche pédagogique.</p>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi un <span class="gradient-text">site internet</span> pour votre activité ?</h2>
        <p class="section-subtitle" data-animate>Les parents cherchent une assistante maternelle en ligne et veulent en savoir plus avant de vous contacter.</p>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem; font-size: 1.1rem;">
              <strong style="color: white;">Confier son enfant</strong> est une décision importante. Les parents veulent vous connaître avant le premier contact.
            </p>
            <ul style="color: var(--gray-400); list-style: none;">
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Vous présenter et montrer votre environnement
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Afficher votre agrément et vos disponibilités
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Expliquer votre approche et vos activités
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Être visible sur "assistante maternelle + votre ville"
              </li>
            </ul>
          </div>
          <div class="zone-map">
            <span class="pin" style="font-size: 5rem;">👶</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que contient votre <span class="gradient-text">site d'assistante maternelle</span></h2>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">👩</div>
          <h3>Présentation</h3>
          <p>Votre parcours, vos formations, votre expérience avec les enfants.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📋</div>
          <h3>Agrément</h3>
          <p>Nombre d'enfants autorisés, âges accueillis, dates de renouvellement.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📅</div>
          <h3>Disponibilités</h3>
          <p>Places disponibles actuelles et à venir, horaires d'accueil.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🎨</div>
          <h3>Activités proposées</h3>
          <p>Éveil, jeux, sorties, motricité, apprentissages...</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🏠</div>
          <h3>Lieu d'accueil</h3>
          <p>Photos de l'espace dédié aux enfants, jardin, équipements.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📞</div>
          <h3>Contact</h3>
          <p>Formulaire de contact pour les parents intéressés.</p>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tous les modes de <span class="gradient-text">garde</span></h2>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass"><span>👶</span><div><strong>Assistante maternelle</strong></div></div>
        <div class="advantage-item glass"><span>🏠</span><div><strong>Micro-crèche</strong></div></div>
        <div class="advantage-item glass"><span>👨‍👩‍👧</span><div><strong>MAM</strong></div></div>
        <div class="advantage-item glass"><span>🎒</span><div><strong>Garde périscolaire</strong></div></div>
        <div class="advantage-item glass"><span>🌙</span><div><strong>Garde atypique</strong></div></div>
        <div class="advantage-item glass"><span>♿</span><div><strong>Accueil spécialisé</strong></div></div>
      </div>
    </div>
  </section>

  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Tarifs site <span class="gradient-text">assistante maternelle</span></h2>
      </div>
      <div class="features-grid home-pricing">
        <div class="feature-card glass" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine3']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine3']['creation'], 0, ',', ' ') ?> €</p>
          <p>Présentation, agrément, lieu d'accueil, contact.</p>
        </div>
        <div class="feature-card glass portfolio-featured" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine5']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine5']['creation'], 0, ',', ' ') ?> €</p>
          <p>+ Activités, galerie photos, disponibilités détaillées.</p>
        </div>
      </div>
      <p class="home-pricing-note" data-animate>Puis <?= $mensuel ?> €/mois : hébergement, mises à jour, modifications sous 48h. <?= htmlspecialchars($tarifs['config']['tva_mention']) ?>.</p>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Trouvez des <span class="gradient-text">familles</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit sous 24h. Un site qui rassure les parents.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
