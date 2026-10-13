<?php
$pageTitle = 'Création de site pour société de sécurité | Pertec';
$pageDescription = 'Création de site internet pour société de sécurité, de gardiennage ou centre de formation. Déjà 4 sites réalisés dans le secteur. Dès 990 €, devis en 24h.';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];
$realisations = [
    ['nom' => 'STS Sécurité', 'url' => 'https://sts-securite.fr', 'image' => 'sts-securite.webp', 'type' => 'Entreprise de sécurité privée', 'texte' => 'Présentation de l\'équipe et des services, demande de devis en ligne.'],
    ['nom' => 'Vigiprestance', 'url' => 'https://vigiprestance.fr', 'image' => 'vigiprestance.webp', 'type' => 'Surveillance et gardiennage', 'texte' => 'Prestations et zone d\'intervention claires, formulaire de contact.'],
    ['nom' => 'Solution Sécurité', 'url' => 'https://solution-securite.fr', 'image' => 'solution-securite.webp', 'type' => 'Gardiennage et protection', 'texte' => 'Gardiennage, surveillance et protection, référencement local.'],
    ['nom' => 'CFSSIS', 'url' => 'https://cfssis.fr', 'image' => 'cfssis.webp', 'type' => 'Centre de formation sécurité', 'texte' => 'Formations en sécurité incendie et sûreté, demandes d\'inscription.'],
];
$canonicalUrl = '/secteurs/securite-privee';
$activeMenu = '';

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('entreprises de sécurité privée', '/secteurs/securite-privee');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour la sécurité privée
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">pour société de sécurité</span></h1>
      <p data-animate>Gardiennage, surveillance, agents de sécurité, centres de formation : j'ai déjà créé 4 sites dans le secteur. Un site qui inspire confiance aux donneurs d'ordre.</p>
    </div>
  </section>

  <!-- Pourquoi un site -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pourquoi un <span class="gradient-text">site internet</span> pour votre activité ?</h2>
        <p class="section-subtitle" data-animate>Vos clients recherchent une entreprise de sécurité fiable et professionnelle.</p>
      </div>

      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem; font-size: 1.1rem;">
              <strong style="color: white;">Les entreprises et particuliers</strong> vérifient la crédibilité d'une société de sécurité avant de la contacter. Un site professionnel rassure et légitime votre activité.
            </p>
            <p style="color: var(--gray-400); margin-bottom: 1.5rem;">
              Un site internet professionnel vous permet de :
            </p>
            <ul style="color: var(--gray-400); list-style: none;">
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Afficher vos autorisations et certifications
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Présenter vos services de sécurité
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Recevoir des demandes de devis qualifiées
              </li>
              <li style="display: flex; align-items: center; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Vous démarquer de la concurrence
              </li>
            </ul>
          </div>
          <div class="zone-map">
            <span class="pin" style="font-size: 5rem;">🛡️</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Fonctionnalités -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que vous <span class="gradient-text">obtenez</span></h2>
        <p class="section-subtitle" data-animate>Un site complet, adapté aux métiers de la sécurité privée.</p>
      </div>

      <div class="features-grid">
        <div class="feature-card glass" data-animate>
          <div class="feature-icon">🏅</div>
          <h3>Autorisations affichées</h3>
          <p>Numéro d'autorisation d'exercice CNAPS, certifications et assurances bien visibles pour rassurer vos prospects.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📋</div>
          <h3>Services détaillés</h3>
          <p>Gardiennage, surveillance, rondes, événementiel, protection rapprochée... Chaque service sur sa page.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📍</div>
          <h3>Zone d'intervention</h3>
          <p>Carte interactive de votre zone de couverture. Apparaissez sur les recherches locales.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📝</div>
          <h3>Demande de devis</h3>
          <p>Formulaire adapté à vos prestations : type de mission, durée, effectifs nécessaires.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">⭐</div>
          <h3>Témoignages clients</h3>
          <p>Affichez les retours de vos clients satisfaits pour renforcer votre crédibilité.</p>
        </div>

        <div class="feature-card glass" data-animate>
          <div class="feature-icon">📱</div>
          <h3>Contact rapide</h3>
          <p>Numéro de téléphone et formulaire accessibles en un clic, 24h/24.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Types de services -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pour tous les <span class="gradient-text">métiers</span> de la sécurité</h2>
        <p class="section-subtitle" data-animate>Quel que soit votre domaine d'activité.</p>
      </div>

      <div class="advantages" data-animate>
        <div class="advantage-item glass">
          <span>🏢</span>
          <div><strong>Gardiennage</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🎪</span>
          <div><strong>Événementiel</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🔒</span>
          <div><strong>Surveillance</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🚗</span>
          <div><strong>Rondes</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🛡️</span>
          <div><strong>Protection</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>📹</span>
          <div><strong>Vidéosurveillance</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🏬</span>
          <div><strong>Commerce</strong></div>
        </div>
        <div class="advantage-item glass">
          <span>🏗️</span>
          <div><strong>Chantiers</strong></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Réalisations -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Déjà <span class="gradient-text">4 sites</span> dans la sécurité</h2>
        <p class="section-subtitle" data-animate>Entreprises d'agents de sécurité, de gardiennage et centre de formation : ces sites sont en ligne.</p>
      </div>
      <div class="article-refs article-refs-2">
<?php foreach ($realisations as $r): ?>
        <a href="<?= $r['url'] ?>" target="_blank" rel="noopener" class="article-ref glass" data-animate>
          <img class="article-ref-img" src="/images/portfolio/<?= $r['image'] ?>" width="640" height="360" loading="lazy" decoding="async" alt="Site internet de <?= htmlspecialchars($r['nom']) ?> (<?= htmlspecialchars(mb_strtolower($r['type'])) ?>) réalisé par Pertec">
          <span class="article-ref-body">
            <span class="article-ref-type"><?= htmlspecialchars($r['type']) ?></span>
            <strong><?= htmlspecialchars($r['nom']) ?></strong>
            <span><?= htmlspecialchars($r['texte']) ?></span>
            <span class="article-ref-link">Voir le site →</span>
          </span>
        </a>
<?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Tarifs -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Combien coûte un site <span class="gradient-text">pour une société de sécurité</span> ?</h2>
        <p class="section-subtitle" data-animate>Une page par prestation, une zone d'intervention claire, une page recrutement : la plupart des sociétés de sécurité partent sur 5 à 10 pages.</p>
      </div>
      <div class="features-grid home-pricing">
        <div class="feature-card glass" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine5']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine5']['creation'], 0, ',', ' ') ?> €</p>
          <p>Accueil, prestations, zone d'intervention, recrutement, contact.</p>
        </div>
        <div class="feature-card glass portfolio-featured" data-animate>
          <h3><?= htmlspecialchars($offres['vitrine10']['label']) ?></h3>
          <p class="home-price"><?= number_format($offres['vitrine10']['creation'], 0, ',', ' ') ?> €</p>
          <p>Une page par prestation (gardiennage, rondes, SSIAP, événementiel…) ou par secteur.</p>
        </div>
      </div>
      <p class="home-pricing-note" data-animate>Puis <?= $mensuel ?> €/mois : hébergement, nom de domaine, mises à jour et modifications sous 48 h, sans engagement. <?= htmlspecialchars($tarifs['config']['tva_mention']) ?>.</p>
      <p style="color: var(--gray-400); text-align: center; max-width: 700px; margin: 2rem auto 0;" data-animate>Avant de vous lancer : <a href="/conseils/site-internet-entreprise-securite-privee" style="color: var(--primary-light);">ce que doit contenir le site d'une entreprise de sécurité</a> (mentions CNAPS, devis, recrutement), et <a href="/conseils/faire-connaitre-entreprise-securite-privee" style="color: var(--primary-light);">comment faire connaître son entreprise de sécurité</a>.</p>
    </div>
  </section>

  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Prêt à renforcer <span class="gradient-text">votre image</span> ?</h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Demandez votre devis gratuit pour un site professionnel qui inspire confiance.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/realisations" class="btn btn-secondary">Voir mes réalisations</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
