<?php
require_once dirname(__DIR__) . '/includes/article.php';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];
$euros = fn(int $n) => number_format($n, 0, ',', ' ') . ' €';

$article = article_debut(
    'site-vitrine-catalogue-ecommerce',
    'Site vitrine, catalogue ou e-commerce ? | Pertec',
    'Site vitrine, catalogue, e-commerce ou institutionnel : <span class="gradient-text">lequel choisir ?</span>',
    7
);
?>

        <p>« Il me faut un site », d'accord. Mais lequel ? Un artisan, une boutique de centre-ville, une PME industrielle et une association n'ont pas les mêmes besoins. Choisir le mauvais type de site, c'est payer pour des fonctions inutiles, ou au contraire se retrouver bloqué six mois plus tard.</p>
        <p>Voici les quatre grandes familles de sites, à quoi elles servent, et comment choisir selon votre activité.</p>

        <div class="article-answer glass">
          <p class="article-answer-label">En bref</p>
          <p>Vous vendez un service ou un savoir-faire : <strong>site vitrine</strong>. Vous avez beaucoup de produits à montrer : <strong>site catalogue</strong>. Vous voulez encaisser en ligne : <strong>e-commerce</strong>. Vous devez présenter une organisation : <strong>site institutionnel</strong>. Et on peut toujours commencer simple puis évoluer.</p>
        </div>

        <h2>Le site vitrine : présenter et faire venir les demandes</h2>
        <p>C'est le cas le plus courant pour les artisans, les professions libérales et les entreprises de services. Il présente qui vous êtes, ce que vous faites, où vous intervenez, et permet de vous contacter ou de demander un devis.</p>
        <ul>
          <li><strong>Pour qui</strong> : plombier, électricien, coiffeur, avocat, société de sécurité, consultant…</li>
          <li><strong>Pages types</strong> : accueil, services, réalisations, à propos, contact.</li>
          <li><strong>Prix chez Pertec</strong> : <?= $euros($offres['page_unique']['creation']) ?> pour une page unique, <?= $euros($offres['vitrine5']['creation']) ?> pour 5 pages, <?= $euros($offres['vitrine10']['creation']) ?> pour 10 pages.</li>
        </ul>

        <h2>Le site catalogue : montrer ses produits sans forcément vendre en ligne</h2>
        <p>Entre la vitrine et la boutique en ligne, le catalogue présente vos produits avec photos, descriptions et parfois prix, mais la vente se fait en magasin, par téléphone ou sur devis. C'est souvent le meilleur choix pour un commerce local ou un fabricant.</p>
        <ul>
          <li><strong>Pour qui</strong> : magasin de meubles, fleuriste, caviste, menuisier qui fabrique sur mesure, fournisseur professionnel…</li>
          <li><strong>Avantages</strong> : pas de gestion de stock ni de paiement en ligne, les clients viennent en boutique en sachant ce qu'ils veulent.</li>
          <li><strong>Option utile</strong> : le click &amp; collect (réservation en ligne, paiement et retrait en magasin).</li>
        </ul>

        <h2>Le site e-commerce : vendre et encaisser en ligne</h2>
        <p>Une vraie boutique : panier, paiement sécurisé, gestion des commandes, des stocks et des livraisons. C'est le plus complet, mais aussi celui qui demande le plus de travail au quotidien : photos et fiches produits, expéditions, service client, retours.</p>
        <ul>
          <li><strong>Pour qui</strong> : commerce qui veut vendre au-delà de sa ville, artisan créateur, producteur local…</li>
          <li><strong>À prévoir</strong> : les frais de paiement en ligne, l'emballage et la livraison, les conditions générales de vente, et le temps passé chaque semaine.</li>
          <li><strong>Prix</strong> : sur devis, selon le nombre de produits et les fonctions nécessaires.</li>
        </ul>

        <h2>Le site institutionnel : présenter une organisation</h2>
        <p>Pour une PME, une association, une collectivité ou un organisme, le site sert surtout à présenter la structure : son histoire, ses équipes, ses valeurs, ses actualités, ses documents. Il s'adresse autant aux partenaires, aux candidats et aux financeurs qu'aux clients.</p>
        <ul>
          <li><strong>Pour qui</strong> : PME industrielle, association, centre de formation, cabinet avec plusieurs associés…</li>
          <li><strong>Pages types</strong> : présentation, équipes, actualités, recrutement, documents, contact.</li>
        </ul>

        <h2>Comment choisir ?</h2>
        <div class="article-table-wrap">
          <table class="article-table article-table-2col">
            <thead>
              <tr><th>Votre situation</th><th>Le bon choix</th></tr>
            </thead>
            <tbody>
              <tr><td>Vous vendez un service, on vous appelle pour un devis</td><td><strong>Site vitrine</strong></td></tr>
              <tr><td>Vous avez des dizaines de produits, la vente se fait en magasin</td><td><strong>Site catalogue</strong>, éventuellement avec click &amp; collect</td></tr>
              <tr><td>Vous voulez vendre et expédier dans toute la France</td><td><strong>E-commerce</strong></td></tr>
              <tr><td>Vous présentez une organisation à des publics variés</td><td><strong>Site institutionnel</strong></td></tr>
              <tr><td>Vous hésitez</td><td>Commencer par un <strong>site vitrine</strong> bien fait, et ajouter un catalogue ou la vente plus tard</td></tr>
            </tbody>
          </table>
        </div>

        <h2>Le point commun : être trouvé</h2>
        <p>Quel que soit le type de site, il ne sert à rien s'il n'apparaît pas sur Google. Une page par service ou par catégorie de produits, des textes qui répondent aux questions de vos clients, une fiche Google reliée : le référencement se prévoit dès la création, pas après. C'est inclus dans tous les sites que je réalise, avec ensuite <?= $mensuel ?> € par mois pour l'hébergement, le nom de domaine, les mises à jour et vos modifications.</p>

        <h2>Questions fréquentes</h2>
<?php article_faq([
    ['Peut-on transformer un site vitrine en boutique plus tard ?', 'Oui, à condition que le site soit construit proprement dès le départ. C\'est même souvent la meilleure approche : valider d\'abord que le site attire des visiteurs, puis ajouter la vente en ligne.'],
    ['Un site catalogue doit-il afficher les prix ?', 'Ce n\'est pas obligatoire pour un catalogue de présentation, mais c\'est souvent apprécié des clients. Pour les produits sur mesure, une fourchette ou un « à partir de » suffit.'],
    ['Faut-il un site e-commerce pour faire du click & collect ?', 'Non. Une réservation en ligne avec paiement en magasin se fait très bien sur un site vitrine ou catalogue, sans la lourdeur d\'une boutique complète.'],
]); ?>

<?php
article_fin(
    'Quel site pour votre activité ?',
    'Décrivez votre activité, je vous conseille le format adapté et vous envoie un devis sous 24 h.',
    [['/conseils/prix-site-internet-artisan', 'Prix d\'un site internet pour artisan'], ['/conseils/referencement-local-eure-normandie', 'Référencement local : apparaître sur Google']]
);
