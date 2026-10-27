<?php
require_once dirname(__DIR__) . '/config/site.php';

$slug = 'planning-cabinet-infirmier';
require_once dirname(__DIR__) . '/includes/conseils.php';
$article = conseil($slug);

$pageTitle = 'Planning de cabinet infirmier : un outil sur mesure | Pertec';
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
    'dateModified' => $article['modifie'],
    'inLanguage' => 'fr-FR',
    'mainEntityOfPage' => 'https://pertec.fr' . $canonicalUrl,
    'image' => 'https://pertec.fr/images/og-pertec.jpg',
    'author' => ['@type' => 'Person', 'name' => 'Fabien Perrin', 'url' => 'https://pertec.fr/a-propos'],
    'publisher' => ['@id' => 'https://pertec.fr/#entreprise', '@type' => 'ProfessionalService', 'name' => 'Pertec'],
    'about' => ['Planning infirmier', 'Cabinet infirmier libéral', 'Application sur mesure'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

$fonctions = [
    ['Génération automatique', 'Choisissez une période : l\'outil remplit tous les créneaux du matin et du soir en respectant les indisponibilités et les règles du cabinet.'],
    ['Équité entre associés', 'Matins, soirs, week-ends et jours fériés sont répartis le plus équitablement possible. Un tableau montre l\'équilibre entre associés et l\'écart restant.'],
    ['Règles du cabinet', 'Par exemple : la même paire travaille le samedi et le dimanche, celui qui fait le soir enchaîne le lendemain matin, pas trop de jours d\'affilée.'],
    ['Indisponibilités et remplaçantes', 'Chacun indique ses indisponibilités (matin, soir ou journée). Les remplaçantes ont leur place dans le planning sans fausser l\'équilibre entre associés.'],
    ['Combler les trous', 'Un congé de dernière minute ? L\'outil comble les créneaux vides et peut rééquilibrer par échanges, sans tout refaire.'],
    ['Jours fériés', 'Un clic sur un jour le marque comme férié. Les fériés sont comptés à part pour être répartis équitablement eux aussi.'],
    ['Agenda du téléphone', 'Chacun s\'abonne à son propre planning dans Google Agenda, Calendrier Apple ou Outlook. Les changements s\'y reportent automatiquement.'],
    ['Export PDF', 'Le planning du mois, en couleurs, prêt à imprimer ou à envoyer, pour toute l\'équipe ou une seule personne.'],
    ['Historique et sauvegardes', 'Chaque modification est enregistrée et les versions précédentes sont conservées : on peut retrouver le planning d\'avant.'],
];

$metiers = [
    ['Cabinets d\'infirmiers libéraux', 'Tournées du matin et du soir, week-ends, remplaçantes.'],
    ['Centres de soins et SSIAD', 'Rotation des équipes, astreintes et week-ends.'],
    ['Cabinets de kinésithérapeutes', 'Permanences, samedis et remplacements entre associés.'],
    ['Sages-femmes et maisons de santé', 'Gardes, permanences et présences partagées.'],
    ['Services d\'aide à domicile', 'Interventions matin et soir, répartition des week-ends.'],
    ['Toute équipe qui tourne', 'Pharmacies de garde, cabinets vétérinaires, astreintes techniques.'],
];

require_once dirname(__DIR__) . '/includes/header.php';
?>

  <article class="article">
    <header class="page-header article-header">
      <div class="container">
        <nav class="article-breadcrumb" aria-label="Fil d'Ariane"><a href="/">Accueil</a> › <a href="/conseils/">Conseils</a> › <span><?= htmlspecialchars($article['categorie']) ?></span></nav>
        <h1 data-animate>Planning de cabinet infirmier : <span class="gradient-text">fini les heures sur le tableau</span></h1>
        <p class="article-meta" data-animate>Par <a href="/a-propos">Fabien Perrin</a>, créateur de sites et d'outils web à Évreux · <time datetime="<?= $article['date'] ?>"><?= date_longue_fr($article['date']) ?></time> · 6 min de lecture</p>
      </div>
    </header>

    <div class="container">
      <div class="article-prose">

        <p>Dans un cabinet d'infirmiers libéraux (IDEL), le planning est rarement simple. Il faut couvrir chaque matin et chaque soir, partager équitablement les week-ends et les jours fériés entre associés, intégrer les remplaçantes, tenir compte des indisponibilités de chacun… et tout refaire dès qu'un congé tombe. Beaucoup de cabinets y passent des soirées, sur un tableur ou une feuille papier, avec toujours le sentiment que quelqu'un est lésé.</p>
        <p>J'ai développé pour un cabinet infirmier un outil de planning qui fait ce travail automatiquement. Voici ce qu'il fait, et pourquoi il peut servir à d'autres équipes de soignants.</p>

        <div class="article-answer glass">
          <p class="article-answer-label">En bref</p>
          <p>Une application web qui <strong>génère le planning de tout le cabinet en un clic</strong>, répartit équitablement matins, soirs, week-ends et fériés entre associés, gère les remplaçantes et les indisponibilités, et envoie le planning de chacun <strong>directement dans l'agenda de son téléphone</strong>. Elle ne contient aucune donnée de patient : seulement les membres de l'équipe et leurs créneaux.</p>
        </div>

        <h2>Le problème : un planning qui prend du temps et crée des tensions</h2>
        <p>Un planning de cabinet, ce n'est pas qu'un calendrier. Ce sont des règles, souvent implicites : la même personne fait le samedi et le dimanche, celui qui fait la tournée du soir enchaîne celle du lendemain matin, personne ne travaille trop de jours d'affilée, les fériés tournent. Ajoutez les vacances, les remplaçantes et les imprévus, et la moindre modification oblige à tout revérifier à la main.</p>
        <p>Le résultat : du temps perdu, des erreurs (un créneau oublié, deux personnes le même matin) et, surtout, des discussions sur l'équité. Qui a fait le plus de week-ends cette année ? Personne ne sait vraiment.</p>

        <h2>Ce que fait l'outil : un calendrier de rotation automatique</h2>
        <figure class="article-figure">
          <img src="/images/conseils/planning-infirmier-calendrier.webp" width="1400" height="956" loading="lazy" decoding="async" alt="Outil de planning de cabinet infirmier : vue du mois de janvier, avec pour chaque jour les infirmières du matin (M) et du soir (S) en couleur">
          <figcaption>La vue mensuelle : chaque couleur est une infirmière, M pour la tournée du matin, S pour celle du soir. Les week-ends et jours fériés sont signalés. Équipe fictive.</figcaption>
        </figure>
        <div class="article-features">
<?php foreach ($fonctions as [$titre, $texte]): ?>
          <div class="article-feature glass">
            <h3><?= htmlspecialchars($titre) ?></h3>
            <p><?= htmlspecialchars($texte) ?></p>
          </div>
<?php endforeach; ?>
        </div>

        <h2>Ce que ça change au quotidien</h2>
        <figure class="article-figure">
          <img src="/images/conseils/planning-infirmier-equilibre.webp" width="1400" height="714" loading="lazy" decoding="async" alt="Tableau d'équilibre entre associés : nombre de demi-journées, week-ends et jours fériés de chaque infirmière, avec l'écart à la moyenne">
          <figcaption>L'équilibre entre associés : demi-journées, week-ends et fériés de chacun, avec l'écart à la moyenne. La remplaçante est comptée à part. Équipe fictive.</figcaption>
        </figure>
        <ul>
          <li><strong>Moins de temps passé</strong> : le planning de plusieurs semaines est généré en quelques secondes, puis ajusté si besoin.</li>
          <li><strong>Plus de débat sur l'équité</strong> : la rotation des week-ends et des fériés est calculée, et les compteurs sont visibles par tous. Si un associé a fait plus de week-ends, ça se voit, et l'outil le compense.</li>
          <li><strong>Moins d'erreurs</strong> : chaque créneau est couvert, les indisponibilités sont respectées, les règles du cabinet sont appliquées à chaque fois.</li>
          <li><strong>Tout le monde à jour</strong> : chacun voit son planning dans l'agenda de son téléphone, sans recopier, et les changements s'y reportent.</li>
        </ul>

        <h2>Pour quels métiers ?</h2>
        <p>L'outil a été conçu pour un cabinet d'infirmiers libéraux, mais le principe s'applique à toute équipe qui doit se partager des créneaux de façon équitable :</p>
        <div class="article-features article-features-compact">
<?php foreach ($metiers as [$titre, $texte]): ?>
          <div class="article-feature glass">
            <h3><?= htmlspecialchars($titre) ?></h3>
            <p><?= htmlspecialchars($texte) ?></p>
          </div>
<?php endforeach; ?>
        </div>
        <p>Les créneaux (matin, soir, garde, astreinte), leurs horaires, le nombre de personnes nécessaires et les règles de répartition sont adaptés à chaque structure.</p>

        <h2>Et les données ?</h2>
        <p>L'outil ne gère <strong>aucune information sur les patients</strong> : ni nom, ni adresse, ni soin. Il ne contient que les prénoms des membres de l'équipe, leurs couleurs, leurs indisponibilités et les créneaux attribués. C'est volontaire : un planning d'équipe n'a pas besoin de données de santé, ce qui évite les contraintes d'un hébergement de données de santé.</p>
        <p>Chaque cabinet dispose de sa propre installation, séparée des autres.</p>

        <h2>Un outil adapté à votre cabinet</h2>
        <p>Chaque équipe a ses habitudes. Je pars de l'outil existant et je l'adapte à votre fonctionnement :</p>
        <ul>
          <li>vos créneaux et leurs horaires, et le nombre de personnes par créneau en semaine, le week-end et les jours fériés ;</li>
          <li>vos règles de répartition (paires de week-end, enchaînements, limites de jours consécutifs…) ;</li>
          <li>le nombre d'associés et de remplaçants, et la façon de compter leur temps ;</li>
          <li>vos couleurs et la présentation du PDF ;</li>
          <li>l'installation sur votre propre adresse, avec un accès réservé à votre équipe.</li>
        </ul>
        <p>Le prix dépend de ces adaptations : je vous fais un devis précis après un premier échange, gratuit et sans engagement.</p>

        <h2>Questions fréquentes</h2>
        <div class="faq-list article-faq">
          <div class="faq-item glass">
            <button class="faq-question"><span>Faut-il installer quelque chose ?</span><span>+</span></button>
            <div class="faq-answer"><p>Non. C'est une application web : elle s'ouvre dans le navigateur, sur ordinateur comme sur téléphone. Pour suivre son planning, chacun s'abonne simplement au calendrier depuis son agenda habituel.</p></div>
          </div>
          <div class="faq-item glass">
            <button class="faq-question"><span>Peut-on modifier le planning à la main ?</span><span>+</span></button>
            <div class="faq-answer"><p>Oui. La génération automatique propose un planning, que vous pouvez ensuite ajuster. Pour un imprévu, la fonction « Combler les trous » complète seulement les créneaux vides, sans refaire tout le planning.</p></div>
          </div>
          <div class="faq-item glass">
            <button class="faq-question"><span>Existe-t-il un planning infirmier gratuit ?</span><span>+</span></button>
            <div class="faq-answer"><p>Oui : un tableur ou un modèle de calendrier partagé peut suffire pour un petit cabinet. Mais il faut tout remplir et vérifier à la main (équité des week-ends, indisponibilités, remplaçantes). Cet outil automatise ces calculs ; il est adapté à chaque cabinet, donc proposé sur devis.</p></div>
          </div>
          <div class="faq-item glass">
            <button class="faq-question"><span>Est-ce un logiciel de facturation ou de dossier patient ?</span><span>+</span></button>
            <div class="faq-answer"><p>Non. C'est un outil de planning d'équipe uniquement. Il se combine avec le logiciel métier que vous utilisez déjà pour la télétransmission et le suivi des patients.</p></div>
          </div>
          <div class="faq-item glass">
            <button class="faq-question"><span>Mon cabinet a des règles particulières, est-ce possible ?</span><span>+</span></button>
            <div class="faq-answer"><p>C'est justement l'intérêt d'un outil sur mesure. Décrivez-moi votre fonctionnement actuel, même sur papier : je vous dis ce qui peut être automatisé et à quel prix.</p></div>
          </div>
        </div>

        <div class="article-cta glass-strong">
          <h2>Un planning comme celui-ci pour votre équipe ?</h2>
          <p>Expliquez-moi comment votre cabinet s'organise aujourd'hui. Je vous réponds sous 24 h avec un devis adapté.</p>
          <div class="hero-buttons">
            <a href="/contact" class="btn btn-primary">Demander un devis</a>
            <a href="/secteurs/professions-liberales" class="btn btn-secondary">Sites pour professions libérales</a>
          </div>
        </div>

        <p class="article-related">À lire aussi : <a href="/secteurs/professions-liberales">Site internet pour professions libérales</a> · <a href="/services">Mes services, dont les outils sur mesure</a></p>
      </div>
    </div>
  </article>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
