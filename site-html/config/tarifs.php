<?php
/**
 * Grille tarifaire Pertec
 * SEULE source des prix - utilisée par le JS et le PHP
 */

return [
    // Question 1 : Type de site (choix unique)
    'type_site' => [
        'label' => 'Quel site vous faut-il ?',
        'type' => 'radio',
        'default' => 'vitrine5',
        'options' => [
            'page_unique' => [
                'label' => 'Page unique',
                'description' => 'Une page complète pour être trouvé et contacté.',
                'creation' => 490,
                'mensuel' => 0,
            ],
            'vitrine5' => [
                'label' => 'Site vitrine, 5 pages',
                'description' => 'Accueil, services, réalisations, à propos, contact.',
                'creation' => 990,
                'mensuel' => 0,
            ],
            'vitrine10' => [
                'label' => 'Site vitrine, 10 pages',
                'description' => 'Une page par service ou par zone d\'intervention.',
                'creation' => 1590,
                'mensuel' => 0,
            ],
            'sur_mesure' => [
                'label' => 'Sur mesure',
                'description' => 'Boutique en ligne, espace client, outil métier.',
                'creation' => null, // Sur devis
                'mensuel' => 0,
            ],
        ],
    ],

    // Question 2 : Options supplémentaires (choix multiples)
    'options' => [
        'label' => 'Que doit-il faire en plus ?',
        'type' => 'checkbox',
        'options' => [
            'redaction' => [
                'label' => 'Je rédige vos textes',
                'description' => 'Vous me parlez de votre métier, j\'écris les pages.',
                'creation' => 290,
                'mensuel' => 0,
            ],
            'rdv_ligne' => [
                'label' => 'Rendez-vous en ligne',
                'description' => 'Vos clients réservent un créneau sans vous appeler.',
                'creation' => 190,
                'mensuel' => 9,
            ],
            'devis_guide' => [
                'label' => 'Demande de devis guidée',
                'description' => 'Un formulaire qui vous envoie des demandes complètes.',
                'creation' => 150,
                'mensuel' => 0,
            ],
            'logo_couleurs' => [
                'label' => 'Logo et couleurs',
                'description' => 'Une identité simple et propre si vous partez de zéro.',
                'creation' => 250,
                'mensuel' => 0,
            ],
        ],
    ],

    // Question 3 : Après mise en ligne (inclus + options)
    'suivi' => [
        'label' => 'Et après la mise en ligne ?',
        'type' => 'checkbox',
        'inclus' => [
            'id' => 'suivi_base',
            'label' => 'Inclus : je m\'occupe de tout',
            'description' => 'Hébergement, nom de domaine, mises à jour, sauvegardes et vos modifications faites sous 48 h.',
            'creation' => 0,
            'mensuel' => 39,
        ],
        'options' => [
            'fiche_google' => [
                'label' => 'Fiche Google gérée',
                'description' => 'Photos, horaires, publications, réponses aux avis.',
                'creation' => 0,
                'mensuel' => 29,
            ],
            'avis_clients' => [
                'label' => 'Collecte d\'avis clients',
                'description' => 'QR code et SMS après intervention pour faire monter votre note.',
                'creation' => 0,
                'mensuel' => 19,
            ],
            'rapport_mensuel' => [
                'label' => 'Rapport mensuel',
                'description' => 'Appels, formulaires et devis reçus grâce au site, en une page.',
                'creation' => 0,
                'mensuel' => 15,
            ],
        ],
    ],

    // Question 4 : Délai (choix unique)
    'delai' => [
        'label' => 'Pour quand ?',
        'type' => 'radio',
        'default' => 'normal',
        'options' => [
            'normal' => [
                'label' => 'Sans urgence',
                'description' => 'En ligne sous 3 à 4 semaines.',
                'majoration' => 0, // Pas de majoration
            ],
            'express' => [
                'label' => 'Au plus vite',
                'description' => 'En ligne sous 10 jours.',
                'majoration' => 0.20, // +20% sur création, arrondi à la dizaine
            ],
        ],
    ],

    // Configuration
    'config' => [
        'contact_email' => 'contact@pertec.fr',
        'contact_tel' => '', // Pas de téléphone
        'tva_mention' => 'Prix HT / TVA non applicable, art. 293 B du CGI',
    ],
];
