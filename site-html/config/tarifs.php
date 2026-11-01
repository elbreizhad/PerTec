<?php
/**
 * Grille tarifaire Pertec
 * Modèle : abonnement 24 mois puis maintenance
 */

return [
    // Question 1 : Formule (choix unique)
    'formule' => [
        'label' => 'Quelle formule ?',
        'type' => 'radio',
        'default' => 'vitrine',
        'engagement' => 24, // mois
        'options' => [
            'vitrine' => [
                'label' => 'Site Vitrine',
                'description' => 'Jusqu\'à 5 pages. Idéal pour être trouvé et présenter votre activité.',
                'mensuel' => 59,
                'badge' => 'Populaire',
            ],
            'complet' => [
                'label' => 'Site Complet',
                'description' => 'Jusqu\'à 10 pages, fonctionnalités avancées, formulaires sur-mesure.',
                'mensuel' => 149,
                'badge' => null,
            ],
            'ecommerce' => [
                'label' => 'E-commerce',
                'description' => 'Boutique en ligne complète avec paiement sécurisé et gestion des stocks.',
                'mensuel' => 249,
                'badge' => null,
            ],
        ],
    ],

    // Question 2 : Options supplémentaires
    'options' => [
        'label' => 'Options supplémentaires',
        'type' => 'checkbox',
        'options' => [
            'rdv_ligne' => [
                'label' => 'Réservation en ligne',
                'description' => 'Vos clients réservent un créneau directement sur le site.',
                'mensuel' => 15,
            ],
            'multilingue' => [
                'label' => 'Site multilingue',
                'description' => 'Votre site traduit en anglais ou autre langue.',
                'mensuel' => 20,
            ],
            'logo' => [
                'label' => 'Création de logo',
                'description' => 'Logo professionnel + déclinaisons pour web et print.',
                'unique' => 250, // paiement unique
            ],
            'shooting' => [
                'label' => 'Shooting photo',
                'description' => 'Photos professionnelles de votre activité (demi-journée).',
                'unique' => 350,
            ],
        ],
    ],

    // Après engagement : maintenance
    'maintenance' => [
        'label' => 'Après 24 mois',
        'description' => 'Hébergement, mises à jour, sauvegardes et vos modifications sous 48h.',
        'mensuel_base' => 49,
    ],

    // Ce qui est inclus dans toutes les formules
    'inclus' => [
        'Nom de domaine (.fr ou .com)',
        'Hébergement haute performance',
        'Certificat SSL (HTTPS)',
        'Design sur-mesure responsive',
        'Rédaction des textes SEO',
        'Référencement Google de base',
        'Formulaire de contact',
        'Statistiques de visite',
        'Modifications illimitées',
        'Support par email',
    ],

    // Configuration
    'config' => [
        'contact_email' => 'contact@pertec.fr',
        'contact_tel' => '',
        'tva_mention' => 'Prix HT / TVA non applicable, art. 293 B du CGI',
    ],
];
