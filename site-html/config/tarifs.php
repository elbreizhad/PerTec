<?php
/**
 * Grille tarifaire Pertec
 * Modèle : abonnement 24 mois puis maintenance
 */

return [
    // Formules principales
    'formule' => [
        'label' => 'Quelle formule ?',
        'type' => 'radio',
        'default' => 'vitrine',
        'engagement' => 24, // mois
        'options' => [
            'vitrine' => [
                'label' => 'Site Vitrine',
                'description' => 'Jusqu\'à 5 pages. Idéal pour présenter votre activité et être trouvé sur Google.',
                'mensuel' => 59,
                'badge' => 'Populaire',
            ],
            'complet' => [
                'label' => 'Site Complet',
                'description' => 'Jusqu\'à 10 pages avec blog, galerie et réservation en ligne.',
                'mensuel' => 149,
                'badge' => null,
            ],
            'ecommerce' => [
                'label' => 'E-commerce',
                'description' => 'Boutique en ligne avec paiement sécurisé et gestion des stocks.',
                'mensuel' => 249,
                'badge' => null,
            ],
        ],
    ],

    // Comparatif des fonctionnalités par formule
    'comparatif' => [
        'Pages' => [
            'vitrine' => 'Jusqu\'à 5',
            'complet' => 'Jusqu\'à 10',
            'ecommerce' => 'Jusqu\'à 15 + boutique',
        ],
        'Design sur-mesure responsive' => [
            'vitrine' => true,
            'complet' => true,
            'ecommerce' => true,
        ],
        'Domaine + hébergement' => [
            'vitrine' => true,
            'complet' => true,
            'ecommerce' => true,
        ],
        'Certificat SSL (HTTPS)' => [
            'vitrine' => true,
            'complet' => true,
            'ecommerce' => true,
        ],
        'Conformité RGPD' => [
            'vitrine' => true,
            'complet' => true,
            'ecommerce' => true,
        ],
        'Référencement Google' => [
            'vitrine' => 'Base',
            'complet' => 'Optimisé',
            'ecommerce' => 'Optimisé',
        ],
        'Formulaire de contact' => [
            'vitrine' => true,
            'complet' => true,
            'ecommerce' => true,
        ],
        'Statistiques de visite' => [
            'vitrine' => true,
            'complet' => true,
            'ecommerce' => true,
        ],
        'Blog / Actualités' => [
            'vitrine' => false,
            'complet' => true,
            'ecommerce' => true,
        ],
        'Réservation en ligne' => [
            'vitrine' => false,
            'complet' => true,
            'ecommerce' => false,
        ],
        'Galerie photos' => [
            'vitrine' => false,
            'complet' => true,
            'ecommerce' => true,
        ],
        'Paiement en ligne' => [
            'vitrine' => false,
            'complet' => false,
            'ecommerce' => true,
        ],
        'Gestion produits / stocks' => [
            'vitrine' => false,
            'complet' => false,
            'ecommerce' => true,
        ],
        'Modifications incluses' => [
            'vitrine' => 'Illimitées',
            'complet' => 'Illimitées',
            'ecommerce' => 'Illimitées',
        ],
    ],

    // Options récurrentes (par mois)
    'options_recurrentes' => [
        'label' => 'Options mensuelles',
        'options' => [
            'multilingue' => [
                'label' => 'Site multilingue',
                'description' => 'Votre site traduit en anglais ou autre langue.',
                'mensuel' => 20,
                'pour' => 'Gîtes, tourisme, zones frontalières',
            ],
            'seo' => [
                'label' => 'SEO renforcé',
                'description' => 'Optimisation avancée, suivi de positions, rapport mensuel.',
                'mensuel' => 39,
                'pour' => 'Marchés concurrentiels (plombier, avocat...)',
            ],
            'reseaux' => [
                'label' => 'Gestion réseaux sociaux',
                'description' => 'Création de contenu et publication sur vos réseaux.',
                'mensuel' => 79,
                'pour' => 'Ceux qui veulent tout déléguer',
            ],
        ],
    ],

    // Options uniques (paiement one-shot)
    'options_uniques' => [
        'label' => 'Options à la carte',
        'options' => [
            'logo' => [
                'label' => 'Création de logo',
                'description' => 'Logo professionnel + déclinaisons web et print.',
                'prix' => 250,
            ],
            'shooting' => [
                'label' => 'Shooting photo',
                'description' => 'Photos professionnelles de votre activité (demi-journée).',
                'prix' => 350,
            ],
            'gmb' => [
                'label' => 'Fiche Google Business',
                'description' => 'Création et optimisation de votre fiche Google.',
                'prix' => 99,
            ],
            'page_sup' => [
                'label' => 'Page supplémentaire',
                'description' => 'Page additionnelle au-delà du quota inclus.',
                'prix' => 49,
                'unite' => 'par page',
            ],
        ],
    ],

    // Après engagement : maintenance
    'maintenance' => [
        'label' => 'Après 24 mois',
        'description' => 'Hébergement, sauvegardes, mises à jour sécurité, modifications sous 48h, support email.',
        'mensuel' => 49,
    ],

    // Ce qui est inclus dans toutes les formules (résumé)
    'inclus' => [
        'Nom de domaine (.fr ou .com)',
        'Hébergement géré par Pertec',
        'Certificat SSL (HTTPS)',
        'Design sur-mesure responsive',
        'Conformité RGPD',
        'Référencement Google',
        'Formulaire de contact',
        'Statistiques de visite',
        'Modifications illimitées',
        'Support par email',
        'Site transférable en fin de contrat',
    ],

    // Configuration
    'config' => [
        'contact_email' => 'contact@pertec.fr',
        'contact_tel' => '',
        'tva_mention' => 'Prix HT / TVA non applicable, art. 293 B du CGI',
    ],
];
