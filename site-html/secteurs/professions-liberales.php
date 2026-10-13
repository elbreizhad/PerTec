<?php
$pageTitle = 'Site Internet Profession Libérale | Avocat, Kiné, Comptable | Pertec.fr';
$pageDescription = 'Site internet pour professions libérales à Évreux et dans l\'Eure : avocats, kinés, comptables, thérapeutes. Prise de rendez-vous en ligne, à partir de 490 €.';
$canonicalUrl = '/secteurs/professions-liberales';
$activeMenu = '';

$atouts = [
    ['📅', 'Rendez-vous en ligne', 'Vos patients ou clients réservent un créneau sans vous appeler. Intégration de votre outil existant (Doctolib, agenda en ligne) ou formulaire de demande simple.'],
    ['🧭', 'Infos pratiques claires', 'Adresse, accès, stationnement, accessibilité, horaires, tarifs ou honoraires : tout ce qu\'on vous demande au téléphone, au même endroit.'],
    ['🎓', 'Présentation du praticien', 'Votre parcours, vos spécialités, votre façon de travailler. Ce qui permet à quelqu\'un de choisir le bon professionnel avant même le premier rendez-vous.'],
    ['⚖️', 'Contenu informatif et sobre', 'Un ton professionnel, sans promesse ni discours commercial, en cohérence avec les règles de communication de votre ordre ou de votre profession.'],
    ['🔍', 'Visible sur « métier + ville »', 'Pages optimisées pour « kiné Évreux », « avocat Louviers » ou « expert-comptable Vernon », là où vos futurs clients cherchent.'],
    ['🔒', 'Formulaire de contact sécurisé', 'Demandes protégées contre le spam, envoyées directement dans votre boîte mail. Mentions légales et politique de confidentialité incluses.'],
];

$metiers = [
    ['⚖️', 'Avocats & notaires', 'Domaines d\'intervention, premier rendez-vous'],
    ['🩺', 'Kinés & ostéopathes', 'Soins proposés, prise de rendez-vous'],
    ['🧠', 'Psychologues & thérapeutes', 'Approche, déroulé des séances'],
    ['📊', 'Experts-comptables', 'Missions, accompagnement des créateurs'],
    ['🦷', 'Cabinets de santé', 'Équipe, informations pratiques'],
    ['🏛️', 'Architectes & géomètres', 'Projets, références, contact'],
];

$faq = [
    ['Ma profession encadre la communication. Est-ce compatible ?', 'Oui. Un site de profession libérale reste informatif : présentation, domaines d\'intervention, informations pratiques. J\'écris des textes sobres, sans superlatifs ni promesses de résultat, et vous validez chaque page avant la mise en ligne. Pour les règles propres à votre ordre, c\'est vous qui avez le dernier mot.'],
    ['J\'utilise déjà Doctolib ou un agenda en ligne. Faut-il changer ?', 'Non. Le site renvoie vers votre outil de réservation actuel (bouton ou intégration). Le site vous donne en plus une présence à votre nom sur Google, que vous maîtrisez.'],
    ['Combien coûte un site pour un cabinet ?', 'Une page unique complète commence à 490 €, un site de 5 pages à 990 €. Ensuite 39 €/mois pour l\'hébergement, le nom de domaine, les mises à jour et vos modifications. Le configurateur de tarifs vous donne une estimation en une minute.'],
    ['Je n\'ai pas de temps à y consacrer.', 'Un échange de 30 minutes suffit pour que je comprenne votre activité. Je rédige les textes, vous relisez, je mets en ligne. Les modifications ensuite se font par simple e-mail.'],
];

require_once dirname(__DIR__) . '/config/site.php';
$schemaJson = schema_service_metier('professions libérales', '/secteurs/professions-liberales');
require_once dirname(__DIR__) . '/includes/header.php';
?>

  <!-- Page Header -->
  <section class="page-header">
    <div class="container">
      <div class="hero-badge glass" data-animate>
        <span class="dot"></span>
        Site pour professions libérales
      </div>
      <h1 data-animate>Création de site internet <span class="gradient-text">pour professions libérales</span></h1>
      <p data-animate>Avocat, kiné, comptable, thérapeute… Un site sobre et clair qui répond aux questions avant le premier rendez-vous.</p>
    </div>
  </section>

  <!-- Pourquoi un site -->
  <section>
    <div class="container">
      <div class="zone-card glass-strong" data-animate>
        <div class="zone-content">
          <div>
            <h2 class="section-title" style="font-size: 1.75rem;">On vous choisit <span class="gradient-text">avant de vous appeler</span></h2>
            <p style="color: var(--gray-400); margin-bottom: 1rem; font-size: 1.05rem;">
              Quand on cherche un professionnel, on compare : spécialités, adresse, disponibilités, façon de travailler. Sans site, vous n'existez qu'à travers une fiche d'annuaire, comme tous vos confrères.
            </p>
            <p style="color: var(--gray-400);">
              Un site à votre nom présente votre cabinet tel que vous le souhaitez, répond aux questions répétitives et oriente directement vers la prise de rendez-vous.
            </p>
          </div>
          <div class="zone-map" style="background: url('/images/professions/bureau.webp') center/cover; border-radius: 16px;"></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Ce que contient le site -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Ce que contient <span class="gradient-text">votre site</span></h2>
        <p class="section-subtitle" data-animate>Pensé pour un cabinet, pas pour une boutique.</p>
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

  <!-- Métiers -->
  <section>
    <div class="container">
      <div class="section-header">
        <h2 class="section-title" data-animate>Pour les <span class="gradient-text">cabinets</span> de l'Eure</h2>
      </div>

      <div class="secteurs-grid" data-animate>
<?php foreach ($metiers as [$icone, $titre, $texte]): ?>
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

  <section>
    <div class="container" style="text-align: center;">
      <p style="color: var(--gray-400); max-width: 700px; margin: 0 auto;" data-animate>Besoin d'un outil pour organiser votre équipe ? Découvrez <a href="/conseils/planning-cabinet-infirmier" style="color: var(--primary-light);">l'outil de planning créé pour un cabinet infirmier</a>.</p>
    </div>
  </section>

  <!-- CTA -->
  <section class="cta-section">
    <div class="container">
      <div class="cta-box glass-strong" data-animate>
        <h2 class="section-title">Parlons de <span class="gradient-text">votre cabinet</span></h2>
        <p class="section-subtitle" style="margin: 0 auto 2rem;">Devis gratuit sous 24h, sans engagement. Rendez-vous possible à Évreux ou en visio.</p>
        <div class="hero-buttons" style="justify-content: center;">
          <a href="/contact" class="btn btn-primary">Demander un devis gratuit</a>
          <a href="/tarifs" class="btn btn-secondary">Estimer le prix</a>
        </div>
      </div>
    </div>
  </section>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
