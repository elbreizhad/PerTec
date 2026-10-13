<?php
/**
 * Articles de la rubrique Conseils - Pertec.fr
 *
 * Pour publier un article :
 * 1. créer conseils/<slug>.php (copier un article existant) ;
 * 2. l'ajouter dans ce tableau avec sa date de publication.
 * Le sitemap et la liste se mettent à jour seuls. Une date future = article
 * programmé, invisible jusqu'à ce jour-là. 'modifie' (facultatif) = dernière mise à jour.
 */

return [
    'planning-cabinet-infirmier' => [
        'titre' => 'Planning de cabinet infirmier : fini les heures sur le tableau',
        'description' => 'Outil web qui génère le planning d\'un cabinet infirmier : équité entre associés, week-ends, fériés, remplaçantes, agenda et PDF. Sur devis.',
        'resume' => 'Génération automatique des tournées, équité entre associés, remplaçantes, agenda du téléphone : un outil de planning pour cabinets infirmiers et équipes de soignants.',
        'categorie' => 'Outils sur mesure',
        'date' => '2026-10-13',
        'image' => '/images/conseils/planning-infirmier-calendrier.webp',
    ],
    'faire-connaitre-entreprise-securite-privee' => [
        'titre' => 'Comment faire connaître son entreprise de sécurité privée ?',
        'description' => 'Faire connaître son entreprise de sécurité privée : site internet, référencement local, fiche Google, réseau, appels d\'offres. Plan d\'action sur 3 mois.',
        'resume' => 'Site internet, référencement local, fiche Google, réseau, appels d\'offres : les leviers pour trouver de nouveaux clients quand on dirige une entreprise de sécurité.',
        'categorie' => 'Sécurité privée',
        'date' => '2026-10-06',
        'image' => '/images/conseils/securite-tous-les-chemins.webp',
    ],
    'site-internet-entreprise-securite-privee' => [
        'titre' => 'Site internet pour une entreprise de sécurité privée : ce qui rassure vos clients',
        'description' => 'Ce que doit contenir le site d\'une société de sécurité ou d\'agents de sécurité : CNAPS, prestations, zone, devis, recrutement. Avec des exemples réalisés.',
        'resume' => 'Autorisation CNAPS, prestations, zone d\'intervention, recrutement, formation : ce que doit contenir le site d\'une société de sécurité ou d\'un centre de formation, avec des exemples réalisés.',
        'categorie' => 'Sécurité privée',
        'date' => '2026-09-29',
        'modifie' => '2026-10-13',
        'image' => '/images/portfolio/sts-securite.webp',
    ],
    'prix-site-internet-artisan' => [
        'titre' => 'Combien coûte un site internet pour un artisan en 2026 ?',
        'description' => 'Prix d\'un site internet pour artisan en 2026 : créateur de site, location, indépendant ou agence. Ce qui fait varier le prix, les coûts cachés et les pièges à éviter.',
        'resume' => 'Créateur de site, location, indépendant ou agence : les vrais prix, ce qui les fait varier et les pièges à éviter avant de signer.',
        'categorie' => 'Prix',
        'date' => '2026-09-15',
        'modifie' => '2026-10-13',
        'image' => '/images/conseils/prix-site-artisan.webp',
    ],
];
