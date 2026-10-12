<?php
$pageTitle = 'Site Internet Commerce | Boutique, Fleuriste, Épicerie | Pertec.fr';
$pageDescription = 'Site internet pour commerces de proximité à Évreux et dans l\'Eure : boutiques, fleuristes, épiceries, cavistes. Horaires, produits, click & collect. À partir de 490 €.';
$canonicalUrl = '/secteurs/commerces';
$activeMenu = '';

$atouts = [
    ['🕐', 'Horaires toujours à jour', 'Horaires d\'ouverture, fermetures exceptionnelles, congés : vos clients voient l\'information juste avant de se déplacer.'],
    ['🛍️', 'Vos produits en vitrine', 'Une sélection de produits ou de nouveautés avec photos, pour donner envie de passer en boutique.'],
    ['📦', 'Click & collect', 'Commande ou réservation en ligne, retrait en magasin. Possible dès le départ ou plus tard, quand vous êtes prêt.'],
    ['📍', 'Plan et accès', 'Adresse, plan, stationnement à proximité : on vous trouve facilement, même quand on ne connaît pas le quartier.'],
    ['🔍', 'Visible sur Google', 'Pages optimisées pour « fleuriste Évreux » ou « épicerie fine Vernon », reliées à votre fiche Google.'],
    ['📱', 'Pensé pour le téléphone', 'La plupart des recherches de commerces se font sur mobile, souvent juste avant de sortir. Le site est conçu pour ça.'],
];

$commerces = [
    ['💐', 'Fleuristes', 'Compositions, deuil, mariages, commande'],
    ['🧀', 'Épiceries fines & cavistes', 'Produits, dégustations, coffrets'],
    ['👗', 'Boutiques de mode', 'Collections, nouveautés, marques'],
    ['🥖', 'Boulangeries & pâtisseries', 'Gammes, commandes de gâteaux'],
    ['🎁', 'Cadeaux & déco', 'Sélection, idées cadeaux, événements'],
    ['🚲', 'Commerces spécialisés', 'Vélo, bricolage, informatique…'],
];

$faq = [
    ['J\'ai déjà une page Facebook et une fiche Google. Un site sert à quoi ?', 'La fiche Google et Facebook sont utiles, mais ils ne vous appartiennent pas et affichent aussi vos concurrents. Le site est l\'endroit où tout est à jour et à votre nom, et il renforce votre fiche Google : les deux se complètent.'],
    ['Puis-je vendre en ligne ?', 'Oui, du simple click & collect (réservation, paiement en boutique) jusqu\'à la boutique en ligne complète. On peut démarrer avec un site vitrine et ajouter la vente plus tard. La boutique en ligne est sur devis.'],
    ['Qui met à jour les horaires et les produits ?', 'Vous m\'envoyez un message, je fais la modification sous 48 h : c\'est inclus dans le suivi à 39 €/mois. Si vous préférez, je peux aussi vous montrer comment modifier certaines parties vous-même.'],
    ['Combien ça coûte ?', 'À partir de 490 € pour une page unique complète, 990 € pour un site de 5 pages, puis 39 €/mois tout compris. Le configurateur de tarifs vous donne une estimation en une minute.'],
];

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('commerces', '/secteurs/commerces');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour commerces
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">pour commerces</span></h1>
      <p data-animate>Boutique, fleuriste, épicerie, caviste… Un site qui fait venir les clients en magasin.</p>
    </div>
  </section>

  <!-- Pourquoi un site -->
  <section>
    <div class="container">
      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <h2 class="section-title" style="font-size: 1.75rem;">Votre vitrine, <span class="gradient-text">ouverte 24h/24</span></h2>
            <p style="color: var(--gray-400); margin-bottom: 1rem; font-size: 1.05rem;">
              Avant de se déplacer, on vérifie : est-ce ouvert ? Est-ce qu'ils ont ce que je cherche ? Où se garer ? Si la réponse n'est pas en ligne, le client va chez celui qui l'affiche.
            </p>
            <p style="color: var(--gray-400);">
              Un site de commerce répond à ces questions en quelques secondes, montre vos produits et donne envie de pousser la porte.
            </p>
          </div>
          <div class="zone-map" style="background: url('/images/commerces/fleuriste.webp') center/cover; border-radius: 16px;"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Ce que contient le site -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que contient <span class="gradient-text">votre site</span></h2>
        <p class="section-subtitle" data-animate>L'essentiel pour un commerce de proximité.</p>
      </div>

      <div class="features-grid">
<?php foreach ($atouts as [$icone, $titre, $texte]): ?>
        <div class="feature-card glass" data-animate>
          <div class="feature-icon"><?= $icone ?></div>
          <h3><?= $titre ?></h3>
          <p><?= $texte ?></p>
        </div>
<?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Types de commerces -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pour tous les <span class="gradient-text">commerces</span></h2>
      </div>

      <div class="secteurs-grid" data-animate>
<?php foreach ($commerces as [$icone, $titre, $texte]): ?>
        <div class="secteur-card glass">
          <div class="secteur-icon"><?= $icone ?></div>
          <div>
            <h3><?= $titre ?></h3>
            <p><?= $texte ?></p>
          </div>
        </div>
<?php endforeach; ?>
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
<?php foreach ($faq as [$question, $reponse]): ?>
        <div class="faq-item glass" data-animate>
          <button class="faq-question">
            <span><?= $question ?></span>
            <span>+</span>
          </button>
          <div class="faq-answer">
            <p><?= $reponse ?></p>
          </div>
        </div>
<?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Faites entrer <span class="gradient-text">plus de clients</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit sous 24h, sans engagement. Je peux passer vous voir en boutique dans l'Eure.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/tarifs" class="btn btn-secondary">Estimer le prix</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
