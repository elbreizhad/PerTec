<?php
require_once dirname(__DIR__) . '/includes/article.php';

$tarifs = require dirname(__DIR__) . '/config/tarifs.php';
$offres = $tarifs['type_site']['options'];
$mensuel = $tarifs['suivi']['inclus']['mensuel'];
$euros = fn(int $n) => number_format($n, 0, ',', ' ') . ' €';

$article = article_debut(
    'site-internet-centre-formation-securite',
    'Site internet pour centre de formation sécurité | Pertec',
    'Site internet pour un centre de formation <span class="gradient-text">en sécurité</span> : SSIAP, CPF et Qualiopi',
    7
);
?>

        <p>Un centre de formation aux métiers de la sécurité ne recrute pas ses stagiaires comme une entreprise trouve ses clients. Ses visiteurs sont des demandeurs d'emploi, des salariés en reconversion, des agents qui doivent renouveler leur carte professionnelle, des entreprises qui forment leurs équipes. Tous cherchent la même chose : la bonne formation, à la bonne date, avec un financement possible.</p>
        <p>J'ai créé le site de <strong>CFSSIS</strong>, centre de formation en sécurité privée, sécurité incendie et secourisme. Voici ce qu'un site de centre de formation doit faire, et ce qui fait la différence face aux grands organismes nationaux.</p>

        <div class="article-answer glass">
          <p class="article-answer-label">L'essentiel</p>
          <p>Un <strong>catalogue clair</strong> (une page par formation), <strong>les prochaines sessions à jour</strong>, <strong>les solutions de financement</strong> expliquées simplement, <strong>les informations attendues par Qualiopi</strong>, et une <strong>demande d'inscription</strong> en quelques clics.</p>
        </div>

        <h2>Ce que cherchent vos futurs stagiaires</h2>
        <p>Les recherches sont très concrètes : « formation SSIAP 1 Rouen », « recyclage SSIAP 2 », « formation agent de sécurité CPF », « carte professionnelle agent de sécurité formation », « SST Évreux ». Derrière chaque recherche, une personne qui veut savoir trois choses : où, quand, et comment la payer.</p>
        <p>Un site qui répond à ces trois questions sur chaque page de formation a toutes les chances de transformer la visite en demande d'inscription.</p>

        <h2>1. Une page par formation</h2>
        <p>Chaque formation mérite sa page : SSIAP 1, 2 et 3, recyclages et remises à niveau, formation d'agent de prévention et de sécurité, maintien et actualisation des compétences, secourisme (SST)… Sur chacune :</p>
        <ul>
          <li>objectifs et débouchés, en mots simples ;</li>
          <li>durée, prérequis et conditions d'accès ;</li>
          <li>programme et modalités d'évaluation ;</li>
          <li>prix et financements possibles ;</li>
          <li>prochaines dates et lieu ;</li>
          <li>un bouton de demande d'inscription.</li>
        </ul>
        <p>C'est aussi ce qui fait le référencement : une page « Formation SSIAP 1 » détaillée sera bien mieux classée qu'une simple ligne dans une liste.</p>

        <h2>2. Les sessions à jour</h2>
        <p>La page des prochaines sessions est souvent la plus consultée. Une session passée encore affichée donne l'impression d'un centre à l'abandon. Il faut pouvoir la mettre à jour facilement, ou me l'envoyer pour une mise à jour sous 48 h.</p>

        <h2>3. Le financement expliqué simplement</h2>
        <p>Pour beaucoup de stagiaires, c'est la question décisive. Le site doit expliquer clairement les possibilités selon la situation : compte personnel de formation (pour les formations éligibles), France Travail pour les demandeurs d'emploi, OPCO et plan de développement des compétences pour les salariés, financement personnel. Pour les formations proposées sur Mon Compte Formation, un lien direct vers la fiche simplifie l'inscription.</p>

        <h2>4. Les informations attendues par Qualiopi</h2>
        <p>La certification Qualiopi conditionne l'accès aux financements publics et mutualisés. Son référentiel demande de rendre accessibles certaines informations : conditions d'accès, délais, tarifs, modalités d'évaluation, accessibilité aux personnes en situation de handicap, indicateurs de résultats. Le site est l'endroit naturel pour les publier, et un auditeur appréciera de les trouver facilement. Pour la liste exacte, référez-vous au référentiel national qualité en vigueur.</p>

        <h2>5. L'autorisation CNAPS</h2>
        <p>Les organismes qui forment aux activités privées de sécurité doivent être autorisés par le CNAPS. Afficher ce numéro rassure les stagiaires comme les entreprises qui envoient leurs salariés.</p>

        <h2>Se démarquer des grands organismes</h2>
        <p>Les réseaux nationaux ont des budgets publicitaires importants. Un centre local a d'autres atouts, à mettre en avant sur son site :</p>
        <ul>
          <li><strong>la proximité</strong> : des pages pour les villes de votre secteur, l'accès, le stationnement ;</li>
          <li><strong>les formateurs</strong> : leur parcours, leur expérience terrain ;</li>
          <li><strong>les résultats</strong> : vos taux de réussite et de satisfaction réels ;</li>
          <li><strong>le lien avec les employeurs locaux</strong> : entreprises partenaires, débouchés dans la région ;</li>
          <li><strong>la réactivité</strong> : réponse rapide, sessions fréquentes, accompagnement pour le financement.</li>
        </ul>

        <h2>Combien coûte un site de centre de formation ?</h2>
        <p>Le nombre de pages dépend surtout du catalogue. Un petit centre avec quelques formations peut démarrer avec un site de 5 pages (<?= $euros($offres['vitrine5']['creation']) ?>), un catalogue plus large avec 10 pages ou plus (<?= $euros($offres['vitrine10']['creation']) ?> pour 10 pages), puis <?= $mensuel ?> € par mois pour l'hébergement, les mises à jour et vos modifications, y compris les sessions. Le plus simple est d'en parler : je vous fais une proposition adaptée à votre catalogue.</p>

        <h2>Questions fréquentes</h2>
<?php article_faq([
    ['Peut-on afficher un calendrier des sessions qui se met à jour ?', 'Oui. Selon votre fonctionnement, les sessions peuvent être mises à jour par vos soins dans un espace simple, ou envoyées par e-mail pour une mise à jour sous 48 h dans le cadre du suivi mensuel.'],
    ['Le site peut-il gérer les inscriptions ?', 'Le site recueille des demandes d\'inscription complètes (formation, situation, financement envisagé), que vous recevez par e-mail. Pour les formations sur Mon Compte Formation, l\'inscription se finalise sur la plateforme officielle.'],
    ['Avez-vous déjà travaillé avec des centres de formation ?', 'Oui, j\'ai créé le site de CFSSIS, centre de formation en sécurité privée, sécurité incendie et secourisme, ainsi que plusieurs sites d\'entreprises de sécurité. Je connais donc le vocabulaire et les attentes du secteur.'],
]); ?>

<?php
article_fin(
    'Un site pour votre centre de formation',
    'Envoyez-moi votre catalogue de formations : je vous propose une structure de site et un devis sous 24 h.',
    [['/conseils/site-internet-entreprise-securite-privee', 'Site internet pour entreprise de sécurité privée'], ['/secteurs/securite-privee', 'Mes réalisations dans la sécurité']]
);
