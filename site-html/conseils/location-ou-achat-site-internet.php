<?php
require_once dirname(__DIR__) . '/includes/article.php';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];
$euros = fn(int $n) => number_format($n, 0, ',', ' ') . ' €';

$article = article_debut(
    'location-ou-achat-site-internet',
    'Location ou achat de site internet : les pièges | Pertec',
    'Acheter ou louer son site internet ? <span class="gradient-text">Les pièges de la location</span>',
    7
);
?>

        <p>« Pas de frais de création, juste un abonnement mensuel. » Beaucoup d'artisans et de commerçants ont reçu cet appel. La location de site internet séduit parce qu'elle évite une dépense de départ. Mais sur trois ou quatre ans, la facture est souvent bien plus élevée, et les contrats réservent parfois de mauvaises surprises.</p>
        <p>Voici comment comparer honnêtement les deux formules, et les questions à poser avant de signer.</p>

        <div class="article-answer glass">
          <p class="article-answer-label">En bref</p>
          <p>La location coûte souvent <strong>plus cher sur la durée</strong>, engage sur <strong>24 à 48 mois</strong>, et le site ou le nom de domaine <strong>ne vous appartient pas toujours</strong>. L'achat demande une dépense au départ, mais vous êtes propriétaire et libre de changer de prestataire.</p>
        </div>

        <h2>Le calcul sur 4 ans</h2>
        <p>Prenons un exemple courant : une location à 120 € par mois sur 48 mois, sans frais de départ, face à un site vitrine de 5 pages acheté <?= $euros($offres['vitrine5']['creation']) ?> avec <?= $mensuel ?> € par mois d'hébergement et de suivi.</p>

<?php article_figure('/images/conseils/location-achat-cout-cumule.webp', 1400, 700,
    'Graphique du coût cumulé sur 48 mois : location à 120 € par mois contre achat d\'un site 5 pages avec suivi mensuel',
    'Coût cumulé sur 48 mois, exemple : location à 120 €/mois contre achat avec suivi mensuel. Les écarts varient selon les offres.'); ?>

        <p>Au bout de 4 ans, la location revient à <strong><?= $euros(120 * 48) ?></strong>, l'achat à <strong><?= $euros($offres['vitrine5']['creation'] + $mensuel * 48) ?></strong>. Et dans le premier cas, à la fin du contrat, il faut souvent continuer à payer ou repartir de zéro.</p>
        <p>Les montants varient d'une offre à l'autre : certaines locations sont moins chères, d'autres beaucoup plus. Faites le calcul avec les chiffres de votre contrat : mensualité × nombre de mois, plus les éventuels frais.</p>

        <h2>Les 5 pièges à vérifier dans un contrat de location</h2>
        <ol>
          <li><strong>La durée d'engagement</strong> : 24, 36, 48 mois ? Peut-on résilier avant, et à quel prix ? Certains contrats exigent le paiement de toutes les mensualités restantes.</li>
          <li><strong>Le propriétaire du nom de domaine</strong> : s'il est au nom du prestataire, vous risquez de perdre l'adresse de votre site, et tout son historique sur Google, en partant.</li>
          <li><strong>Le propriétaire du site</strong> : à la fin du contrat, récupérez-vous les textes, les photos et le site lui-même ? Souvent, non.</li>
          <li><strong>La tacite reconduction</strong> : le contrat se renouvelle-t-il automatiquement ? Avec quel préavis pour l'arrêter ?</li>
          <li><strong>Le financement</strong> : certains contrats sont en réalité des contrats de location financière signés avec un organisme tiers. Le prestataire peut disparaître, les mensualités restent dues.</li>
        </ol>
        <p>Les contrats signés par un professionnel pour son activité offrent moins de protections qu'un achat de particulier. Avant de signer un engagement long, faites relire le contrat, et en cas de litige, rapprochez-vous de votre chambre consulaire ou d'un juriste.</p>

        <h2>Quand la location peut avoir du sens</h2>
        <p>Pour être juste : la location n'est pas toujours une mauvaise idée. Elle peut convenir si vous n'avez vraiment aucune trésorerie au départ, si le contrat est court et sans pénalité de sortie, et si le nom de domaine reste à votre nom. Le problème n'est pas l'abonnement en soi, ce sont les engagements longs et la dépendance au prestataire.</p>

        <h2>L'achat chez Pertec</h2>
        <ul>
          <li><strong>Un prix de création fixé à l'avance</strong> : <?= $euros($offres['page_unique']['creation']) ?> pour une page unique, <?= $euros($offres['vitrine5']['creation']) ?> pour 5 pages, <?= $euros($offres['vitrine10']['creation']) ?> pour 10 pages.</li>
          <li><strong><?= $mensuel ?> € par mois, sans engagement de durée</strong>, pour l'hébergement, le nom de domaine, les mises à jour, les sauvegardes et vos modifications.</li>
          <li><strong>Le nom de domaine à votre nom</strong> et le site vous appartient : si vous changez de prestataire, vous partez avec.</li>
        </ul>

        <h2>Questions fréquentes</h2>
<?php article_faq([
    ['J\'ai déjà signé une location, puis-je en sortir ?', 'Tout dépend du contrat : relisez les clauses de durée, de résiliation et de reconduction. Si le nom de domaine est à votre nom, vous pourrez le garder pour un nouveau site. Sinon, il faudra peut-être en prévoir un nouveau, et je peux vous aider à organiser la transition.'],
    ['Peut-on payer un site acheté en plusieurs fois ?', 'C\'est souvent possible : parlons-en au moment du devis pour trouver un échéancier adapté.'],
    ['Que comprend exactement le suivi mensuel ?', 'L\'hébergement, le renouvellement du nom de domaine, les mises à jour de sécurité, les sauvegardes et vos demandes de modification traitées sous 48 h. Sans engagement : vous pouvez arrêter quand vous voulez.'],
]); ?>

<?php
article_fin(
    'Un site à vous, sans engagement',
    'Prix fixé à l\'avance, nom de domaine à votre nom, suivi sans engagement de durée. Devis gratuit sous 24 h.',
    [['/conseils/prix-site-internet-artisan', 'Prix d\'un site internet pour artisan'], ['/conseils/site-vitrine-catalogue-ecommerce', 'Site vitrine, catalogue ou e-commerce ?']]
);
