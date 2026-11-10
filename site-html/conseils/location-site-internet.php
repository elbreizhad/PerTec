<?php
require_once dirname(__DIR__) . '/includes/article.php';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$formules = $tarifs['formule']['options'];
$engagement = $tarifs['formule']['engagement'];
$maintenance = $tarifs['maintenance']['mensuel'];
$vitrine = $formules['vitrine']['mensuel'];
$complet = $formules['complet']['mensuel'];

$article = article_debut(
    'location-site-internet',
    'Location de site internet : bien choisir sa formule | Pertec',
    'Location de site internet : <span class="gradient-text">bien choisir sa formule</span>',
    7
);
?>

        <p>Un site internet, ce n'est pas seulement des pages à créer. Une fois en ligne, il faut l'héberger, renouveler le nom de domaine, faire les mises à jour de sécurité, sauvegarder, corriger un horaire, ajouter une photo, changer un tarif… C'est pour cela que de plus en plus d'artisans et de commerçants choisissent une formule mensuelle : ils paient chaque mois, et quelqu'un s'occupe de tout.</p>
        <p>C'est une très bonne solution, à condition de bien choisir son prestataire. Voici ce que doit comprendre une bonne formule, et les points à vérifier avant de signer.</p>

        <div class="article-answer glass">
          <p class="article-answer-label">En bref</p>
          <p>Une bonne formule de location ou d'abonnement comprend <strong>l'hébergement, le nom de domaine, la sécurité, les sauvegardes et vos modifications</strong>. Elle laisse <strong>le nom de domaine à votre nom</strong> et ne vous enferme pas dans <strong>un engagement de plusieurs années</strong>.</p>
        </div>

        <h2>Pourquoi payer son site au mois ?</h2>
        <ul>
          <li><strong>Vous n'avez rien à gérer</strong> : pas d'hébergeur à choisir, pas de mises à jour, pas de renouvellement oublié qui met votre site hors ligne.</li>
          <li><strong>Votre site reste à jour</strong> : vous envoyez un message, la modification est faite. Un site qui affiche les bons horaires et les dernières réalisations inspire confiance, à vos clients comme à Google.</li>
          <li><strong>Votre site reste sûr</strong> : un site abandonné, jamais mis à jour, finit souvent piraté ou bloqué par les navigateurs.</li>
          <li><strong>Un budget prévisible</strong> : un montant fixe chaque mois, sans facture surprise quand quelque chose doit être réparé.</li>
          <li><strong>Un interlocuteur qui connaît votre site</strong> : pas besoin de tout réexpliquer à chaque demande.</li>
        </ul>

        <h2>Ce que doit comprendre une bonne formule</h2>
        <ol>
          <li><strong>L'hébergement</strong>, rapide et sécurisé (HTTPS).</li>
          <li><strong>Le nom de domaine</strong>, renouvelé chaque année.</li>
          <li><strong>Les mises à jour de sécurité</strong> et la surveillance du site.</li>
          <li><strong>Les sauvegardes</strong>, pour pouvoir revenir en arrière en cas de problème.</li>
          <li><strong>Vos modifications</strong> : textes, photos, horaires, tarifs. Vérifiez si elles sont incluses, en quelle quantité et sous quel délai.</li>
        </ol>
        <p>Si l'une de ces lignes est facturée en plus, ou absente, comparez avec attention : une mensualité basse peut cacher des frais à chaque demande.</p>

        <h2>Les 4 points à vérifier avant de signer</h2>
        <ol>
          <li><strong>La durée d'engagement</strong> : certains contrats engagent sur 36 ou 48 mois, avec toutes les mensualités restantes dues en cas d'arrêt. Préférez une formule sans engagement long : un prestataire qui fait bien son travail n'a pas besoin de vous retenir par contrat.</li>
          <li><strong>Le propriétaire du nom de domaine</strong> : il doit être à votre nom. C'est votre adresse sur internet et tout l'historique de votre site sur Google.</li>
          <li><strong>Ce qui se passe si vous arrêtez</strong> : récupérez-vous votre site, vos textes et vos photos ?</li>
          <li><strong>Le contrat lui-même</strong> : certaines offres sont en réalité des contrats de location financière signés avec un organisme tiers. Si le prestataire disparaît, les mensualités restent dues. Lisez qui encaisse vos paiements.</li>
        </ol>

        <h2>Le coût sur la durée</h2>
        <p>Les formules sans frais de départ, autour de 100 à 150 € par mois engagés sur 4 ans, sont courantes chez les démarcheurs. Une autre approche (la mienne) : un abonnement transparent avec un engagement raisonnable de 24 mois, puis le site vous appartient. Comparons :</p>

<?php article_figure('/images/conseils/location-site-cout-4-ans.webp', 1400, 700,
    'Graphique du coût cumulé sur 48 mois : location classique à 120 € par mois contre formule Pertec à 59 € par mois',
    'Coût cumulé sur 48 mois, exemple : location classique à 120 €/mois contre formule Pertec. Les écarts varient selon les offres.'); ?>

        <p>Au bout de 4 ans : location classique (120 €/mois × 48) = <strong>5 760 €</strong> et le site ne vous appartient toujours pas. Formule Pertec : <strong><?= number_format($vitrine * 24 + $maintenance * 24, 0, ',', ' ') ?> €</strong> (24 mois à <?= $vitrine ?> € puis 24 mois de maintenance à <?= $maintenance ?> €), et le site est transférable dès la fin de l'engagement.</p>

        <h2>La formule Pertec : je m'occupe de tout</h2>
        <p>J'héberge moi-même les sites que je crée. Une fois votre site en ligne, vous n'avez plus à vous en soucier :</p>
        <ul>
          <li><strong>À partir de <?= $vitrine ?> €/mois, tout compris</strong> : hébergement, nom de domaine, mises à jour de sécurité, sauvegardes, et vos modifications faites sous 48 h.</li>
          <li><strong>Engagement de 24 mois</strong> : durée raisonnable, beaucoup plus courte que les 36 ou 48 mois des démarcheurs.</li>
          <li><strong>Le site vous est transféré en fin d'engagement</strong> : il vous appartient, vous pouvez le confier à un autre prestataire ou continuer avec moi pour <?= $maintenance ?> €/mois.</li>
          <li><strong>Le nom de domaine à votre nom</strong> : votre adresse internet vous appartient dès le départ.</li>
          <li><strong>Un seul interlocuteur</strong>, basé dans l'Eure, qui connaît votre site et votre activité.</li>
        </ul>

        <h2>Questions fréquentes</h2>
<?php article_faq([
    ['Que comprend exactement l\'abonnement mensuel ?', 'L\'hébergement, le renouvellement du nom de domaine, les mises à jour de sécurité, les sauvegardes et vos demandes de modification traitées sous 48 h. Engagement de 24 mois, puis maintenance optionnelle à ' . $maintenance . ' €/mois ou transfert du site.'],
    ['J\'ai déjà un site en location ailleurs, pouvez-vous le reprendre ?', 'Tout dépend de votre contrat actuel : durée d\'engagement restante et propriétaire du nom de domaine. Envoyez-moi les informations, je vous dis ce qui est possible et je peux organiser la transition pour que votre site ne soit jamais hors ligne.'],
    ['Pourquoi un engagement de 24 mois ?', 'C\'est le temps de créer un site de qualité, de travailler le référencement et de voir les premiers résultats. En contrepartie, pas de frais de création et un prix mensuel raisonnable. Et à la fin, le site vous appartient.'],
]); ?>

<?php
article_fin(
    'Un site dont vous n\'avez plus à vous occuper',
    'Hébergement, sécurité, modifications : je m\'occupe de tout à partir de ' . $vitrine . ' €/mois. Devis gratuit sous 24 h.',
    [['/conseils/prix-site-internet-artisan', 'Prix d\'un site internet pour artisan'], ['/conseils/site-vitrine-catalogue-ecommerce', 'Site vitrine, catalogue ou e-commerce ?']]
);
