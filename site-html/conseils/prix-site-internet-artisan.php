<?php
require_once dirname(__DIR__) . '/config/site.php';

$slug = 'prix-site-internet-artisan';
require_once dirname(__DIR__) . '/includes/conseils.php';
$article = conseil($slug);

// Prix Pertec lus depuis la grille tarifaire (source unique)
$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];
$prixPage = $offres['page_unique']['creation'];
$prix5 = $offres['vitrine5']['creation'];
$prix10 = $offres['vitrine10']['creation'];
$redaction = $tarifs['options']['options']['redaction']['creation'];
$total3ans = fn(int $creation) => number_format($creation + $mensuel * 36, 0, ',', ' ');
$euros = fn(int $n) => number_format($n, 0, ',', ' ') . ' €';

$pageTitle = 'Combien coûte un site internet pour un artisan ? (prix 2026) | Pertec';
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

        <div class="article-answer glass">
          <p class="article-answer-label">La réponse courte</p>
          <p>Pour un site vitrine d'artisan fait par un professionnel, comptez généralement <strong>entre 500 € et 2 500 €</strong> à la création, puis <strong>de 0 à 50 € par mois</strong> pour l'hébergement, le nom de domaine et la maintenance. Moins cher, c'est vous qui faites le travail. Plus cher, c'est une agence ou un site très sur-mesure.</p>
          <p>Le bon chiffre à regarder n'est pas le prix de départ, mais <strong>le coût total sur 3 ans</strong>, et ce que le site vous rapporte en demandes de devis.</p>
        </div>

        <h2>Les 4 façons d'avoir un site, et leur vrai coût</h2>
        <p>Les montants ci-dessous sont des ordres de grandeur couramment pratiqués en France. Chaque prestataire a ses tarifs, mais ces fourchettes permettent de savoir si un devis est dans la norme.</p>

        <div class="article-table-wrap">
          <table class="article-table article-table-prix">
            <thead>
              <tr><th>Solution</th><th>À la création</th><th>Ensuite</th><th>Sur 3 ans (environ)</th><th>Qui fait le travail ?</th></tr>
            </thead>
            <tbody>
              <tr><td><strong>Créateur de site</strong> (Wix, Jimdo, Squarespace…)</td><td>0 €</td><td>15 à 35 €/mois</td><td>550 à 1 300 €</td><td>Vous : textes, photos, mise en page, référencement</td></tr>
              <tr><td><strong>Location avec engagement</strong></td><td>0 à 500 €</td><td>80 à 200 €/mois sur 24 à 48 mois</td><td>3 000 à 7 000 €</td><td>Le prestataire, mais le site ne vous appartient souvent pas</td></tr>
              <tr><td><strong>Indépendant</strong></td><td>500 à 2 500 €</td><td>0 à 50 €/mois</td><td>800 à 4 000 €</td><td>Un professionnel, un seul interlocuteur</td></tr>
              <tr><td><strong>Agence web</strong></td><td>2 500 à 8 000 € et plus</td><td>50 à 150 €/mois</td><td>4 000 à 13 000 €</td><td>Une équipe, plus de process</td></tr>
            </tbody>
          </table>
        </div>

        <h3>Le créateur de site : pas cher, mais pas gratuit</h3>
        <p>Les outils comme Wix ou Jimdo sont faciles à prendre en main et conviennent si vous aimez faire les choses vous-même. Le coût caché, c'est votre temps : rédiger les textes, choisir les photos, régler le référencement et tout mettre à jour prend souvent plusieurs dizaines d'heures, le soir après les chantiers. Et un site fait rapidement ressort rarement bien sur Google face à des concurrents mieux préparés.</p>

        <h3>La location : attention au contrat</h3>
        <p>C'est la formule que proposent beaucoup de démarcheurs : « pas de frais au départ, juste un abonnement ». Sur le papier, c'est indolore. En pratique, l'engagement court souvent sur 3 ou 4 ans, la résiliation anticipée est coûteuse, et à la fin vous n'êtes pas toujours propriétaire du site ni même du nom de domaine. Avant de signer, posez trois questions : quelle est la durée d'engagement, à qui appartient le nom de domaine, et que se passe-t-il si j'arrête ?</p>

        <h3>L'indépendant : le bon compromis pour la plupart des artisans</h3>
        <p>Un indépendant crée le site pour vous, avec un prix fixé à l'avance, et reste votre contact pour les modifications. C'est généralement la formule la plus équilibrée pour un artisan : un vrai site professionnel, sans les frais de structure d'une agence. Vérifiez simplement que le nom de domaine sera bien à votre nom et que les frais mensuels sont clairs.</p>

        <h3>L'agence : utile pour les projets plus lourds</h3>
        <p>Une agence se justifie pour un site avec beaucoup de pages, une boutique en ligne importante, ou une entreprise avec plusieurs services et un vrai budget marketing. Pour un artisan qui veut être trouvé dans sa ville et recevoir des demandes de devis, c'est souvent plus que nécessaire.</p>

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
        <p>Mes prix sont affichés et identiques pour tous les clients :</p>
        <div class="article-prices">
          <div class="article-price glass"><span><?= htmlspecialchars($offres['page_unique']['label']) ?></span><strong><?= $euros($prixPage) ?></strong><small>soit environ <?= $total3ans($prixPage) ?> € sur 3 ans, suivi compris</small></div>
          <div class="article-price glass"><span><?= htmlspecialchars($offres['vitrine5']['label']) ?></span><strong><?= $euros($prix5) ?></strong><small>soit environ <?= $total3ans($prix5) ?> € sur 3 ans, suivi compris</small></div>
          <div class="article-price glass"><span><?= htmlspecialchars($offres['vitrine10']['label']) ?></span><strong><?= $euros($prix10) ?></strong><small>soit environ <?= $total3ans($prix10) ?> € sur 3 ans, suivi compris</small></div>
        </div>
        <p>Puis <strong><?= $mensuel ?> € par mois</strong> qui couvrent l'hébergement, le nom de domaine, les mises à jour, les sauvegardes et vos modifications faites sous 48 h. Si vous souhaitez que je rédige vos textes, c'est <?= $euros($redaction) ?> en plus. Pas d'engagement de durée, et le nom de domaine est à votre nom.</p>
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
