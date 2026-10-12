<?php
require_once __DIR__ . '/config.php';
require_login();

$settings = get_settings();
$success = false;
$error = '';

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settings = [
        'site_name' => trim($_POST['site_name'] ?? ''),
        'site_tagline' => trim($_POST['site_tagline'] ?? ''),
        'contact_email' => trim($_POST['contact_email'] ?? ''),
        'contact_phone' => trim($_POST['contact_phone'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
        'social_facebook' => trim($_POST['social_facebook'] ?? ''),
        'social_linkedin' => trim($_POST['social_linkedin'] ?? ''),
        'social_instagram' => trim($_POST['social_instagram'] ?? ''),
        'analytics_id' => trim($_POST['analytics_id'] ?? ''),
        'smtp_host' => trim($_POST['smtp_host'] ?? ''),
        'smtp_port' => trim($_POST['smtp_port'] ?? '587'),
        'smtp_user' => trim($_POST['smtp_user'] ?? ''),
        'smtp_pass' => trim($_POST['smtp_pass'] ?? ''),
        'smtp_from' => trim($_POST['smtp_from'] ?? ''),
        'smtp_from_name' => trim($_POST['smtp_from_name'] ?? ''),
        'turnstile_site_key' => trim($_POST['turnstile_site_key'] ?? ''),
        'turnstile_secret_key' => trim($_POST['turnstile_secret_key'] ?? ''),
    ];

    if (save_settings($settings)) {
        $success = true;
    } else {
        $error = 'Erreur lors de la sauvegarde';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - Admin Pertec</title>
    <link rel="stylesheet" href="style.css?v=<?= filemtime(__DIR__ . '/style.css') ?>">
</head>
<body>
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>

        <main class="admin-main">
            <header class="admin-header">
                <h1>Paramètres</h1>
            </header>

            <?php if ($success): ?>
                <div class="alert alert-success">Paramètres sauvegardés avec succès !</div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="card">
                    <h2>Informations générales</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="site_name">Nom du site</label>
                            <input type="text" id="site_name" name="site_name" value="<?= e($settings['site_name']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="site_tagline">Slogan</label>
                            <input type="text" id="site_tagline" name="site_tagline" value="<?= e($settings['site_tagline']) ?>">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2>Contact</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="contact_email">Email de contact</label>
                            <input type="email" id="contact_email" name="contact_email" value="<?= e($settings['contact_email']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="contact_phone">Téléphone</label>
                            <input type="tel" id="contact_phone" name="contact_phone" value="<?= e($settings['contact_phone']) ?>">
                        </div>
                        <div class="form-group full-width">
                            <label for="address">Adresse</label>
                            <input type="text" id="address" name="address" value="<?= e($settings['address']) ?>">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2>Réseaux sociaux</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="social_facebook">Facebook (URL)</label>
                            <input type="url" id="social_facebook" name="social_facebook" value="<?= e($settings['social_facebook']) ?>" placeholder="https://facebook.com/...">
                        </div>
                        <div class="form-group">
                            <label for="social_linkedin">LinkedIn (URL)</label>
                            <input type="url" id="social_linkedin" name="social_linkedin" value="<?= e($settings['social_linkedin']) ?>" placeholder="https://linkedin.com/in/...">
                        </div>
                        <div class="form-group">
                            <label for="social_instagram">Instagram (URL)</label>
                            <input type="url" id="social_instagram" name="social_instagram" value="<?= e($settings['social_instagram']) ?>" placeholder="https://instagram.com/...">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2>Configuration SMTP (emails)</h2>
                    <p class="form-hint">Configurez un serveur SMTP pour envoyer les emails de contact. Laissez vide pour utiliser la fonction mail() par défaut.</p>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="smtp_host">Serveur SMTP</label>
                            <input type="text" id="smtp_host" name="smtp_host" value="<?= e($settings['smtp_host']) ?>" placeholder="smtp.exemple.com">
                        </div>
                        <div class="form-group">
                            <label for="smtp_port">Port</label>
                            <input type="number" id="smtp_port" name="smtp_port" value="<?= e($settings['smtp_port']) ?>" placeholder="587">
                        </div>
                        <div class="form-group">
                            <label for="smtp_user">Utilisateur SMTP</label>
                            <input type="text" id="smtp_user" name="smtp_user" value="<?= e($settings['smtp_user']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="smtp_pass">Mot de passe SMTP</label>
                            <input type="password" id="smtp_pass" name="smtp_pass" value="<?= e($settings['smtp_pass']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="smtp_from">Email expéditeur</label>
                            <input type="email" id="smtp_from" name="smtp_from" value="<?= e($settings['smtp_from']) ?>" placeholder="contact@pertec.fr">
                        </div>
                        <div class="form-group">
                            <label for="smtp_from_name">Nom expéditeur</label>
                            <input type="text" id="smtp_from_name" name="smtp_from_name" value="<?= e($settings['smtp_from_name']) ?>" placeholder="Pertec">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2>Protection anti-spam (Cloudflare Turnstile)</h2>
                    <p class="form-hint">Créez un compte gratuit sur <a href="https://dash.cloudflare.com/sign-up?to=/:account/turnstile" target="_blank">Cloudflare Turnstile</a> pour obtenir vos clés.</p>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="turnstile_site_key">Clé du site (Site Key)</label>
                            <input type="text" id="turnstile_site_key" name="turnstile_site_key" value="<?= e($settings['turnstile_site_key'] ?? '') ?>" placeholder="0x...">
                        </div>
                        <div class="form-group">
                            <label for="turnstile_secret_key">Clé secrète (Secret Key)</label>
                            <input type="password" id="turnstile_secret_key" name="turnstile_secret_key" value="<?= e($settings['turnstile_secret_key'] ?? '') ?>" placeholder="0x...">
                        </div>
                    </div>
                </div>

                <div class="card">
                    <h2>Analytics</h2>
                    <div class="form-group">
                        <label for="analytics_id">Google Analytics ID</label>
                        <input type="text" id="analytics_id" name="analytics_id" value="<?= e($settings['analytics_id']) ?>" placeholder="G-XXXXXXXXXX">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Enregistrer les paramètres</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
