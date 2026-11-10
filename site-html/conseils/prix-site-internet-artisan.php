<?php
require_once dirname(__DIR__) . '/config/site.php';

$slug = 'prix-site-internet-artisan';
require_once dirname(__DIR__) . '/includes/conseils.php';
$article = conseil($slug);

// Prix Pertec lus depuis la grille tarifaire (source unique)
$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$formules = $tarifs['formule']['options'];
$engagement = $tarifs['formule']['engagement']; // 24 mois
$maintenance = $tarifs['maintenance']['mensuel'];
$vitrine = $formules['vitrine']['mensuel'];
$complet = $formules['complet']['mensuel'];
$ecommerce = $formules['ecommerce']['mensuel'];
// Coût total sur 3 ans : 24 mois d'engagement + 12 mois de maintenance
$total3ans = fn(int $mensuel) => number_format($mensuel * 24 + $maintenance * 12, 0, ',', ' ');

$pageTitle = 'Prix d\'un site internet pour artisan en 2026 | Pertec';
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
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

require_once dirname(__DIR__) . '/includes/header.php';
?>

  <article class="article">
    <header class="page-header article-header">
      <div class="container">
        <nav class="article-breadcrumb" aria-label="Fil d'Ariane"><a href="/">Accueil</a> › <a href="/conseils/">Conseils</a> › <span><?= htmlspecialchars($article['categorie']) ?></span></nav>
        <h1 data-animate>Combien coûte un site internet <span class="gradient-text">pour un artisan</span> en 2026 ?</h1>
        <p class="article-meta" data-animate>Par <a href="/a-propos">Fabien Perrin</a>, créateur de sites à Évreux · <time datetime="<?= $article['date'] ?>"><?= date_longue_fr($article['date']) ?></time> · 7 min de lecture</p>
      </div>
    </header>

    <div class="container">
      <div class="article-prose">

        <figure class="article-figure">
          <img src="/images/conseils/prix-site-artisan.webp" width="800" height="534" decoding="async" alt="Artisan électricien au travail sur un tableau électrique">
          <figcaption>Pour un artisan, le bon site est celui qui rapporte des demandes de devis, pas forcément le moins cher.</figcaption>
        </figure>

        <div class="article-answer glass">
          <p class="article-answer-label">La réponse courte</p>
          <p>Pour un site vitrine d'artisan fait par un professionnel, comptez généralement <strong>entre 50 € et 150 € par mois</strong> tout compris (hébergement, domaine, maintenance), avec un engagement de 12 à 24 mois. Les créateurs de sites (Wix, Jimdo) sont moins chers mais vous faites tout vous-même. Les agences facturent 2 500 € à 8 000 € à la création.</p>
          <p>Le bon chiffre à regarder n'est pas le prix mensuel, mais <strong>le coût total sur 3 ans</strong>, et ce que le site vous rapporte en demandes de devis.</p>
        </div>

        <h2>Les 4 façons d'avoir un site, et leur vrai coût</h2>
        <p>Les montants ci-dessous sont des ordres de grandeur couramment pratiqués en France. Chaque prestataire a ses tarifs, mais ces fourchettes permettent de savoir si un devis est dans la norme.</p>

        <div class="article-table-wrap">
          <table class="article-table article-table-prix">
            <thead>
              <tr><th>Solution</th><th>À la création</th><th>Mensuel</th><th>Sur 3 ans (environ)</th><th>Qui fait le travail ?</th></tr>
            </thead>
            <tbody>
              <tr><td><strong>Créateur de site</strong> (Wix, Jimdo, Squarespace…)</td><td>0 €</td><td>15 à 35 €/mois</td><td>550 à 1 300 €</td><td>Vous : textes, photos, mise en page, référencement</td></tr>
              <tr><td><strong>Indépendant avec abonnement</strong> (comme Pertec)</td><td>0 €</td><td><?= $vitrine ?> à <?= $complet ?> €/mois sur 24 mois</td><td><?= $total3ans($vitrine) ?> à <?= $total3ans($complet) ?> €</td><td>Un professionnel, site transférable à vous en fin d'engagement</td></tr>
              <tr><td><strong>Indépendant achat direct</strong></td><td>500 à 2 500 €</td><td>0 à 50 €/mois</td><td>800 à 4 000 €</td><td>Un professionnel, vous êtes propriétaire dès le départ</td></tr>
              <tr><td><strong>Agence web</strong></td><td>2 500 à 8 000 € et plus</td><td>50 à 150 €/mois</td><td>4 000 à 13 000 €</td><td>Une équipe, plus de process</td></tr>
            </tbody>
          </table>
        </div>

        <figure class="article-figure">
          <img src="/images/conseils/prix-site-cout-3-ans.webp" width="1400" height="610" loading="lazy" decoding="async" alt="Graphique du coût d'un site vitrine sur 3 ans : créateur de site 550 à 1 300 €, indépendant 800 à 4 000 €, location 3 000 à 7 000 €, agence 4 000 à 13 000 €">
          <figcaption>Sur 3 ans, la location avec engagement coûte souvent plus cher qu'un site créé par un indépendant, sans que le site vous appartienne.</figcaption>
        </figure>

        <h3>Le créateur de site : pas cher, mais pas gratuit</h3>
        <p>Les outils comme Wix ou Jimdo sont faciles à prendre en main et conviennent si vous aimez faire les choses vous-même. Le coût caché, c'est votre temps : rédiger les textes, choisir les photos, régler le référencement et tout mettre à jour prend souvent plusieurs dizaines d'heures, le soir après les chantiers. Et un site fait rapidement ressort rarement bien sur Google face à des concurrents mieux préparés.</p>

        <h3>L'abonnement avec engagement : comparer les conditions</h3>
        <p>De plus en plus d'indépendants (dont Pertec) proposent un site sans frais de création, contre un abonnement mensuel avec engagement. L'avantage : un investissement initial nul et un coût prévisible. Mais attention aux conditions ! Certains démarcheurs proposent des engagements de 3 ou 4 ans avec résiliation coûteuse, et vous ne récupérez jamais le site. Avant de signer, posez trois questions : quelle est la durée d'engagement, à qui appartient le nom de domaine, et que récupérez-vous si vous arrêtez ? Chez Pertec, l'engagement est de 24 mois et le site vous est transféré à la fin si vous le souhaitez.</p>

        <h3>L'indépendant : le bon compromis pour la plupart des artisans</h3>
        <p>Un indépendant crée le site pour vous, avec un prix fixé à l'avance, et reste votre contact pour les modifications. C'est généralement la formule la plus équilibrée pour un artisan : un vrai site professionnel, sans les frais de structure d'une agence. Vérifiez simplement que le nom de domaine sera bien à votre nom et que les frais mensuels sont clairs.</p>

        <h3>L'agence : utile pour les projets plus lourds</h3>
        <p>Une agence se justifie pour un site avec beaucoup de pages, une boutique en ligne importante, ou une entreprise avec plusieurs services et un vrai budget marketing. Pour un artisan qui veut être trouvé dans sa ville et recevoir des demandes de devis, c'est souvent plus que nécessaire.</p>

        <h2>Prix d'un site internet selon votre métier</h2>
        <p>Le métier change surtout ce que le site doit faire, donc le nombre de pages et les options utiles. Voici ce qui convient le plus souvent, avec mes tarifs :</p>
        <div class="article-table-wrap">
          <table class="article-table">
            <thead>
              <tr><th>Métier</th><th>Ce qui compte sur le site</th><th>Formule adaptée</th></tr>
            </thead>
            <tbody>
              <tr><td><strong>Plombier, chauffagiste</strong></td><td>Dépannage, zone d'intervention, numéro cliquable, demande de devis</td><td>Site Vitrine (<?= $vitrine ?> €/mois) avec une page par service</td></tr>
              <tr><td><strong>Électricien</strong></td><td>Services (rénovation, mise aux normes, dépannage), qualifications</td><td>Site Vitrine (<?= $vitrine ?> €/mois)</td></tr>
              <tr><td><strong>Menuisier, maçon, couvreur</strong></td><td>Photos de réalisations, types de travaux, zone</td><td>Site Vitrine (<?= $vitrine ?> €/mois) avec galerie de chantiers</td></tr>
              <tr><td><strong>Coiffeur, esthéticienne</strong></td><td>Prestations et tarifs, prise de rendez-vous en ligne</td><td>Site Complet (<?= $complet ?> €/mois) avec réservation en ligne</td></tr>
              <tr><td><strong>Artisan multi-services ou multi-sites</strong></td><td>Une page par service ou par ville pour être trouvé partout</td><td>Site Complet (<?= $complet ?> €/mois) jusqu'à 10 pages</td></tr>
            </tbody>
          </table>
        </div>
        <p>Ces tarifs incluent l'hébergement, le nom de domaine, les mises à jour de sécurité et vos modifications pendant la durée de l'engagement (24 mois). Après cette période, le site vous est transféré ou vous pouvez continuer avec la maintenance à <?= $maintenance ?> €/mois.</p>

        <h2>Ce qui fait varier le prix</h2>
        <p>Deux sites « vitrine » peuvent avoir des prix très différents. Voici ce qui pèse vraiment :</p>
        <ul>
          <li><strong>Le nombre de pages.</strong> Une page unique bien faite suffit pour démarrer. Un site avec une page par service ou par ville vous rend plus visible sur Google, mais demande plus de travail.</li>
          <li><strong>La rédaction des textes.</strong> Si vous fournissez vos textes, le prix baisse. Si le prestataire les écrit pour vous, comptez quelques centaines d'euros de plus, et c'est souvent ce qui fait la différence sur Google.</li>
          <li><strong>Les fonctionnalités.</strong> Formulaire de demande de devis détaillé, prise de rendez-vous en ligne, galerie de chantiers, avis clients : chaque ajout a un coût.</li>
          <li><strong>Le logo et les photos.</strong> Si vous partez de zéro, il faudra peut-être créer un logo simple et prévoir de bonnes photos de vos réalisations.</li>
          <li><strong>Le référencement local.</strong> Être trouvé sur « plombier + votre ville » demande des pages bien construites et une fiche Google reliée au site.</li>
          <li><strong>Le délai.</strong> Un site en ligne en 10 jours au lieu de 3 à 4 semaines coûte généralement plus cher.</li>
        </ul>

        <h2>Les coûts qu'on oublie</h2>
        <p>Le prix de création n'est qu'une partie de l'addition. Pensez à demander ce qui est inclus pour :</p>
        <ul>
          <li><strong>Le nom de domaine</strong> (votre-entreprise.fr) : environ 10 à 20 € par an.</li>
          <li><strong>L'hébergement</strong> : de quelques euros à une vingtaine d'euros par mois selon la qualité.</li>
          <li><strong>La maintenance et la sécurité</strong> : mises à jour, sauvegardes, certificat SSL (le cadenas dans le navigateur).</li>
          <li><strong>Les modifications</strong> : changer un horaire, ajouter une photo, mettre à jour un tarif. Certains prestataires les facturent à l'unité.</li>
          <li><strong>Votre temps</strong>, surtout si vous faites le site vous-même.</li>
        </ul>

        <h2>Les 4 pièges à éviter</h2>
        <ol>
          <li><strong>Le nom de domaine qui n'est pas à votre nom.</strong> Si vous changez de prestataire, vous risquez de perdre l'adresse de votre site et tout son historique sur Google.</li>
          <li><strong>L'engagement long sans porte de sortie.</strong> Lisez les conditions de résiliation avant de signer.</li>
          <li><strong>Le site joli mais invisible.</strong> Un beau site que personne ne trouve sur Google ne rapporte rien. Demandez comment le site sera référencé sur votre ville et votre métier.</li>
          <li><strong>Le prix « à partir de » qui grimpe.</strong> Demandez un devis détaillé avec ce qui est inclus et ce qui ne l'est pas.</li>
        </ol>

        <h2>Combien investir pour que ce soit rentable ?</h2>
        <p>Prenez votre panier moyen : le montant d'un chantier ou d'une intervention type. Si votre site vous apporte un ou deux clients de plus par an, combien cela représente-t-il ? Pour beaucoup d'artisans, un seul chantier couvre le coût du site sur plusieurs années. C'est ce calcul qui compte, plus que le prix affiché.</p>

        <h2>Et chez Pertec, combien ça coûte ?</h2>
        <p>Mes prix sont affichés et identiques pour tous les clients. Formule abonnement sur 24 mois, tout compris :</p>
        <div class="article-prices">
          <div class="article-price glass"><span><?= htmlspecialchars($formules['vitrine']['label']) ?></span><strong><?= $vitrine ?> €/mois</strong><small>jusqu'à 5 pages · soit <?= $total3ans($vitrine) ?> € sur 3 ans</small></div>
          <div class="article-price glass"><span><?= htmlspecialchars($formules['complet']['label']) ?></span><strong><?= $complet ?> €/mois</strong><small>jusqu'à 10 pages + blog · soit <?= $total3ans($complet) ?> € sur 3 ans</small></div>
          <div class="article-price glass"><span><?= htmlspecialchars($formules['ecommerce']['label']) ?></span><strong><?= $ecommerce ?> €/mois</strong><small>boutique en ligne · soit <?= $total3ans($ecommerce) ?> € sur 3 ans</small></div>
        </div>
        <p>L'abonnement inclut l'hébergement, le nom de domaine, les mises à jour, les sauvegardes et vos modifications faites sous 48 h. Après les 24 mois, le site vous est transféré ou vous continuez avec la maintenance à <strong><?= $maintenance ?> €/mois</strong>.</p>
        <p>Pour connaître le prix exact de votre projet, <a href="/tarifs">le configurateur de tarifs</a> vous donne une estimation en une minute.</p>

        <h2>Questions fréquentes</h2>
        <div class="faq-list article-faq">
          <div class="faq-item glass">
            <button class="faq-question"><span>Un site à moins de 300 € est-il une bonne affaire ?</span><span>+</span></button>
            <div class="faq-answer"><p>Ça dépend de ce qui est inclus. À ce prix, il s'agit souvent d'un modèle peu personnalisé, sans rédaction ni travail de référencement. Ça peut suffire pour exister en ligne, mais rarement pour recevoir des demandes de devis.</p></div>
          </div>
          <div class="faq-item glass">
            <button class="faq-question"><span>Peut-on payer un site en plusieurs fois ?</span><span>+</span></button>
            <div class="faq-answer"><p>Beaucoup d'indépendants acceptent un acompte à la commande puis le solde à la mise en ligne, voire un paiement en plusieurs fois. Demandez-le simplement au moment du devis.</p></div>
          </div>
          <div class="faq-item glass">
            <button class="faq-question"><span>Un site internet est-il une dépense déductible ?</span><span>+</span></button>
            <div class="faq-answer"><p>Pour une entreprise, la création d'un site est une dépense professionnelle. Son traitement exact (charge ou immobilisation) dépend de votre régime et du montant : votre comptable vous dira comment l'enregistrer.</p></div>
          </div>
        </div>

        <div class="article-cta glass-strong">
          <h2>Combien coûterait votre site ?</h2>
          <p>Répondez à 4 questions et obtenez une estimation tout de suite, sans engagement.</p>
          <div class="hero-buttons">
            <a href="/tarifs" class="btn btn-primary">Estimer mon site</a>
            <a href="/contact" class="btn btn-secondary">Demander un devis</a>
          </div>
        </div>

        <p class="article-related">À lire aussi : <a href="/secteurs/artisan">Site internet pour artisans</a> · <a href="/realisations">Exemples de sites réalisés</a></p>
      </div>
    </div>
  </article>

<?php require_once dirname(__DIR__) . '/includes/footer.php'; ?>
