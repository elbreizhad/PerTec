<?php
/**
 * Pertec - Page Tarifs
 * Configurateur de prix interactif
 */

session_start();
require_once __DIR__ . '/includes/turnstile.php';

// Générer le token CSRF si nécessaire
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Charger la grille tarifaire
$tarifs = require __DIR__ . '/config/tarifs.php';
$config = $tarifs['config'] ?? [];

// Récupérer les paramètres admin si disponibles
$settings_file = __DIR__ . '/admin/settings.json';
if (file_exists($settings_file)) {
    $settings = json_decode(file_get_contents($settings_file), true) ?: [];
    $config['contact_email'] = $settings['contact_email'] ?? $config['contact_email'];
    $config['contact_tel'] = $settings['contact_phone'] ?? $config['contact_tel'];
}

// Turnstile
$turnstileSiteKey = get_turnstile_site_key();

// Fonction pour échapper HTML
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Format prix
function formatPrix($prix) {
    if ($prix === null) return 'Sur devis';
    return number_format($prix, 0, ',', ' ') . ' €';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarifs Création Site Internet | Devis en Ligne | Pertec.fr</title>
    <meta name="description" content="Estimez le prix de votre site internet en 4 questions. Tarifs transparents pour artisans, commerces et PME. À partir de 490 €. Devis gratuit.">
    <link rel="canonical" href="https://pertec.fr/tarifs">
    <link rel="icon" type="image/svg+xml" href="favicon.svg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS principal du site -->
    <link rel="stylesheet" href="css/style.css">
    <!-- CSS spécifique tarifs -->
    <link rel="stylesheet" href="css/tarifs.css">
    <?php if ($turnstileSiteKey): ?>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <?php endif; ?>
</head>
<body class="page-tarifs">

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
                    <img src="images/logo-pertec.svg" alt="Pertec">
                </a>
                <nav class="nav">
                    <a href="services.html" class="nav-link">Services</a>
                    <a href="tarifs.php" class="nav-link active">Tarifs</a>
                    <a href="realisations.html" class="nav-link">Réalisations</a>
                    <a href="a-propos.html" class="nav-link">À propos</a>
                    <a href="contact.php" class="nav-link">Contact</a>
                </nav>
                <a href="contact.php" class="btn btn-primary header-cta">Devis gratuit</a>
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
        <a href="services.html">Services</a>
        <a href="tarifs.php">Tarifs</a>
        <a href="realisations.html">Réalisations</a>
        <a href="a-propos.html">À propos</a>
        <a href="contact.php">Contact</a>
        <a href="contact.php" class="btn btn-primary">Devis gratuit</a>
    </div>

    <main>
        <div class="tarifs-container">
            <!-- Header -->
            <header class="tarifs-header tarifs-section">
                <p class="tarifs-surtitre">TARIFS</p>
                <h1 class="tarifs-h1">Estimez votre site en 4 questions</h1>
                <p class="tarifs-intro">Cochez ce dont vous avez besoin, le prix se met à jour tout de suite. Pas de surprise, pas d'engagement.</p>
            </header>

            <!-- Layout principal -->
            <div class="tarifs-layout">
                <!-- Colonne questions -->
                <div class="tarifs-questions">

                    <!-- Question 1 : Type de site -->
                    <section class="tarifs-section">
                        <h2 class="tarifs-h2">1. <?= e($tarifs['type_site']['label']) ?></h2>
                        <div class="tarifs-options" role="radiogroup" aria-label="Type de site">
                            <?php foreach ($tarifs['type_site']['options'] as $id => $opt): ?>
                            <label class="tarifs-option <?= $id === $tarifs['type_site']['default'] ? 'selected' : '' ?>">
                                <input type="radio" name="type_site" value="<?= e($id) ?>" <?= $id === $tarifs['type_site']['default'] ? 'checked' : '' ?>>
                                <span class="tarifs-puce tarifs-puce--radio"></span>
                                <div class="tarifs-option-content">
                                    <p class="tarifs-option-label"><?= e($opt['label']) ?></p>
                                    <p class="tarifs-option-desc"><?= e($opt['description']) ?></p>
                                </div>
                                <span class="tarifs-option-prix"><?= formatPrix($opt['creation']) ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <!-- Question 2 : Options -->
                    <section class="tarifs-section">
                        <h2 class="tarifs-h2">2. <?= e($tarifs['options']['label']) ?></h2>
                        <div class="tarifs-options" role="group" aria-label="Options supplémentaires">
                            <?php foreach ($tarifs['options']['options'] as $id => $opt): ?>
                            <label class="tarifs-option">
                                <input type="checkbox" name="options[]" value="<?= e($id) ?>">
                                <span class="tarifs-puce tarifs-puce--checkbox"></span>
                                <div class="tarifs-option-content">
                                    <p class="tarifs-option-label"><?= e($opt['label']) ?></p>
                                    <p class="tarifs-option-desc"><?= e($opt['description']) ?></p>
                                </div>
                                <span class="tarifs-option-prix">
                                    + <?= formatPrix($opt['creation']) ?>
                                    <?php if ($opt['mensuel'] > 0): ?>
                                    <span class="tarifs-option-prix-mensuel">+ <?= $opt['mensuel'] ?> €/mois</span>
                                    <?php endif; ?>
                                </span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <!-- Question 3 : Suivi -->
                    <section class="tarifs-section">
                        <h2 class="tarifs-h2">3. <?= e($tarifs['suivi']['label']) ?></h2>

                        <!-- Bloc inclus -->
                        <div class="tarifs-inclus">
                            <div class="tarifs-inclus-header">
                                <div>
                                    <p class="tarifs-inclus-label"><?= e($tarifs['suivi']['inclus']['label']) ?></p>
                                    <p class="tarifs-inclus-desc"><?= e($tarifs['suivi']['inclus']['description']) ?></p>
                                </div>
                                <span class="tarifs-inclus-prix"><?= $tarifs['suivi']['inclus']['mensuel'] ?> €/mois</span>
                            </div>
                        </div>

                        <div class="tarifs-options" role="group" aria-label="Options de suivi">
                            <?php foreach ($tarifs['suivi']['options'] as $id => $opt): ?>
                            <label class="tarifs-option">
                                <input type="checkbox" name="suivi[]" value="<?= e($id) ?>">
                                <span class="tarifs-puce tarifs-puce--checkbox"></span>
                                <div class="tarifs-option-content">
                                    <p class="tarifs-option-label"><?= e($opt['label']) ?></p>
                                    <p class="tarifs-option-desc"><?= e($opt['description']) ?></p>
                                </div>
                                <span class="tarifs-option-prix">+ <?= $opt['mensuel'] ?> €/mois</span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <!-- Question 4 : Délai -->
                    <section class="tarifs-section">
                        <h2 class="tarifs-h2">4. <?= e($tarifs['delai']['label']) ?></h2>
                        <div class="tarifs-options" role="radiogroup" aria-label="Délai">
                            <?php foreach ($tarifs['delai']['options'] as $id => $opt): ?>
                            <label class="tarifs-option <?= $id === $tarifs['delai']['default'] ? 'selected' : '' ?>">
                                <input type="radio" name="delai" value="<?= e($id) ?>" <?= $id === $tarifs['delai']['default'] ? 'checked' : '' ?>>
                                <span class="tarifs-puce tarifs-puce--radio"></span>
                                <div class="tarifs-option-content">
                                    <p class="tarifs-option-label"><?= e($opt['label']) ?></p>
                                    <p class="tarifs-option-desc"><?= e($opt['description']) ?></p>
                                </div>
                                <span class="tarifs-option-prix">
                                    <?php if ($opt['majoration'] > 0): ?>
                                    + <?= intval($opt['majoration'] * 100) ?> %
                                    <?php else: ?>
                                    Inclus
                                    <?php endif; ?>
                                </span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </section>

                </div>

                <!-- Récapitulatif -->
                <aside class="tarifs-recap" id="tarifs-recap">
                    <h2 class="tarifs-recap-titre">Votre estimation</h2>

                    <div class="tarifs-totaux" aria-live="polite" aria-atomic="true">
                        <div class="tarifs-total-bloc">
                            <p class="tarifs-total-label">Création du site, une seule fois<br><small>à partir de</small></p>
                            <p class="tarifs-total-montant" id="total-creation">990 €</p>
                        </div>
                        <div class="tarifs-total-bloc">
                            <p class="tarifs-total-label">Suivi, chaque mois</p>
                            <p class="tarifs-total-montant" id="total-mensuel">39 €/mois</p>
                        </div>
                    </div>

                    <div class="tarifs-detail">
                        <ul class="tarifs-detail-liste" id="detail-creation">
                            <li class="tarifs-detail-ligne">
                                <span class="tarifs-detail-ligne-nom">Site vitrine, 5 pages</span>
                                <span class="tarifs-detail-ligne-prix">990 €</span>
                            </li>
                        </ul>
                        <ul class="tarifs-detail-liste" id="detail-mensuel" style="margin-top: 12px;">
                            <li class="tarifs-detail-ligne">
                                <span class="tarifs-detail-ligne-nom">Hébergement, maintenance, modifications</span>
                                <span class="tarifs-detail-ligne-prix">39 €</span>
                            </li>
                        </ul>
                    </div>

                    <form id="tarifs-form" class="tarifs-form" action="envoi-estimation.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">
                        <input type="hidden" name="timestamp" id="tarifs-timestamp" value="<?= time() ?>">
                        <input type="hidden" name="choix" id="tarifs-choix" value="">

                        <!-- Honeypot -->
                        <div class="tarifs-hp" aria-hidden="true">
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>

                        <div class="tarifs-form-groupe">
                            <input type="email" name="email" class="tarifs-form-input" placeholder="vous@entreprise.fr" required aria-label="Adresse e-mail">
                        </div>
                        <div class="tarifs-form-groupe">
                            <input type="tel" name="telephone" class="tarifs-form-input" placeholder="06 00 00 00 00 (facultatif)" aria-label="Téléphone">
                        </div>
                        <?php if ($turnstileSiteKey): ?>
                        <div class="tarifs-form-groupe" style="display: flex; justify-content: center;">
                            <div class="cf-turnstile" data-sitekey="<?= e($turnstileSiteKey) ?>" data-theme="dark"></div>
                        </div>
                        <?php endif; ?>
                        <button type="submit" class="tarifs-form-btn">Recevoir mon estimation</button>
                    </form>

                    <a href="tel:<?= e(str_replace(' ', '', $config['contact_tel'] ?? '')) ?>" class="tarifs-rappel">
                        Ou être rappelé : <?= e($config['contact_tel'] ?? '06 00 00 00 00') ?>
                    </a>

                    <p class="tarifs-mentions">
                        Estimation indicative, sans valeur de devis. Le devis définitif est établi après un échange.<br>
                        <?= e($config['tva_mention'] ?? 'Prix HT / TVA non applicable, art. 293 B du CGI') ?>
                    </p>
                    <p class="tarifs-mentions" style="margin-top: 12px;">
                        Votre e-mail sert uniquement à vous envoyer l'estimation et à vous recontacter.
                        <a href="politique-confidentialite.html">Politique de confidentialité</a>
                    </p>
                </aside>
            </div>
        </div>
    </main>

    <!-- Barre mobile fixe -->
    <div class="tarifs-barre-mobile">
        <div class="tarifs-barre-mobile-inner">
            <div class="tarifs-barre-totaux">
                <div class="tarifs-barre-total">
                    <span class="tarifs-barre-label">Création</span>
                    <span class="tarifs-barre-montant" id="barre-montant-creation">990 €</span>
                </div>
                <div class="tarifs-barre-total">
                    <span class="tarifs-barre-label">Mensuel</span>
                    <span class="tarifs-barre-montant" id="barre-montant-mensuel">39 €/mois</span>
                </div>
            </div>
            <button type="button" class="tarifs-barre-btn" id="barre-btn">Voir mon estimation</button>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>© 2025 Pertec.fr - Création de sites internet</p>
                <div class="footer-legal">
                    <a href="mentions-legales.html">Mentions légales</a>
                    <a href="politique-confidentialite.html">Confidentialité</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="js/main.js"></script>
    <script>
        // Injection de la grille tarifaire pour le JS
        window.PERTEC_TARIFS = <?= json_encode($tarifs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    </script>
    <script src="js/tarifs.js"></script>
</body>
</html>
