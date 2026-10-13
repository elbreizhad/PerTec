<?php
require_once dirname(__DIR__) . '/config/site.php';

$slug = 'site-internet-entreprise-securite-privee';
$articles = require dirname(__DIR__) . '/includes/articles.php';
$article = $articles[$slug];

// Prix lus depuis la grille tarifaire (source unique)
$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];
$devisGuide = $tarifs['options']['options']['devis_guide']['creation'];
$euros = fn(int $n) => number_format($n, 0, ',', ' ') . ' €';

$realisations = [
    ['nom' => 'STS Sécurité', 'url' => 'https://sts-securite.fr', 'image' => 'sts-securite.webp', 'type' => 'Entreprise de sécurité privée', 'texte' => 'Site vitrine avec présentation de l\'équipe et des services, et demande de devis en ligne.'],
    ['nom' => 'Vigiprestance', 'url' => 'https://vigiprestance.fr', 'image' => 'vigiprestance.webp', 'type' => 'Surveillance et gardiennage', 'texte' => 'Présentation claire des prestations et de la zone d\'intervention, formulaire de contact.'],
    ['nom' => 'Solution Sécurité', 'url' => 'https://solution-securite.fr', 'image' => 'solution-securite.webp', 'type' => 'Gardiennage et protection', 'texte' => 'Services de gardiennage, surveillance et protection, travail sur le référencement local.'],
    ['nom' => 'CFSSIS', 'url' => 'https://cfssis.fr', 'image' => 'cfssis.webp', 'type' => 'Centre de formation sécurité incendie et sûreté', 'texte' => 'Site multi-pages pour présenter les formations et recevoir les demandes d\'inscription.'],
];

$pageTitle = 'Site internet pour entreprise de sécurité privée et centre de formation | Pertec';
$pageDescription = $article['description'];
$canonicalUrl = '/conseils/' . $slug;
$activeMenu = 'conseils';
$ogData = ['type' => 'article', 'title' => $article['titre']];

$schemaJson = json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $article['titre'],
    'description' => $article['description'],
    'datePublished' => $article['date'],
    'dateModified' => $article['date'],
    'inLanguage' => 'fr-FR',
    'mainEntityOfPage' => 'https://pertec.fr' . $canonicalUrl,
    'image' => 'https://pertec.fr/images/og-pertec.jpg',
    'author' => ['@type' => 'Person', 'name' => 'Fabien Perrin', 'url' => 'https://pertec.fr/a-propos'],
    'publisher' => ['@id' => 'https://pertec.fr/#entreprise', '@type' => 'ProfessionalService', 'name' => 'Pertec'],
    'about' => ['Sécurité privée', 'Création de site internet', 'Organisme de formation'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

require_once dirname(__DIR__) . '/includes/header.php';
?>

  <article class="article">
    <header class="page-header article-header">
      <div class="container">
        <nav class="article-breadcrumb" aria-label="Fil d'Ariane"><a href="/">Accueil</a> › <a href="/conseils/">Conseils</a> › <span><?= htmlspecialchars($article['categorie']) ?></span></nav>
        <h1 data-animate>Site internet pour une entreprise <span class="gradient-text">de sécurité privée</span> : ce qui rassure vos clients</h1>
        <p class="article-meta" data-animate>Par <a href="/a-propos">Fabien Perrin</a>, créateur de sites à Évreux · <time datetime="<?= $article['date'] ?>">13 octobre 2026</time> · 8 min de lecture</p>
      </div>
    </header>

    <div class="container">
      <div class="article-prose">

        <p>J'ai créé les sites de plusieurs structures de la sécurité privée : trois entreprises de gardiennage et de surveillance, <strong>STS Sécurité</strong>, <strong>Vigiprestance</strong> et <strong>Solution Sécurité</strong>, et un centre de formation en sécurité incendie et sûreté, <strong>CFSSIS</strong>. Ce secteur a ses propres règles, ses propres clients et ses propres questions. Voici ce que j'en ai retenu, et ce qu'un site doit faire pour une société de sécurité ou un organisme de formation.</p>

        <div class="article-answer glass">
          <p class="article-answer-label">L'essentiel</p>
          <p>Un donneur d'ordre ne choisit pas une société de sécurité comme on choisit un fleuriste. Avant d'appeler, il vérifie que l'entreprise est <strong>autorisée, sérieuse et capable de couvrir sa zone</strong>. Votre site doit donner ces preuves en quelques secondes : autorisation CNAPS, prestations détaillées, zone d'intervention, références et un formulaire de devis qui pose les bonnes questions.</p>
        </div>

        <h2>Pourquoi la sécurité privée est un secteur à part</h2>
        <p>La plupart de vos clients sont des professionnels : syndics, gestionnaires d'entrepôts, commerces, organisateurs d'événements, chantiers, parfois des acheteurs publics. Ils comparent plusieurs prestataires, souvent par écrit, et ils ont une responsabilité : confier un site à une entreprise qui ne serait pas en règle leur poserait problème.</p>
        <p>Le métier est aussi encadré. Les entreprises, leurs dirigeants et leurs agents doivent être autorisés par le CNAPS, le Conseil national des activités privées de sécurité. Un site qui ignore cet aspect paraît amateur aux yeux d'un client averti. Un site qui le met en avant rassure tout de suite.</p>
        <p>Enfin, votre site a souvent un deuxième public : <strong>les agents que vous voulez recruter</strong>. Dans un secteur où trouver du personnel qualifié est un enjeu permanent, une bonne page recrutement fait partie du travail.</p>

        <h2>Ce que doit contenir le site d'une société de sécurité</h2>

        <h3>1. Les mentions liées à votre autorisation CNAPS</h3>
        <p>Le Code de la sécurité intérieure impose de faire figurer sur les documents de l'entreprise, y compris ses supports commerciaux, le numéro de l'autorisation d'exercice délivrée par le CNAPS ainsi qu'une mention rappelant que cette autorisation ne confère aucun caractère officiel à l'entreprise. Sur un site, on les place dans le pied de page et dans les mentions légales, pour qu'elles soient visibles sur toutes les pages.</p>
        <p>C'est aussi un argument commercial : un prospect qui voit le numéro d'autorisation sait qu'il a affaire à une entreprise en règle. Je l'intègre sur chaque site du secteur. Pour la formulation exacte, référez-vous aux textes en vigueur ou au CNAPS.</p>

        <h3>2. Une page par prestation</h3>
        <p>Gardiennage, rondes et intervention sur alarme, sécurité événementielle, sécurité incendie (SSIAP), maître-chien, accueil et filtrage, protection de chantiers… Chaque prestation correspond à des recherches différentes sur Google et à des clients différents. Une page dédiée par prestation explique ce que vous faites, pour qui, avec quels agents et quelles qualifications. C'est meilleur pour le référencement et plus clair pour le client.</p>

        <h3>3. Une zone d'intervention précise</h3>
        <p>« Nous intervenons partout » ne rassure personne. Un client veut savoir si vous couvrez sa ville et en combien de temps vous pouvez être sur place. Listez les départements et les principales villes couvertes, et créez des pages locales pour les secteurs où vous voulez vous développer.</p>

        <h3>4. Un formulaire de devis qui pose les bonnes questions</h3>
        <p>Un simple champ « message » oblige à rappeler le client pour tout lui redemander. Un formulaire adapté au métier vous fait gagner ce temps : type de site à protéger, prestation souhaitée, dates et horaires, nombre d'agents, qualifications nécessaires (SSIAP, cynophile…), mission ponctuelle ou contrat régulier. Vous recevez une demande exploitable, et le client a l'impression d'avoir affaire à des spécialistes.</p>

        <h3>5. Des preuves de sérieux</h3>
        <p>Références clients (avec leur accord), types de sites déjà sécurisés, certifications éventuelles, assurance responsabilité civile professionnelle, ancienneté de l'entreprise, présentation de l'encadrement. Pas besoin d'en faire trop : quelques éléments vérifiables valent mieux que des superlatifs.</p>

        <h3>6. Une page recrutement</h3>
        <p>Postes ouverts, qualifications demandées (carte professionnelle, SSIAP, SST…), secteurs concernés et formulaire de candidature. Les candidatures arrivent directement par e-mail, et les données sont traitées dans le respect du RGPD, ce que le site doit expliquer.</p>

        <h3>7. Un contact immédiat</h3>
        <p>Si vous avez une permanence téléphonique, votre numéro doit être visible et cliquable sur mobile, en haut de chaque page. Une demande de sécurité est souvent urgente.</p>

        <h2>Le cas des centres de formation en sécurité</h2>
        <p>Un organisme de formation aux métiers de la sécurité a des besoins différents d'une société de gardiennage. Ses visiteurs sont des futurs stagiaires, des demandeurs d'emploi, des entreprises qui forment leurs salariés, et des financeurs. Avec <strong>CFSSIS</strong>, centre de formation en sécurité incendie et sûreté, le site devait avant tout présenter clairement l'offre et faciliter les demandes d'inscription.</p>
        <p>Ce qui compte pour un centre de formation :</p>
        <ul>
          <li><strong>Un catalogue lisible</strong> : une fiche par formation (SSIAP 1, 2 et 3, formation d'agent de prévention et de sécurité, recyclages et remises à niveau, secourisme…) avec objectifs, durée, prérequis, débouchés.</li>
          <li><strong>Les prochaines sessions</strong> : dates et lieux, mis à jour régulièrement. C'est souvent la page la plus consultée.</li>
          <li><strong>Le financement</strong> : CPF, France Travail, OPCO, financement par l'employeur. Les candidats veulent savoir tout de suite s'ils peuvent être pris en charge.</li>
          <li><strong>Les informations attendues par Qualiopi</strong> : le référentiel qualité demande de rendre publiques certaines informations, comme les indicateurs de résultats, les conditions d'accès ou l'accessibilité aux personnes en situation de handicap. Le site est l'endroit naturel pour les publier.</li>
          <li><strong>L'autorisation CNAPS</strong> : les organismes qui forment aux activités privées de sécurité doivent eux aussi être autorisés. Le numéro a sa place sur le site.</li>
          <li><strong>Une demande d'inscription simple</strong> : formation visée, situation (salarié, demandeur d'emploi, entreprise), mode de financement envisagé.</li>
        </ul>

        <h2>Les erreurs que je vois le plus souvent</h2>
        <ol>
          <li><strong>Un site générique</strong>, qui pourrait être celui de n'importe quelle entreprise de services. Le client ne trouve ni vos prestations précises ni votre zone.</li>
          <li><strong>L'absence du numéro d'autorisation CNAPS</strong>, alors que c'est à la fois une obligation et un argument.</li>
          <li><strong>Des photos de banque d'images trop éloignées de la réalité.</strong> Des photos de vos équipes, en tenue, sur de vrais sites (avec l'accord des personnes) inspirent bien plus confiance.</li>
          <li><strong>Un formulaire trop vague</strong>, qui oblige à rappeler chaque prospect.</li>
          <li><strong>Un site jamais mis à jour</strong> : anciennes prestations, numéro qui ne répond plus, sessions de formation passées.</li>
        </ol>

        <h2>Mes réalisations dans la sécurité</h2>
        <p>Ces sites sont en ligne, vous pouvez les consulter :</p>
        <div class="article-refs">
<?php foreach ($realisations as $r): ?>
          <a href="<?= $r['url'] ?>" target="_blank" rel="noopener" class="article-ref glass">
            <span class="article-ref-image" style="background-image: url('/images/portfolio/<?= $r['image'] ?>');"></span>
            <span class="article-ref-body">
              <span class="article-ref-type"><?= htmlspecialchars($r['type']) ?></span>
              <strong><?= htmlspecialchars($r['nom']) ?></strong>
              <span><?= htmlspecialchars($r['texte']) ?></span>
              <span class="article-ref-link">Voir le site →</span>
            </span>
          </a>
<?php endforeach; ?>
        </div>
        <p>Travailler avec plusieurs entreprises du même secteur m'a appris le vocabulaire du métier, les questions que posent vos clients et ce qu'ils vérifient avant de vous contacter. Vous n'avez pas à tout m'expliquer : on va droit à ce qui compte pour votre entreprise.</p>

        <h2>Combien coûte un site pour une entreprise de sécurité ?</h2>
        <p>Une société de sécurité a généralement besoin de plusieurs pages (prestations, zone, recrutement, contact) : comptez <strong><?= $euros($offres['vitrine5']['creation']) ?></strong> pour un site de 5 pages et <strong><?= $euros($offres['vitrine10']['creation']) ?></strong> pour 10 pages, avec une page par prestation ou par secteur. Le formulaire de devis adapté au métier est en option à <?= $euros($devisGuide) ?>. Ensuite, <?= $mensuel ?> € par mois couvrent l'hébergement, le nom de domaine, les mises à jour et vos modifications sous 48 h, sans engagement de durée.</p>
        <p>Pour un centre de formation, le nombre de pages dépend surtout du catalogue. Le plus simple est d'en parler : je vous fais une proposition adaptée sous 24 h. Vous pouvez aussi lire <a href="/conseils/prix-site-internet-artisan">combien coûte un site internet</a> pour comparer avec les autres solutions.</p>

        <h2>Questions fréquentes</h2>
        <div class="faq-list article-faq">
          <div class="faq-item glass">
            <button class="faq-question"><span>Je travaille surtout avec des appels d'offres. Un site sert-il à quelque chose ?</span><span>+</span></button>
            <div class="faq-answer"><p>Oui. Un acheteur qui reçoit votre dossier va presque toujours regarder votre site. Un site clair, avec votre autorisation, vos prestations et vos références, renforce votre candidature. Il vous apporte aussi des demandes directes, en dehors des appels d'offres.</p></div>
          </div>
          <div class="faq-item glass">
            <button class="faq-question"><span>Pouvez-vous reprendre un site existant ?</span><span>+</span></button>
            <div class="faq-answer"><p>Oui. Je reprends les contenus utiles, je corrige ce qui manque (mentions, prestations, formulaire) et je veille à conserver votre nom de domaine et votre historique sur Google.</p></div>
          </div>
          <div class="faq-item glass">
            <button class="faq-question"><span>Intervenez-vous en dehors de l'Eure ?</span><span>+</span></button>
            <div class="faq-answer"><p>Oui. Je suis basé à Évreux et je rencontre volontiers les entreprises de Normandie sur place. Pour les autres régions, tout se fait très bien en visio et par e-mail.</p></div>
          </div>
        </div>

        <div class="article-cta glass-strong">
          <h2>Un projet de site pour votre entreprise de sécurité ?</h2>
          <p>Décrivez votre activité, je vous réponds sous 24 h avec une proposition adaptée.</p>
          <div class="hero-buttons">
            <a href="/contact" class="btn btn-primary">Demander un devis</a>
            <a href="/secteurs/securite-privee" class="btn btn-secondary">Voir l'offre sécurité privée</a>
          </div>
        </div>

        <p class="article-related">À lire aussi : <a href="/conseils/prix-site-internet-artisan">Combien coûte un site internet ?</a> · <a href="/realisations">Toutes mes réalisations</a></p>
      </div>
    </div>
  </article>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
