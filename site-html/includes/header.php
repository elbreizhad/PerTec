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
 * - $ogData : (optionnel) tableau Open Graph ['title', 'description', 'type', 'url']
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="<?= htmlspecialchars($pageDescription) ?>">
  <link rel="canonical" href="https://pertec.fr<?= htmlspecialchars($canonicalUrl) ?>">
<?php if (GOOGLE_SITE_VERIFICATION !== ''): ?>
  <meta name="google-site-verification" content="<?= htmlspecialchars(GOOGLE_SITE_VERIFICATION) ?>">
<?php endif; ?>

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="/favicon.svg">

<?php if (!empty($ogData)): ?>
  <!-- Open Graph -->
  <meta property="og:title" content="<?= htmlspecialchars($ogData['title'] ?? $pageTitle) ?>">
  <meta property="og:description" content="<?= htmlspecialchars($ogData['description'] ?? $pageDescription) ?>">
  <meta property="og:type" content="<?= htmlspecialchars($ogData['type'] ?? 'website') ?>">
  <meta property="og:url" content="https://pertec.fr<?= htmlspecialchars($ogData['url'] ?? $canonicalUrl) ?>">
  <meta property="og:locale" content="fr_FR">

<?php endif; ?>
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- CSS -->
  <link rel="stylesheet" href="/css/style.css">

<?php if (!empty($schemaJson)): ?>
  <!-- Schema.org -->
  <script type="application/ld+json">
  <?= $schemaJson ?>
  </script>
<?php endif; ?>
</head>
<body>
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
          <img src="/images/logo-pertec.svg?v=2" alt="Pertec">
        </a>

        <nav class="nav">
          <a href="/services.php" class="nav-link<?= ($activeMenu === 'services') ? ' active' : '' ?>">Services</a>
          <a href="/tarifs.php" class="nav-link<?= ($activeMenu === 'tarifs') ? ' active' : '' ?>">Tarifs</a>
          <a href="/realisations.php" class="nav-link<?= ($activeMenu === 'realisations') ? ' active' : '' ?>">Réalisations</a>
          <a href="/a-propos.php" class="nav-link<?= ($activeMenu === 'apropos') ? ' active' : '' ?>">À propos</a>
          <a href="/contact.php" class="nav-link<?= ($activeMenu === 'contact') ? ' active' : '' ?>">Contact</a>
        </nav>

        <a href="/contact.php" class="btn btn-primary header-cta">Devis gratuit</a>

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
    <a href="/services.php">Services</a>
    <a href="/tarifs.php">Tarifs</a>
    <a href="/realisations.php">Réalisations</a>
    <a href="/a-propos.php">À propos</a>
    <a href="/contact.php">Contact</a>
    <a href="/contact.php" class="btn btn-primary">Devis gratuit</a>
  </div>
