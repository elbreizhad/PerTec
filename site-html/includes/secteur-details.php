<?php
/**
 * Contenu propre à chaque page secteur - Pertec.fr
 * Inclus avant l'appel à l'action final de secteurs/<slug>.php :
 *   <?php require dirname(__DIR__) . '/includes/secteur-details.php'; ?>
 * Le secteur est déduit de $canonicalUrl. Les prix sont lus dans config/tarifs.php.
 *
 * Pour chaque secteur :
 *  - 'recherches' : ce que les clients tapent sur Google (exemples) ;
 *  - 'intro'      : pourquoi ces recherches comptent pour ce métier ;
 *  - 'pages'      : les pages indispensables, [titre, explication] ;
 *  - 'formule'    : formule conseillée (vitrine, complet, ecommerce) et pourquoi ;
 *  - 'faq'        : questions fréquentes propres au secteur (facultatif) ;
 *  - 'lien'       : article de conseils à lire ensuite (facultatif).
 */

$secteurSlug = basename((string) ($canonicalUrl ?? ''));
$tarifsSecteur = require dirname(__DIR__) . '/config/tarifs.php';
$formulesSecteur = $tarifsSecteur['formule']['options'];

$secteurs = [
    'artisan' => [
        'nom' => 'artisan',
        'recherches' => ['plombier Évreux', 'électricien Vernon', 'menuisier sur mesure Eure', 'couvreur Louviers', 'maçon rénovation', 'dépannage chauffage'],
        'intro' => 'Vos clients tapent rarement le nom de votre entreprise : ils cherchent un métier et une ville, souvent depuis leur téléphone et parfois dans l\'urgence. Celui qui apparaît avec un numéro cliquable, des photos de chantiers et une zone d\'intervention claire reçoit l\'appel.',
        'pages' => [
            ['Une page par prestation', 'Dépannage, installation, rénovation : chaque service répond à une recherche différente sur Google.'],
            ['Vos réalisations', 'Des photos avant et après, classées par type de travaux. C\'est ce qui rassure le plus.'],
            ['Votre zone d\'intervention', 'Les communes où vous vous déplacez réellement, avec vos délais habituels.'],
            ['Une demande de devis guidée', 'Type de travaux, adresse, photos : vous recevez des demandes complètes, prêtes à chiffrer.'],
        ],
        'metiers' => [
            ['electricien', 'Électricien', 'Rénovation, mise aux normes, dépannage, borne de recharge, domotique : une page par intervention, avec vos qualifications. C\'est ce qui permet de ressortir sur « électricien » et sur chaque besoin précis.'],
            ['macon', 'Maçon, carreleur, peintre', 'Les clients comparent les finitions : une galerie de chantiers récents, le type de travaux acceptés (neuf, rénovation, extérieur) et vos délais font la différence.'],
            ['menuisier', 'Menuisier, charpentier, couvreur', 'Escaliers, cuisines, fenêtres, toitures : des photos bien classées et une demande de devis avec dimensions ou photos du projet.'],
        ],
        'formule' => ['vitrine', 'Un site vitrine de 5 pages suffit à la plupart des artisans. Le site complet devient utile avec plusieurs métiers ou une page par ville.'],
        'lien' => ['/conseils/prix-site-internet-artisan', 'Combien coûte un site internet pour un artisan ?'],
    ],
    'plombier' => [
        'nom' => 'plombier',
        'recherches' => ['plombier Évreux', 'fuite d\'eau urgence', 'remplacement chauffe-eau', 'débouchage canalisation', 'rénovation salle de bains', 'plombier chauffagiste Vernon'],
        'intro' => 'La plupart des appels arrivent dans l\'urgence, depuis un téléphone : une fuite, plus d\'eau chaude, un WC bouché. Le client appelle le premier plombier qui lui inspire confiance et qui intervient chez lui. Votre site doit répondre à ces trois questions en quelques secondes : intervenez-vous dans ma commune, quand, et comment vous joindre.',
        'pages' => [
            ['Dépannage et urgences', 'Numéro en grand et cliquable, délais d\'intervention, ce que vous traitez en urgence.'],
            ['Installation et rénovation', 'Chauffe-eau, salle de bains, chauffage : les chantiers plus importants méritent leur propre page.'],
            ['Zone d\'intervention', 'Les communes couvertes, pour apparaître sur « plombier + ville ».'],
            ['Avis et réalisations', 'Quelques chantiers en photo et vos avis Google pour rassurer avant l\'appel.'],
        ],
        'formule' => ['vitrine', 'Le site vitrine couvre l\'essentiel. Le site complet permet une page par service et par ville, utile sur un marché concurrentiel.'],
        'faq' => [
            ['Faut-il afficher mes tarifs de dépannage ?', 'Ce n\'est pas obligatoire sur le site, mais indiquer un prix de déplacement ou une fourchette rassure et évite les appels qui n\'aboutissent pas. Les prix précis restent sur le devis.'],
            ['Je suis RGE, est-ce utile de le mettre en avant ?', 'Oui. Une page sur les travaux éligibles aux aides à la rénovation (chauffage, eau chaude) attire des demandes plus importantes que le simple dépannage.'],
            ['Je n\'ai pas le temps de m\'occuper du site.', 'C\'est prévu : je rédige les textes à partir d\'un échange de 30 minutes, puis vous m\'envoyez vos photos de chantier par SMS ou e-mail quand vous voulez, je les ajoute.'],
        ],
        'lien' => ['/conseils/prix-site-internet-artisan', 'Prix d\'un site internet de plombier ou chauffagiste'],
    ],
    'restaurant' => [
        'nom' => 'restaurant',
        'recherches' => ['restaurant Évreux', 'restaurant ouvert dimanche', 'pizzeria Vernon', 'restaurant Giverny', 'menu du jour', 'restaurant groupe anniversaire'],
        'intro' => 'Avant de choisir où manger, on regarde la carte, les photos, les horaires et les avis, très souvent sur téléphone et peu de temps avant de partir. Un restaurant dont la carte est à jour et lisible, sans PDF à télécharger, gagne la comparaison.',
        'pages' => [
            ['La carte en texte', 'Lisible sur téléphone et par Google, contrairement à une photo ou un PDF. Le PDF peut rester en complément.'],
            ['Horaires, accès et parking', 'Les jours de fermeture et les horaires exceptionnels, à jour : c\'est la première chose qu\'on vérifie.'],
            ['Réservation', 'Un formulaire, un bouton d\'appel ou votre outil de réservation habituel, accessible partout sur le site.'],
            ['Groupes et privatisation', 'Anniversaires, repas d\'entreprise, séminaires : une page dédiée attire des demandes à plus forte valeur.'],
        ],
        'formule' => ['vitrine', 'Le site vitrine suffit pour la carte, les horaires et la réservation. Le site complet ajoute plus de pages (événements, traiteur, galerie).'],
        'faq' => [
            ['Ma carte change souvent, qui la met à jour ?', 'Vous m\'envoyez la nouvelle carte, je la mets en ligne sous 48 h : c\'est inclus dans l\'abonnement. Pour un menu du jour qui change tous les jours, on en parle au devis pour prévoir la solution la plus simple pour vous.'],
            ['J\'utilise déjà une plateforme de réservation, est-ce compatible ?', 'Oui. Le site peut renvoyer vers votre outil actuel ou l\'intégrer, et votre fiche Google peut pointer vers la même page de réservation.'],
            ['Faut-il un site si j\'ai déjà une page Facebook et une fiche Google ?', 'Ils se complètent. Le site est le seul endroit que vous maîtrisez entièrement : carte complète, photos, privatisation, et il aide la fiche Google à mieux se classer.'],
        ],
    ],
    'commerces' => [
        'nom' => 'commerce',
        'recherches' => ['fleuriste Évreux', 'épicerie fine Vernon', 'caviste Louviers', 'boutique cadeaux', 'magasin ouvert dimanche', 'click and collect'],
        'intro' => 'Beaucoup de clients vérifient en ligne avant de se déplacer : le commerce est-il ouvert, a-t-il ce que je cherche, puis-je réserver ? Un site qui montre vos produits phares et vos horaires à jour transforme ces recherches en visites en magasin.',
        'pages' => [
            ['Vos produits ou rayons', 'Un aperçu clair de ce que vous vendez, avec photos, sans forcément tout mettre en ligne.'],
            ['Horaires et accès', 'Y compris les horaires exceptionnels et les fêtes, là où les commerces de proximité font la différence.'],
            ['Click & collect', 'Réservation en ligne, paiement et retrait en boutique : sans la lourdeur d\'une boutique complète.'],
            ['Nouveautés et événements', 'Arrivages, dégustations, fêtes des mères : de bonnes raisons de revenir.'],
        ],
        'formule' => ['complet', 'Le site complet permet un catalogue et le click & collect. Pour vendre et expédier, la formule e-commerce est la bonne.'],
        'lien' => ['/conseils/site-vitrine-catalogue-ecommerce', 'Site vitrine, catalogue ou e-commerce : que choisir ?'],
    ],
    'professions-liberales' => [
        'nom' => 'cabinet',
        'recherches' => ['avocat droit de la famille Évreux', 'kinésithérapeute Vernon', 'expert-comptable Louviers', 'ostéopathe rendez-vous', 'architecte Eure', 'notaire'],
        'intro' => 'Pour une profession libérale, le site sert surtout à rassurer : qui vous êtes, dans quels domaines vous intervenez, comment se passe un premier rendez-vous et comment vous joindre. Les recherches portent souvent sur une spécialité précise, d\'où l\'intérêt d\'une page par domaine.',
        'pages' => [
            ['Vos domaines d\'intervention', 'Une page par spécialité, pour répondre aux recherches précises de vos futurs clients ou patients.'],
            ['Le cabinet et l\'équipe', 'Parcours, formations, photos du cabinet : la confiance se joue là.'],
            ['Honoraires et déroulé', 'Comment se passe un premier rendez-vous, ce qu\'il faut apporter, les honoraires ou leur principe.'],
            ['Prise de rendez-vous', 'Un lien vers votre agenda en ligne, ou un formulaire et un numéro cliquable.'],
        ],
        'formule' => ['vitrine', 'Le site vitrine convient à la plupart des cabinets. Le site complet ajoute une page par domaine et des articles.'],
    ],
    'eglise' => [
        'nom' => 'paroisse',
        'recherches' => ['horaires messe Évreux', 'baptême paroisse', 'mariage à l\'église', 'catéchisme inscription', 'obsèques église', 'paroisse Vernon'],
        'intro' => 'Les recherches les plus fréquentes sont très pratiques : l\'horaire de la prochaine messe, comment préparer un baptême ou un mariage, qui contacter pour des obsèques. Un site clair, à jour, évite des dizaines d\'appels au secrétariat et accueille ceux qui ne connaissent pas encore la paroisse.',
        'pages' => [
            ['Horaires des célébrations', 'Messes, permanences, horaires d\'été, à jour et lisibles sur téléphone.'],
            ['Sacrements et démarches', 'Baptême, mariage, obsèques : les étapes, les délais et la personne à contacter.'],
            ['Agenda et vie de la paroisse', 'Événements, catéchisme, aumônerie, chorale, groupes.'],
            ['Soutenir la paroisse', 'Un lien vers la plateforme de dons ou de denier utilisée par votre diocèse.'],
        ],
        'formule' => ['vitrine', 'Le site vitrine suffit pour une paroisse. Le site complet convient à un ensemble de clochers avec une page par église.'],
        'faq' => [
            ['Qui met à jour les horaires ?', 'Vous m\'envoyez les changements par e-mail, je les mets en ligne sous 48 h. On peut aussi prévoir une page simple que le secrétariat met à jour lui-même.'],
            ['Peut-on recevoir des dons en ligne ?', 'Oui, en renvoyant vers la plateforme utilisée par votre diocèse ou votre association, avec un bouton visible sur le site.'],
        ],
    ],
    'pompes-funebres' => [
        'nom' => 'entreprise de pompes funèbres',
        'recherches' => ['pompes funèbres Évreux', 'avis de décès Vernon', 'organiser des obsèques', 'prix obsèques', 'contrat obsèques', 'crématorium Eure'],
        'intro' => 'Les familles cherchent souvent dans l\'urgence et l\'émotion, parfois la nuit. Elles ont besoin d\'un numéro joignable, d\'explications simples sur les démarches et d\'une idée des prix. Un site sobre et clair aide à choisir sereinement.',
        'pages' => [
            ['Joindre l\'entreprise', 'Votre numéro et vos permanences, visibles en haut de chaque page.'],
            ['Les démarches après un décès', 'Étape par étape, sans jargon : ce que vous prenez en charge.'],
            ['Prestations et tarifs', 'Inhumation, crémation, contrats obsèques, avec vos devis types présentés clairement.'],
            ['Avis de décès', 'Publiés avec l\'accord des familles, avec un espace de condoléances modéré si vous le souhaitez.'],
        ],
        'formule' => ['complet', 'Le site complet permet les avis de décès et plusieurs agences. Une seule agence sans avis en ligne peut démarrer en vitrine.'],
        'faq' => [
            ['Les condoléances en ligne sont-elles modérées ?', 'Oui, chaque message est validé avant publication, et un avis peut être retiré à tout moment à la demande de la famille.'],
            ['Faut-il afficher les prix ?', 'Présenter clairement vos devis types et le principe de vos tarifs rassure les familles, qui comparent souvent plusieurs entreprises.'],
        ],
    ],
    'auto-ecole' => [
        'nom' => 'auto-école',
        'recherches' => ['auto-école Évreux', 'permis accéléré', 'conduite accompagnée', 'permis moto Vernon', 'code de la route', 'permis boîte automatique'],
        'intro' => 'Les futurs élèves et leurs parents comparent les tarifs, les formules, les résultats et la facilité d\'inscription. Une auto-école qui affiche clairement ses forfaits, ses financements et un formulaire d\'inscription simple capte les demandes avant les autres.',
        'pages' => [
            ['Formations et forfaits', 'Permis B, conduite accompagnée, boîte automatique, moto : une page par formation avec les prix.'],
            ['Financement', 'Les financements possibles selon la situation, pour les formations éligibles.'],
            ['Inscription en ligne', 'Un formulaire complet pour être rappelé ou commencer le dossier.'],
            ['L\'agence et l\'équipe', 'Horaires du bureau, moniteurs, lieux d\'examen, résultats.'],
        ],
        'formule' => ['complet', 'Le site complet permet une page par formation et l\'inscription en ligne. Une petite agence peut démarrer en vitrine.'],
        'faq' => [
            ['Peut-on afficher nos taux de réussite ?', 'Oui, vos résultats réels sont un argument fort. On les présente simplement, avec la source et la période.'],
            ['Les élèves peuvent-ils réserver leurs leçons sur le site ?', 'Le site peut renvoyer vers votre logiciel de planning habituel. On en parle au devis selon l\'outil que vous utilisez.'],
        ],
    ],
    'gite' => [
        'nom' => 'hébergement',
        'recherches' => ['gîte Normandie', 'chambre d\'hôtes Giverny', 'gîte avec piscine Eure', 'location vacances vallée de Seine', 'week-end en amoureux Normandie', 'gîte groupe'],
        'intro' => 'Les vacanciers découvrent souvent votre hébergement sur une plateforme, puis cherchent son nom sur Google pour en savoir plus ou réserver en direct. Sans site, ils retournent sur la plateforme, et vous payez la commission.',
        'pages' => [
            ['L\'hébergement en photos', 'Chambres, pièces de vie, extérieurs, équipements : de grandes photos qui donnent envie.'],
            ['Tarifs et disponibilités', 'Un calendrier à jour et des prix clairs selon la saison.'],
            ['Réservation directe', 'Demande de réservation ou réservation en ligne, sans commission.'],
            ['Autour de chez vous', 'Activités, sites à visiter, restaurants : un contenu utile que Google apprécie.'],
        ],
        'formule' => ['complet', 'Le site complet ajoute le calendrier et la réservation. Une version anglaise est disponible en option pour la clientèle étrangère.'],
        'faq' => [
            ['Le calendrier peut-il se synchroniser avec Airbnb et Booking ?', 'Dans la plupart des cas, oui : ces plateformes partagent leur calendrier, ce qui permet de bloquer d\'un côté les dates réservées de l\'autre et d\'éviter les doubles réservations. On vérifie ensemble au devis selon vos plateformes.'],
            ['Dois-je quitter les plateformes ?', 'Non. Elles restent utiles pour être découvert. Le site permet surtout aux clients qui vous connaissent déjà, ou qui reviennent, de réserver en direct.'],
        ],
    ],
    'agriculteur' => [
        'nom' => 'exploitation',
        'recherches' => ['vente directe ferme Eure', 'panier de légumes Évreux', 'producteur local miel', 'cueillette Normandie', 'viande en direct du producteur', 'marché fermier'],
        'intro' => 'De plus en plus de clients cherchent des produits locaux et veulent savoir où et quand acheter. Un site qui présente vos produits de saison, vos points de vente et vos horaires de vente à la ferme attire une clientèle fidèle sans intermédiaire.',
        'pages' => [
            ['Vos produits et la saison', 'Ce qui est disponible en ce moment, avec photos et calendrier des récoltes.'],
            ['Où acheter', 'Vente à la ferme, marchés, magasins de producteurs, AMAP : jours et horaires.'],
            ['Commande ou réservation', 'Colis, paniers, viande en caissette : commande en ligne et retrait à la ferme.'],
            ['La ferme et vos pratiques', 'Votre histoire, vos méthodes, vos labels si vous êtes certifié.'],
        ],
        'formule' => ['vitrine', 'Le site vitrine suffit pour présenter et informer. Pour prendre des commandes payées en ligne, la formule e-commerce est la bonne.'],
        'faq' => [
            ['Peut-on prendre des commandes sans paiement en ligne ?', 'Oui, un formulaire de réservation avec paiement au retrait suffit souvent pour des paniers ou des colis de viande.'],
            ['Qui met à jour les produits disponibles ?', 'Vous m\'envoyez les changements, je les fais sous 48 h. Pour une mise à jour chaque semaine, on peut prévoir une page simple que vous modifiez vous-même.'],
        ],
    ],
    'club-sportif' => [
        'nom' => 'club',
        'recherches' => ['club de foot Évreux inscription', 'club de tennis Vernon', 'judo enfants', 'club de natation', 'association sportive Eure', 'cours de danse'],
        'intro' => 'À chaque rentrée, les familles cherchent un club près de chez elles : horaires par âge, tarifs, lieu et comment s\'inscrire. Un club qui répond clairement à ces questions remplit ses créneaux et soulage ses bénévoles.',
        'pages' => [
            ['Activités et horaires', 'Par âge et par niveau, avec les lieux d\'entraînement.'],
            ['Inscriptions et tarifs', 'Cotisations, licences, documents à fournir, inscription ou paiement en ligne.'],
            ['Actualités et résultats', 'Matchs, compétitions, événements : de quoi faire vivre le club.'],
            ['Partenaires', 'Une vitrine pour vos sponsors, et un argument pour en trouver de nouveaux.'],
        ],
        'formule' => ['vitrine', 'Le site vitrine suffit à la plupart des clubs. Le site complet convient aux clubs avec plusieurs sections et beaucoup d\'actualités.'],
        'faq' => [
            ['Peut-on encaisser les cotisations en ligne ?', 'Oui, en renvoyant vers la plateforme de paiement que le club utilise déjà pour les adhésions, avec un bouton visible sur la page d\'inscription.'],
            ['Nos bénévoles changent souvent, qui s\'occupe du site ?', 'Les modifications passent par moi, par simple e-mail, sous 48 h. Le site ne dépend donc pas d\'un bénévole en particulier.'],
        ],
    ],
    'artiste' => [
        'nom' => 'artiste',
        'recherches' => ['groupe de musique mariage Normandie', 'artiste peintre Eure', 'concert', 'chanteur événement', 'exposition', 'booking artiste'],
        'intro' => 'Programmateurs, organisateurs d\'événements, journalistes et fans cherchent votre nom pour vous écouter, voir vos œuvres, connaître vos dates ou vous contacter. Votre site est votre carte de visite officielle, celle que vous maîtrisez entièrement, au-delà des réseaux sociaux.',
        'pages' => [
            ['Biographie', 'Votre parcours, votre univers, en quelques paragraphes et photos.'],
            ['Œuvres, musique, vidéos', 'Galerie, discographie, clips intégrés, extraits à écouter.'],
            ['Agenda', 'Concerts, expositions, dates à venir et passées.'],
            ['Presse et contact', 'Dossier de presse, fiche technique, contact booking.'],
        ],
        'formule' => ['vitrine', 'Le site vitrine suffit pour présenter votre travail. Pour vendre albums, tirages ou œuvres en ligne, la formule e-commerce est la bonne.'],
        'faq' => [
            ['Mes vidéos sont sur YouTube, peut-on les mettre sur le site ?', 'Oui, elles s\'intègrent directement dans les pages, sans les héberger une deuxième fois.'],
            ['Peut-on vendre des albums ou des œuvres ?', 'Oui, avec la formule e-commerce, ou plus simplement en renvoyant vers la plateforme de vente que vous utilisez déjà.'],
        ],
        'lien' => ['/realisations', 'Voir le site de l\'artiste Manou Bolomik'],
    ],
    'therapeute' => [
        'nom' => 'praticien',
        'recherches' => ['sophrologue Évreux', 'naturopathe Vernon', 'hypnothérapeute', 'réflexologie plantaire', 'massage bien-être Eure', 'psychopraticien'],
        'intro' => 'On choisit un praticien bien-être sur la confiance : son parcours, son approche, le déroulé d\'une séance et le prix. Un site sobre, qui explique simplement ce que vous proposez, transforme une recherche en prise de rendez-vous.',
        'pages' => [
            ['Vos approches', 'Une page par pratique ou par accompagnement, expliquée sans jargon.'],
            ['Déroulé et tarifs', 'Durée, déroulé d\'une première séance, prix, éventuels remboursements par certaines mutuelles.'],
            ['À propos', 'Votre parcours, vos formations, ce qui vous a amené à cette pratique.'],
            ['Rendez-vous et cabinet', 'Prise de rendez-vous, adresse, accès, séances à distance.'],
        ],
        'formule' => ['vitrine', 'Le site vitrine suffit pour la plupart des praticiens. Le site complet ajoute la réservation en ligne et des articles.'],
        'faq' => [
            ['Que peut-on écrire sur les bienfaits des séances ?', 'On décrit votre pratique et ce que vos clients peuvent en attendre, sans promettre de guérison ni se substituer à un avis médical. Vous validez chaque texte.'],
            ['Puis-je proposer la réservation en ligne ?', 'Oui, via votre outil de rendez-vous habituel ou avec le site complet, qui inclut la réservation.'],
        ],
    ],
    'traiteur' => [
        'nom' => 'traiteur',
        'recherches' => ['traiteur mariage Eure', 'traiteur entreprise Évreux', 'buffet anniversaire', 'plateaux repas', 'cocktail dînatoire', 'traiteur Vernon'],
        'intro' => 'Une demande de traiteur se prépare souvent longtemps à l\'avance et se compare : formules, prix par personne, photos des buffets, zone de livraison. Le traiteur qui permet de demander un devis précis en deux minutes reçoit plus de demandes qualifiées.',
        'pages' => [
            ['Vos prestations', 'Mariages, entreprises, particuliers : chaque clientèle a sa page et ses exemples.'],
            ['Menus et formules', 'Des exemples de menus avec un prix par personne indicatif.'],
            ['Demande de devis guidée', 'Date, nombre de convives, type d\'événement, budget : des demandes complètes.'],
            ['Photos et zone de livraison', 'Vos réalisations en images et les communes où vous livrez.'],
        ],
        'formule' => ['vitrine', 'Le site vitrine avec demande de devis guidée couvre l\'essentiel. Le site complet ajoute plus de menus et de pages.'],
        'faq' => [
            ['Faut-il afficher des prix ?', 'Un prix indicatif par personne pour quelques formules aide les clients à se situer et évite les demandes hors budget. Le devis reste personnalisé.'],
            ['Peut-on indiquer les allergènes ?', 'Oui, chaque menu peut préciser les allergènes et les options végétariennes ou sans gluten, ce que les clients apprécient.'],
        ],
    ],
    'assistante-maternelle' => [
        'nom' => 'assistante maternelle',
        'recherches' => ['assistante maternelle Évreux', 'nounou agréée Vernon', 'garde d\'enfant place disponible', 'MAM Eure', 'assistante maternelle horaires atypiques', 'garde périscolaire'],
        'intro' => 'Les parents cherchent une assistante maternelle près de chez eux ou de leur travail, avec des places disponibles et des horaires compatibles. Un site simple, qui présente votre agrément, votre cadre de vie et vos disponibilités, vous fait connaître au-delà du bouche-à-oreille.',
        'pages' => [
            ['Présentation et agrément', 'Votre expérience, votre agrément, votre façon d\'accueillir les enfants.'],
            ['Places et horaires', 'Les places disponibles et à venir, vos horaires, les horaires atypiques si vous les acceptez.'],
            ['Journée type et activités', 'Repas, sorties, activités d\'éveil, photos des lieux.'],
            ['Contact', 'Un formulaire pour que les parents précisent leurs besoins et leurs dates.'],
        ],
        'formule' => ['vitrine', 'Le site vitrine suffit largement, une seule page bien faite peut même démarrer.'],
        'faq' => [
            ['Peut-on mettre des photos des enfants ?', 'Mieux vaut l\'éviter : on privilégie les photos des lieux, du jardin et des activités sans visage. Toute photo d\'enfant nécessiterait l\'accord écrit des parents.'],
            ['Je suis déjà sur le site public de la CAF, est-ce utile ?', 'Oui, les deux se complètent : votre site vous présente en détail et apparaît quand les parents cherchent une assistante maternelle dans votre commune.'],
        ],
    ],
    'coiffeur' => [
        'nom' => 'salon',
        'recherches' => ['coiffeur Évreux', 'barbier Vernon', 'coloriste', 'coiffeur ouvert le lundi', 'coiffure mariage', 'salon de coiffure prix'],
        'intro' => 'On choisit un salon pour son style, ses avis, ses prix et la facilité à prendre rendez-vous. Un site qui montre vos réalisations et permet de réserver à toute heure capte les clients qui cherchent le soir, quand le salon est fermé.',
        'pages' => [
            ['Prestations et tarifs', 'Coupe, couleur, soins, barbe : vos prix clairement affichés.'],
            ['Réservation en ligne', 'Votre outil de rendez-vous habituel, accessible depuis chaque page.'],
            ['Galerie', 'Vos réalisations et l\'ambiance du salon, reliées à votre Instagram.'],
            ['L\'équipe et le salon', 'Les coiffeurs, leurs spécialités, l\'adresse et les horaires.'],
        ],
        'formule' => ['complet', 'Le site complet inclut la réservation en ligne. Si vous utilisez déjà un outil de réservation, le site vitrine peut suffire.'],
        'faq' => [
            ['J\'utilise déjà une application de réservation, faut-il en changer ?', 'Non, le site renvoie vers votre outil actuel. Vos clients réservent comme d\'habitude, mais vous êtes aussi trouvé sur Google avec votre propre site.'],
            ['Peut-on afficher mon Instagram sur le site ?', 'Oui, vos dernières publications peuvent apparaître sur le site, ou on renvoie vers votre compte depuis la galerie.'],
        ],
    ],
];

$d = $secteurs[$secteurSlug] ?? null;
if ($d === null) {
    return;
}
$formuleConseillee = $formulesSecteur[$d['formule'][0]];
?>

  <section><div class="container">
    <div class="section-header">
      <h2 class="section-title" data-animate>Ce que vos clients <span class="gradient-text">cherchent sur Google</span></h2>
      <p class="section-subtitle" data-animate><?= htmlspecialchars($d['intro']) ?></p>
    </div>
    <div class="villes-tags" style="justify-content: center; max-width: 860px; margin: 0 auto;" data-animate>
<?php foreach ($d['recherches'] as $r): ?>
      <span class="ville-tag"><?= htmlspecialchars($r) ?></span>
<?php endforeach; ?>
    </div>
  </div></section>

  <section><div class="container">
    <div class="section-header"><h2 class="section-title" data-animate>Les pages <span class="gradient-text">indispensables</span></h2><p class="section-subtitle" data-animate>Ce qu'un bon site de <?= htmlspecialchars($d['nom']) ?> doit contenir pour être trouvé et recevoir des demandes.</p></div>
    <div class="features-grid features-grid-4">
<?php foreach ($d['pages'] as $i => [$titre, $texte]): ?>
      <div class="feature-card glass" data-animate><div class="feature-icon"><?= $i + 1 ?></div><h3><?= htmlspecialchars($titre) ?></h3><p><?= htmlspecialchars($texte) ?></p></div>
<?php endforeach; ?>
    </div>
<?php if (!empty($d['metiers'])): ?>
    <div class="features-grid" style="margin-top: 1.5rem;">
<?php foreach ($d['metiers'] as [$ancre, $titre, $texte]): ?>
      <div class="feature-card glass" id="<?= $ancre ?>" data-animate><h3><?= htmlspecialchars($titre) ?></h3><p><?= htmlspecialchars($texte) ?></p></div>
<?php endforeach; ?>
    </div>
<?php endif; ?>
    <div class="zone-card glass-strong" style="margin-top: 2rem;" data-animate>
      <div>
        <h3 style="margin-bottom: 0.5rem;">Combien ça coûte ?</h3>
        <p style="color: var(--gray-400);">Formule conseillée : <strong style="color: var(--white);"><?= htmlspecialchars($formuleConseillee['label']) ?>, <?= $formuleConseillee['mensuel'] ?> €/mois</strong> tout compris (création, hébergement, nom de domaine, mises à jour et modifications), engagement <?= $tarifsSecteur['formule']['engagement'] ?> mois. <?= htmlspecialchars($d['formule'][1]) ?> <a href="/tarifs" style="color: var(--primary-light);">Voir les trois formules</a><?php if (!empty($d['lien'])): ?> · <a href="<?= htmlspecialchars($d['lien'][0]) ?>" style="color: var(--primary-light);"><?= htmlspecialchars($d['lien'][1]) ?></a><?php endif; ?>.</p>
      </div>
    </div>
  </div></section>
<?php if (!empty($d['faq'])): ?>

  <section><div class="container">
    <div class="section-header"><h2 class="section-title" data-animate>Questions <span class="gradient-text">fréquentes</span></h2></div>
    <div class="faq-list">
<?php foreach ($d['faq'] as [$question, $reponse]): ?>
      <div class="faq-item glass" data-animate>
        <button class="faq-question"><span><?= htmlspecialchars($question) ?></span><span>+</span></button>
        <div class="faq-answer"><p><?= htmlspecialchars($reponse) ?></p></div>
      </div>
<?php endforeach; ?>
    </div>
  </div></section>
<?php endif; ?>
