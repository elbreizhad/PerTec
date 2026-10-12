<?php
require_once dirname(__DIR__) . '/config/site.php';
require_once __DIR__ . '/stats.php';
stats_track_pageview();
/**
 * Header partagé - Pertec.fr
 * Variables à définir avant inclusion :
 * - $pageTitle : titre de la page
 * - $pageDescription : meta description
 * - $canonicalUrl : URL canonique (chemin relatif, ex: "/services")
 * - $activeMenu : menu actif (services, tarifs, realisations, apropos, contact)
 * - $schemaJson : (optionnel) JSON-LD Schema.org
 * - $ogData : (optionnel) surcharge Open Graph ['title', 'description', 'type', 'url', 'image']
 * - $extraHead : (optionnel) HTML ajouté en fin de <head> (scripts, CSS spécifiques)
 * - $bodyClass : (optionnel) classe CSS du <body>
 * - $noindex : (optionnel) true pour une page à ne pas indexer (ex. 404)
 */
$ogData = $ogData ?? [];
$ogImage = $ogData['image'] ?? '/images/og-pertec.jpg';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
<?php if (!empty($noindex)): ?>
  <meta name="robots" content="noindex">
<?php else: ?>
  <link rel="canonical" href="https://pertec.fr<?= htmlspecialchars($canonicalUrl) ?>">
<?php endif; ?>
<?php if (GOOGLE_SITE_VERIFICATION !== ''): ?>
  <meta name="google-site-verification" content="<?= htmlspecialchars(GOOGLE_SITE_VERIFICATION) ?>">
<?php endif; ?>

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="/favicon.svg">

  <!-- Partage (Open Graph) -->
  <meta property="og:site_name" content="Pertec">
  <meta property="og:title" content="<?= htmlspecialchars($ogData['title'] ?? $pageTitle) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($ogData['description'] ?? $pageDescription) ?>">
  <meta property="og:type" content="<?= htmlspecialchars($ogData['type'] ?? 'website') ?>">
  <meta property="og:url" content="https://pertec.fr<?= htmlspecialchars($ogData['url'] ?? $canonicalUrl) ?>">
  <meta property="og:image" content="https://pertec.fr<?= htmlspecialchars($ogImage) ?>">
  <meta property="og:image:width" content="1200">
  <meta property="og:image:height" content="630">
  <meta property="og:locale" content="fr_FR">
  <meta name="twitter:card" content="summary_large_image">

  <!-- Police hébergée sur le site (pas d'appel à Google Fonts) -->
  <link rel="preload" href="/fonts/inter-400.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="/fonts/inter-700.woff2" as="font" type="font/woff2" crossorigin>

  <!-- CSS -->
  <link rel="stylesheet" href="<?= asset('/css/style.css') ?>">

<?php if (!empty($schemaJson)): ?>
  <!-- Schema.org -->
  <script type="application/ld+json">
  <?= $schemaJson ?>
  </script>
<?php endif; ?>
<?= $extraHead ?? '' ?>
</head>
<body<?= !empty($bodyClass) ? ' class="' . htmlspecialchars($bodyClass) . '"' : '' ?>>
  <!-- Background Effects -->
  <div class="bg-effects">
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="grid-pattern"></div>
  </div>

  <!-- Header -->
  <header class="header">
    <div class="container">
      <div class="header-inner">
        <a href="/" class="logo">
          <img src="<?= asset('/images/logo-pertec.svg') ?>" alt="Pertec">
        </a>

        <nav class="nav">
          <a href="/services" class="nav-link<?= ($activeMenu === 'services') ? ' active' : '' ?>">Services</a>
          <a href="/tarifs" class="nav-link<?= ($activeMenu === 'tarifs') ? ' active' : '' ?>">Tarifs</a>
          <a href="/realisations" class="nav-link<?= ($activeMenu === 'realisations') ? ' active' : '' ?>">Réalisations</a>
          <a href="/conseils/" class="nav-link<?= ($activeMenu === 'conseils') ? ' active' : '' ?>">Conseils</a>
          <a href="/a-propos" class="nav-link<?= ($activeMenu === 'apropos') ? ' active' : '' ?>">À propos</a>
          <a href="/contact" class="nav-link<?= ($activeMenu === 'contact') ? ' active' : '' ?>">Contact</a>
        </nav>

        <a href="/contact" class="btn btn-primary header-cta">Devis gratuit</a>

        <button class="menu-toggle" aria-label="Menu">
          <span></span>
          <span></span>
          <span></span>
        </button>
      </div>
    </div>
  </header>

  <!-- Mobile Menu -->
  <div class="mobile-menu">
    <a href="/services">Services</a>
    <a href="/tarifs">Tarifs</a>
    <a href="/realisations">Réalisations</a>
    <a href="/conseils/">Conseils</a>
    <a href="/a-propos">À propos</a>
    <a href="/contact">Contact</a>
    <a href="/contact" class="btn btn-primary">Devis gratuit</a>
  </div>
