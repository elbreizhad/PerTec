<?php
require_once dirname(__DIR__) . '/includes/article.php';

$article = article_debut(
    'obtenir-avis-google',
    'Obtenir des avis Google quand on est artisan | Pertec',
    'Avis Google : comment en obtenir <span class="gradient-text">quand on est artisan ou commerçant</span>',
    6
);
?>

        <p>Avant d'appeler un artisan ou de pousser la porte d'un commerce qu'ils ne connaissent pas, la plupart des gens regardent ses avis Google. Une note correcte avec des avis récents rassure. Une fiche sans avis fait hésiter, même si votre travail est excellent.</p>
        <p>Les avis comptent aussi pour Google lui-même : ils font partie des critères qui décident quelles entreprises apparaissent dans la carte des résultats locaux. Voici comment en obtenir régulièrement, sans tricher.</p>

        <div class="article-answer glass">
          <p class="article-answer-label">La méthode en une phrase</p>
          <p><strong>Demandez</strong>, au bon moment, avec <strong>un lien direct</strong>, à <strong>chaque client satisfait</strong>, et <strong>répondez à tous les avis</strong>, y compris les négatifs.</p>
        </div>

        <h2>1. Récupérez votre lien direct</h2>
        <p>Dans votre fiche Google Business Profile, l'option « Demander des avis » vous donne un lien court. Il ouvre directement la fenêtre de notation, sans que le client ait à chercher votre entreprise. C'est le détail qui change tout : moins il y a d'étapes, plus les clients laissent un avis.</p>
        <p>Gardez ce lien sous la main : dans vos SMS, en signature d'e-mail, sur vos factures, et sous forme de QR code sur un petit carton ou au comptoir.</p>

        <h2>2. Demandez au bon moment</h2>
        <p>Le meilleur moment, c'est quand le client est content : à la fin du chantier quand tout fonctionne, au moment où il récupère sa commande, juste après un rendez-vous réussi. Une demande envoyée trois semaines plus tard a beaucoup moins de chances d'aboutir.</p>
        <p>Un message simple et personnel fonctionne mieux qu'un message générique :</p>
        <div class="article-answer glass">
          <p>« Bonjour Madame Martin, merci encore pour votre confiance pour la salle de bains. Si vous êtes satisfaite, est-ce que vous pourriez laisser un petit avis sur Google ? Ça m'aide beaucoup à me faire connaître. Voici le lien : … Bonne journée ! »</p>
        </div>

        <h2>3. Faites-en une habitude</h2>
        <p>Les avis récents comptent plus que les anciens. Mieux vaut un avis par semaine pendant un an que vingt avis d'un coup puis plus rien. Ajoutez la demande d'avis à votre routine de fin de chantier ou de vente, comme la facture.</p>

        <h2>4. Répondez à tous les avis</h2>
        <ul>
          <li><strong>Avis positif</strong> : un merci personnalisé, en reprenant un détail du travail réalisé.</li>
          <li><strong>Avis négatif</strong> : restez calme et professionnel, reconnaissez ce qui peut l'être, proposez d'en parler directement. Les futurs clients lisent surtout votre réponse.</li>
          <li><strong>Avis injuste ou faux</strong> : Google permet de signaler un avis qui ne respecte pas ses règles (avis d'un non-client, propos injurieux…).</li>
        </ul>

        <h2>Ce qu'il ne faut jamais faire</h2>
        <ul>
          <li><strong>Acheter des avis</strong> ou en faire écrire par des proches : c'est interdit, Google les détecte et les supprime, et une fiche peut être sanctionnée.</li>
          <li><strong>Offrir une remise ou un cadeau en échange d'un avis</strong> : Google l'interdit, même pour un avis honnête.</li>
          <li><strong>Ne demander qu'aux clients dont vous êtes sûr qu'ils seront positifs</strong> en filtrant les autres par un questionnaire préalable : cette pratique est aussi contraire aux règles de Google.</li>
        </ul>

        <h2>Et votre site dans tout ça ?</h2>
        <p>Les avis Google et votre site se renforcent : la fiche donne envie de cliquer, le site convainc. Vous pouvez reprendre quelques avis sur votre site (avec un lien vers votre fiche), et votre site doit renvoyer vers votre fiche pour faciliter les nouveaux avis. Dans les sites que je crée, la fiche Google est reliée dès le départ.</p>

        <h2>Questions fréquentes</h2>
<?php article_faq([
    ['Combien d\'avis faut-il pour être bien placé ?', 'Il n\'y a pas de chiffre magique : cela dépend de vos concurrents dans votre ville. L\'important est d\'en avoir régulièrement, avec une bonne note, et plus que la moyenne de votre secteur local.'],
    ['Peut-on supprimer un avis négatif ?', 'Vous ne pouvez pas supprimer un avis vous-même. Vous pouvez le signaler s\'il ne respecte pas les règles de Google (faux client, propos injurieux), et surtout y répondre de façon professionnelle.'],
    ['Faut-il aussi des avis sur d\'autres sites ?', 'Les avis Google sont les plus visibles pour une entreprise locale. Selon votre métier, d\'autres plateformes peuvent compter (par exemple pour la restauration ou l\'hébergement), mais commencez par Google.'],
]); ?>

<?php
article_fin(
    'Fiche Google, avis et site qui travaillent ensemble',
    'Je crée des sites reliés à votre fiche Google, pensés pour transformer les visites en demandes. Devis gratuit sous 24 h.',
    [['/conseils/referencement-local-eure-normandie', 'Référencement local : apparaître sur Google'], ['/conseils/prix-site-internet-artisan', 'Prix d\'un site internet pour artisan']]
);
