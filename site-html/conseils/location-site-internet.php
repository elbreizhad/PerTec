<?php
require_once dirname(__DIR__) . '/includes/article.php';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];
$euros = fn(int $n) => number_format($n, 0, ',', ' ') . ' €';

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
        <p>Les formules sans frais de départ, autour de 100 à 150 € par mois engagés sur 4 ans, sont courantes. Une autre approche consiste à payer la création une fois, puis un abonnement mensuel bas pour l'hébergement et le suivi. Exemple avec un site de 5 pages :</p>

<?php article_figure('/images/conseils/location-site-cout-4-ans.webp', 1400, 700,
    'Graphique du coût cumulé sur 48 mois : location classique à 120 € par mois contre formule Pertec, création de 990 € puis 39 € par mois',
    'Coût cumulé sur 48 mois, exemple : location classique à 120 €/mois contre formule Pertec. Les écarts varient selon les offres.'); ?>

        <p>Au bout de 4 ans, la location classique revient à <strong><?= $euros(120 * 48) ?></strong>, la formule Pertec à <strong><?= $euros($offres['vitrine5']['creation'] + $mensuel * 48) ?></strong>, avec le même service au quotidien. Pour comparer deux offres, faites simplement le calcul : frais de départ + mensualité × nombre de mois d'engagement.</p>

        <h2>La formule Pertec : je m'occupe de tout</h2>
        <p>J'héberge moi-même les sites que je crée. Une fois votre site en ligne, vous n'avez plus à vous en soucier :</p>
        <ul>
          <li><strong><?= $mensuel ?> € par mois, tout compris</strong> : hébergement, nom de domaine, mises à jour de sécurité, sauvegardes, et vos modifications faites sous 48 h.</li>
          <li><strong>Sans engagement de durée</strong> : vous restez parce que le service vous convient, pas à cause d'un contrat.</li>
          <li><strong>Le nom de domaine à votre nom</strong> : votre site vous appartient, vous partez avec si vous le souhaitez.</li>
          <li><strong>Un prix de création fixé à l'avance</strong> : <?= $euros($offres['page_unique']['creation']) ?> pour une page unique, <?= $euros($offres['vitrine5']['creation']) ?> pour 5 pages, <?= $euros($offres['vitrine10']['creation']) ?> pour 10 pages.</li>
          <li><strong>Un seul interlocuteur</strong>, basé dans l'Eure, qui connaît votre site et votre activité.</li>
        </ul>

        <h2>Questions fréquentes</h2>
<?php article_faq([
    ['Que comprend exactement l\'abonnement mensuel ?', 'L\'hébergement, le renouvellement du nom de domaine, les mises à jour de sécurité, les sauvegardes et vos demandes de modification traitées sous 48 h. Sans engagement : vous pouvez arrêter quand vous voulez.'],
    ['J\'ai déjà un site en location ailleurs, pouvez-vous le reprendre ?', 'Tout dépend de votre contrat actuel : durée d\'engagement restante et propriétaire du nom de domaine. Envoyez-moi les informations, je vous dis ce qui est possible et je peux organiser la transition pour que votre site ne soit jamais hors ligne.'],
    ['Pourquoi y a-t-il des frais de création ?', 'Payer la création une fois permet de garder un abonnement mensuel bas et sans engagement long. Sur la durée, c\'est en général plus économique qu\'une formule sans frais de départ engagée sur plusieurs années.'],
]); ?>

<?php
article_fin(
    'Un site dont vous n\'avez plus à vous occuper',
    'Hébergement, sécurité, modifications : je m\'occupe de tout pour ' . $mensuel . ' € par mois, sans engagement. Devis gratuit sous 24 h.',
    [['/conseils/prix-site-internet-artisan', 'Prix d\'un site internet pour artisan'], ['/conseils/site-vitrine-catalogue-ecommerce', 'Site vitrine, catalogue ou e-commerce ?']]
);
