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

/**
 * Fiche entreprise Schema.org : une seule adresse, à Évreux.
 * Les pages villes y font référence au lieu de déclarer de fausses adresses.
 */
function schema_entreprise(): array {
    return [
        '@type' => 'ProfessionalService',
        '@id' => 'https://pertec.fr/#entreprise',
        'name' => 'Pertec - Création de sites internet',
        'description' => 'Création de sites internet clé en main à Évreux et en Normandie',
        'url' => 'https://pertec.fr',
        'image' => 'https://pertec.fr/images/logo-pertec.svg',
        'email' => 'contact@pertec.fr',
        'priceRange' => 'À partir de 490 €',
        'openingHours' => 'Mo-Fr 09:00-18:00',
        'address' => [
            '@type' => 'PostalAddress',
            'addressLocality' => 'Évreux',
            'postalCode' => '27000',
            'addressRegion' => 'Normandie',
            'addressCountry' => 'FR',
        ],
        'geo' => ['@type' => 'GeoCoordinates', 'latitude' => 49.0247, 'longitude' => 1.1508],
        'areaServed' => [
            '@type' => 'GeoCircle',
            'geoMidpoint' => ['@type' => 'GeoCoordinates', 'latitude' => 49.0247, 'longitude' => 1.1508],
            'geoRadius' => '50000',
        ],
        'serviceType' => ['Création site internet', 'Site vitrine', 'Site artisan', 'SEO local'],
        'sameAs' => [GOOGLE_BUSINESS_URL],
    ];
}

/**
 * JSON-LD de l'entreprise (page d'accueil).
 */
function schema_entreprise_json(): string {
    return json_encode(['@context' => 'https://schema.org'] + schema_entreprise(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

/**
 * JSON-LD d'une page ville : le service proposé dans cette ville,
 * fourni par l'entreprise basée à Évreux.
 */
function schema_service_ville(string $ville, string $slug): string {
    return json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => 'Création de site internet à ' . $ville,
        'serviceType' => 'Création de site internet',
        'url' => 'https://pertec.fr/creation-site-internet/' . $slug,
        'areaServed' => ['@type' => 'City', 'name' => $ville],
        'offers' => ['@type' => 'Offer', 'price' => '490', 'priceCurrency' => 'EUR', 'description' => 'Site clé en main à partir de 490 €'],
        'provider' => schema_entreprise(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

/**
 * JSON-LD d'une page métier ou secteur : le service proposé à ce métier.
 */
function schema_service_metier(string $metier, string $chemin): string {
    return json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Service',
        'name' => 'Création de site internet pour ' . $metier,
        'serviceType' => 'Création de site internet',
        'url' => 'https://pertec.fr' . $chemin,
        'areaServed' => ['@type' => 'AdministrativeArea', 'name' => 'Normandie'],
        'offers' => ['@type' => 'Offer', 'price' => '490', 'priceCurrency' => 'EUR', 'description' => 'Site clé en main à partir de 490 €'],
        'provider' => schema_entreprise(),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}

/**
 * URL d'un fichier statique avec sa date de modification (ex. /css/style.css?v=1760280000).
 * Après chaque déploiement, les navigateurs voient une nouvelle adresse et
 * rechargent le fichier : plus besoin de vider le cache.
 */
function asset(string $chemin): string {
    $fichier = dirname(__DIR__) . $chemin;
    return $chemin . (is_file($fichier) ? '?v=' . filemtime($fichier) : '');
}
