<?php
/**
 * Informations publiques de l'entreprise - Pertec.fr
 */
define('GOOGLE_BUSINESS_URL', 'https://share.google/t2IrsbJ0cJDq0Du7Y');

// Code de vérification Google Search Console (méthode « balise HTML »).
// Inutile si le domaine est vérifié par DNS. Exemple : 'AbC123...'
define('GOOGLE_SITE_VERIFICATION', '');

/**
 * Avis clients affichés sur l'accueil.
 * Uniquement de vrais avis, recopiés tels quels (ex. depuis la fiche Google).
 * La section reste masquée tant que la liste est vide.
 * Format : ['nom' => 'Prénom N.', 'entreprise' => 'Société', 'texte' => '...', 'note' => 5]
 */
$avisClients = [];
