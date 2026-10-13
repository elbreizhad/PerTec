<?php
require_once dirname(__DIR__) . '/config/site.php';
require_once dirname(__DIR__) . '/includes/conseils.php';

$slug = 'faire-connaitre-entreprise-securite-privee';
$article = conseil($slug);

// Prix lus depuis la grille tarifaire (source unique)
$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];
$euros = fn(int $n) => number_format($n, 0, ',', ' ') . ' €';

$pageTitle = 'Faire connaître son entreprise de sécurité privée | Pertec';
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
    'about' => ['Sécurité privée', 'Référencement local', 'Prospection'],
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

$canaux = [
    ['Site internet et référencement', 'Être trouvé quand un client cherche « société de sécurité + ville ».', 'Durable, travaille 24h/24', 'Quelques mois pour monter'],
    ['Fiche Google Business Profile', 'Apparaître sur la carte Google et recueillir des avis.', 'Gratuit, très visible en local', 'Demande des avis réguliers'],
    ['Réseau et bouche-à-oreille', 'Syndics, gestionnaires, organisateurs, clubs d\'entreprises.', 'Contrats solides', 'Lent, dépend de votre temps'],
    ['Appels d\'offres', 'Marchés publics et privés de gardiennage ou d\'événementiel.', 'Gros volumes', 'Très concurrentiel, dossiers lourds'],
    ['Partenariats', 'Installateurs d\'alarme et de vidéo, entreprises de nettoyage, organisateurs d\'événements.', 'Clients déjà qualifiés', 'À entretenir'],
    ['LinkedIn', 'Montrer vos missions et votre sérieux aux décideurs.', 'Bon pour la crédibilité', 'Peu de demandes directes'],
];

require_once dirname(__DIR__) . '/includes/header.php';
?>

  <article class="article">
    <header class="page-header article-header">
      <div class="container">
        <nav class="article-breadcrumb" aria-label="Fil d'Ariane"><a href="/">Accueil</a> › <a href="/conseils/">Conseils</a> › <span><?= htmlspecialchars($article['categorie']) ?></span></nav>
        <h1 data-animate>Comment faire connaître <span class="gradient-text">son entreprise de sécurité privée</span> ?</h1>
        <p class="article-meta" data-animate>Par <a href="/a-propos">Fabien Perrin</a>, créateur de sites à Évreux · <time datetime="<?= $article['date'] ?>"><?= date_longue_fr($article['date']) ?></time> · 8 min de lecture</p>
      </div>
    </header>

    <div class="container">
      <div class="article-prose">

        <p>Vous avez votre autorisation CNAPS, des agents de sécurité qualifiés, peut-être déjà quelques clients. La question suivante arrive vite : comment trouver les prochains ? Dans la sécurité privée, beaucoup d'entreprises vivent du bouche-à-oreille et des appels d'offres. Ça fonctionne, mais ça rend dépendant de quelques contrats et ça laisse passer tous les clients qui cherchent un prestataire sur Google.</p>
        <p>J'ai créé les sites de plusieurs entreprises du secteur (STS Sécurité, Vigiprestance, Solution Sécurité) et d'un centre de formation (CFSSIS). Voici les leviers qui marchent pour se faire connaître, et pourquoi le site internet en est le socle.</p>

        <div class="article-answer glass">
          <p class="article-answer-label">La réponse courte</p>
          <p>Combinez <strong>un site internet bien référencé</strong> sur vos prestations et vos villes, <strong>une fiche Google</strong> avec des avis, et votre <strong>réseau local</strong>. Le site est la pièce centrale : c'est lui qui vous fait trouver par les clients qui ne vous connaissent pas encore, et c'est lui que consultent tous les autres (prospects du réseau, acheteurs d'appels d'offres) avant de vous faire confiance.</p>
        </div>

        <h2>Qui sont vos clients, et comment cherchent-ils ?</h2>
        <p>Avant de choisir un canal, il faut savoir qui vous voulez toucher. Une entreprise de sécurité privée travaille généralement pour :</p>
        <ul>
          <li><strong>des syndics et gestionnaires de biens</strong> (résidences, parkings, rondes) ;</li>
          <li><strong>des entreprises</strong> (entrepôts, sites industriels, sièges, chantiers) ;</li>
          <li><strong>des commerces</strong> (agents de sécurité en magasin, prévention des vols) ;</li>
          <li><strong>des organisateurs d'événements</strong> (salons, concerts, mariages, manifestations sportives) ;</li>
          <li><strong>des collectivités</strong>, souvent par appel d'offres.</li>
        </ul>
        <p>Quand ils ont un besoin, la plupart tapent leur question dans Google : « société de sécurité Rouen », « agent de sécurité événementiel Évreux », « gardiennage chantier Eure », « maître-chien parking ». Même ceux qui ont eu votre nom par recommandation vont vérifier votre site avant d'appeler. Si vous n'apparaissez pas, ou si votre site ne rassure pas, ils appellent le suivant.</p>

        <h2>Les 6 leviers pour se faire connaître</h2>
        <div class="article-table-wrap">
          <table class="article-table">
            <thead>
              <tr><th>Levier</th><th>À quoi il sert</th><th>Point fort</th><th>Limite</th></tr>
            </thead>
            <tbody>
<?php foreach ($canaux as [$canal, $role, $fort, $limite]): ?>
              <tr><td><strong><?= htmlspecialchars($canal) ?></strong></td><td><?= htmlspecialchars($role) ?></td><td><?= htmlspecialchars($fort) ?></td><td><?= htmlspecialchars($limite) ?></td></tr>
<?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <p>Ces leviers se complètent, mais ils ont un point commun : tous finissent par envoyer le prospect sur votre site. Le partenaire qui vous recommande, l'acheteur qui lit votre dossier, le décideur qui voit votre publication LinkedIn : chacun va taper votre nom dans Google. Le site est l'endroit où se décide la confiance.</p>

        <h2>Pourquoi le site internet est le socle</h2>
        <h3>Il vous fait trouver par des clients qui ne vous connaissent pas</h3>
        <p>Le réseau et les recommandations vous apportent des clients qui ont déjà entendu parler de vous. Le référencement sur Google vous amène tous les autres : le gestionnaire d'entrepôt qui vient d'arriver dans la région, l'organisateur d'un salon qui cherche une équipe pour le mois prochain, le commerçant qui a subi des vols. C'est la seule façon de toucher ces clients sans démarchage.</p>

        <h3>Il travaille pendant que vous êtes sur le terrain</h3>
        <p>Vos journées et vos nuits se passent sur les missions, pas devant un ordinateur. Un site bien construit reçoit des demandes de devis à toute heure, avec les bonnes informations (type de site, dates, nombre d'agents), et vous les traitez quand vous le pouvez.</p>

        <h3>Il prouve que vous êtes en règle</h3>
        <p>Votre numéro d'autorisation CNAPS, vos prestations précises, votre zone d'intervention, vos références : un site professionnel rassure un donneur d'ordre en quelques secondes. Le détail de ce qu'il doit contenir est dans <a href="/conseils/site-internet-entreprise-securite-privee">ce que doit contenir le site d'une entreprise de sécurité</a>.</p>

        <h3>Il vous aide aussi à recruter</h3>
        <p>Trouver des agents de sécurité qualifiés est un défi permanent. Une page recrutement claire sur votre site attire des candidatures, et un site sérieux donne envie aux bons profils de vous rejoindre plutôt qu'un concurrent.</p>

        <h2>Le référencement (SEO) d'une entreprise de sécurité, concrètement</h2>
        <p>Avoir un site ne suffit pas : il faut qu'il apparaisse sur les bonnes recherches. Voici ce qui fonctionne pour une entreprise de sécurité privée.</p>
        <ol>
          <li><strong>Une page par prestation.</strong> « Gardiennage », « rondes et intervention », « sécurité événementielle », « agent SSIAP », « maître-chien » : chacune correspond à des recherches différentes. Une page dédiée, avec un vrai texte, a beaucoup plus de chances d'apparaître qu'une simple liste sur la page d'accueil.</li>
          <li><strong>Des pages pour vos villes et départements.</strong> Les clients cherchent avec le nom de leur ville. Une page « Société de sécurité à Rouen » ou « Gardiennage dans l'Eure », avec un contenu propre à chaque secteur (pas un copier-coller), vous positionne localement.</li>
          <li><strong>Une fiche Google reliée au site.</strong> Sur une recherche locale, Google affiche d'abord une carte avec trois entreprises. Votre fiche Google, complète et reliée à votre site, est votre ticket d'entrée. Les avis de vos clients comptent beaucoup dans ce classement.</li>
          <li><strong>Des textes écrits pour vos clients.</strong> Expliquez ce que vous faites, pour qui, avec quels agents et quelles qualifications. Google classe les pages qui répondent vraiment aux questions, pas celles qui répètent des mots-clés.</li>
          <li><strong>Un site rapide et lisible sur téléphone.</strong> Beaucoup de demandes de sécurité sont urgentes et faites depuis un mobile. Un site lent ou mal affiché sur téléphone perd ces visiteurs, et Google le sait.</li>
          <li><strong>Des liens depuis d'autres sites.</strong> Partenaires, fédérations professionnelles, annuaires sérieux, clubs d'entreprises locaux : chaque lien vers votre site renforce sa crédibilité aux yeux de Google.</li>
          <li><strong>Un suivi régulier.</strong> Google Search Console, un outil gratuit, montre sur quelles recherches vous apparaissez. On ajuste les pages en fonction, on ajoute une prestation ou une ville quand vous vous développez.</li>
        </ol>
        <p>Le référencement prend du temps : comptez généralement quelques semaines à quelques mois pour voir les premières positions, selon la concurrence dans votre zone. Aucun prestataire sérieux ne peut vous promettre la première place. En revanche, une fois en place, il vous apporte des demandes chaque mois, sans payer à chaque clic comme avec la publicité.</p>

        <h2>Et la publicité Google ?</h2>
        <p>Les annonces Google Ads donnent une visibilité immédiate, utile pour lancer une activité ou une nouvelle prestation. Mais chaque clic se paie, et la visibilité s'arrête le jour où vous arrêtez de payer. Elles complètent un site bien référencé, elles ne le remplacent pas. Et elles renvoient de toute façon vers votre site : s'il ne convainc pas, l'argent est perdu.</p>

        <h2>Un plan d'action sur 3 mois</h2>
        <ol>
          <li><strong>Mois 1 : les bases.</strong> Site professionnel avec une page par prestation, votre zone d'intervention et votre numéro CNAPS. Fiche Google complète et reliée au site. Demande d'avis à vos clients actuels.</li>
          <li><strong>Mois 2 : le local.</strong> Pages pour vos principales villes ou départements. Inscription dans quelques annuaires professionnels sérieux. Liens depuis vos partenaires.</li>
          <li><strong>Mois 3 : mesurer et ajuster.</strong> Search Console pour voir les recherches qui vous amènent des visites, ajustement des pages, ajout d'un article ou d'une prestation selon les demandes reçues.</li>
        </ol>

        <h2>Ce que je propose aux entreprises de sécurité</h2>
        <p>Je crée des sites pour les entreprises de sécurité privée et les centres de formation, en tenant compte des spécificités du secteur : mentions CNAPS, une page par prestation, pages locales, formulaire de devis adapté, page recrutement, fiche Google reliée. Les textes sont écrits pour être trouvés sur Google et pour rassurer vos clients.</p>
        <p>Pour une entreprise de sécurité, comptez <strong><?= $euros($offres['vitrine5']['creation']) ?></strong> pour un site de 5 pages et <strong><?= $euros($offres['vitrine10']['creation']) ?></strong> pour 10 pages (une page par prestation ou par secteur), puis <?= $mensuel ?> € par mois pour l'hébergement, le nom de domaine, les mises à jour et vos modifications. Vous pouvez voir <a href="/secteurs/securite-privee">les sites déjà réalisés dans la sécurité</a>.</p>

        <h2>Questions fréquentes</h2>
        <div class="faq-list article-faq">
          <div class="faq-item glass">
            <button class="faq-question"><span>J'ai déjà un site, mais il ne m'apporte aucun client. Que faire ?</span><span>+</span></button>
            <div class="faq-answer"><p>C'est fréquent : un site qui présente l'entreprise sur une seule page, sans prestation détaillée ni ville, apparaît rarement sur Google. Souvent, il suffit de le restructurer (une page par prestation, des pages locales, une fiche Google reliée) sans tout refaire. Je peux regarder votre site et vous dire ce qui bloque.</p></div>
          </div>
          <div class="faq-item glass">
            <button class="faq-question"><span>Les annuaires en ligne suffisent-ils ?</span><span>+</span></button>
            <div class="faq-answer"><p>Ils aident, surtout pour les liens vers votre site, mais vous y êtes listé à côté de tous vos concurrents. Votre site est le seul endroit où le client ne voit que vous.</p></div>
          </div>
          <div class="faq-item glass">
            <button class="faq-question"><span>Combien de temps avant d'avoir des demandes ?</span><span>+</span></button>
            <div class="faq-answer"><p>La fiche Google peut apporter des appels assez vite si elle est bien remplie et reçoit des avis. Le référencement du site prend généralement quelques semaines à quelques mois, selon la concurrence dans votre zone et le nombre de prestations et de villes visées.</p></div>
          </div>
        </div>

        <div class="article-cta glass-strong">
          <h2>Faire connaître votre entreprise de sécurité</h2>
          <p>Décrivez votre activité et votre zone : je vous réponds sous 24 h avec une proposition de site pensé pour le référencement.</p>
          <div class="hero-buttons">
            <a href="/contact" class="btn btn-primary">Demander un devis</a>
            <a href="/secteurs/securite-privee" class="btn btn-secondary">Voir l'offre sécurité privée</a>
          </div>
        </div>

        <p class="article-related">À lire aussi : <a href="/conseils/site-internet-entreprise-securite-privee">Site internet pour entreprise de sécurité : ce qui rassure vos clients</a> · <a href="/conseils/prix-site-internet-artisan">Combien coûte un site internet ?</a></p>
      </div>
    </div>
  </article>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
