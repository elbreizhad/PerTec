<?php
/**
 * Pertec - Envoi d'estimation tarifaire
 * Gère la soumission du formulaire de tarifs
 */

session_start();
require_once __DIR__ . '/includes/turnstile.php';

// Headers
header('Content-Type: application/json; charset=utf-8');

// Réponse par défaut
$response = ['success' => false, 'message' => 'Une erreur est survenue.'];

try {
    // Vérifier la méthode
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Méthode non autorisée.');
    }

    // CSRF
    $token = $_POST['csrf_token'] ?? '';
    if (empty($token) || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        throw new Exception('Session expirée. Veuillez recharger la page.');
    }

    // Honeypot
    if (!empty($_POST['website'])) {
        // Bot détecté, on simule un succès
        $response = ['success' => true];
        echo json_encode($response);
        exit;
    }

    // Vérification Turnstile (CAPTCHA)
    $turnstileSecret = get_turnstile_secret_key();
    if ($turnstileSecret) {
        $turnstileToken = $_POST['cf-turnstile-response'] ?? '';
        $turnstileResult = verify_turnstile($turnstileToken, $turnstileSecret, $_SERVER['REMOTE_ADDR'] ?? null);

        if (!$turnstileResult['success']) {
            throw new Exception($turnstileResult['error']);
        }
    }

    // Délai anti-spam (3 secondes minimum)
    $timestamp = intval($_POST['timestamp'] ?? 0);
    $now = time() * 1000; // JS timestamp en ms
    if ($timestamp > 0 && ($now - $timestamp) < 3000) {
        throw new Exception('Veuillez patienter quelques secondes.');
    }

    // Rate limiting (5 soumissions par IP par heure)
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $rateFile = __DIR__ . '/data/rate_tarifs.json';
    $rateData = [];

    if (file_exists($rateFile)) {
        $rateData = json_decode(file_get_contents($rateFile), true) ?: [];
    }

    // Nettoyer les entrées expirées (> 1 heure)
    $oneHourAgo = time() - 3600;
    foreach ($rateData as $key => $data) {
        if (($data['first'] ?? 0) < $oneHourAgo) {
            unset($rateData[$key]);
        }
    }

    $ipHash = md5($ip);
    if (isset($rateData[$ipHash])) {
        if ($rateData[$ipHash]['count'] >= 5) {
            throw new Exception('Trop de demandes. Réessayez dans une heure.');
        }
        $rateData[$ipHash]['count']++;
    } else {
        $rateData[$ipHash] = ['first' => time(), 'count' => 1];
    }

    file_put_contents($rateFile, json_encode($rateData), LOCK_EX);

    // Validation email
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    if (!$email) {
        throw new Exception('Adresse e-mail invalide.');
    }

    $telephone = preg_replace('/[^0-9+\s]/', '', $_POST['telephone'] ?? '');
    $telephone = trim($telephone);

    // Charger la grille tarifaire
    $tarifs = require __DIR__ . '/config/tarifs.php';

    // Décoder les choix
    $choixJson = $_POST['choix'] ?? '{}';
    $choix = json_decode($choixJson, true) ?: [];

    // Recalcul serveur (JAMAIS confiance au client)
    $typeSite = $choix['type_site'] ?? 'vitrine5';
    $options = $choix['options'] ?? [];
    $suivi = $choix['suivi'] ?? [];
    $delai = $choix['delai'] ?? 'normal';

    // Valider type_site
    if (!isset($tarifs['type_site']['options'][$typeSite])) {
        $typeSite = 'vitrine5';
    }

    // Calcul création
    $creation = 0;
    $surDevis = false;
    $lignesCreation = [];

    $typeSiteData = $tarifs['type_site']['options'][$typeSite];
    if ($typeSiteData['creation'] === null) {
        $surDevis = true;
        $lignesCreation[] = ['nom' => $typeSiteData['label'], 'prix' => 'Sur devis'];
    } else {
        $creation = $typeSiteData['creation'];
        $lignesCreation[] = ['nom' => $typeSiteData['label'], 'prix' => $creation];
    }

    // Options
    $lignesOptions = [];
    if (is_array($options)) {
        foreach ($options as $optId) {
            if (isset($tarifs['options']['options'][$optId])) {
                $opt = $tarifs['options']['options'][$optId];
                $creation += $opt['creation'] ?? 0;
                if ($opt['creation'] > 0) {
                    $lignesCreation[] = ['nom' => $opt['label'], 'prix' => $opt['creation']];
                }
                if (($opt['mensuel'] ?? 0) > 0) {
                    $lignesOptions[] = ['nom' => $opt['label'], 'prix' => $opt['mensuel']];
                }
            }
        }
    }

    // Calcul mensuel
    $mensuel = 39; // Base toujours incluse
    $lignesMensuel = [['nom' => 'Hébergement, maintenance, modifications', 'prix' => 39]];

    // Ajouter options mensuelles
    foreach ($lignesOptions as $ligne) {
        $mensuel += $ligne['prix'];
        $lignesMensuel[] = $ligne;
    }

    // Suivi
    if (is_array($suivi)) {
        foreach ($suivi as $suiviId) {
            if (isset($tarifs['suivi']['options'][$suiviId])) {
                $suiviOpt = $tarifs['suivi']['options'][$suiviId];
                $mensuel += $suiviOpt['mensuel'] ?? 0;
                if (($suiviOpt['mensuel'] ?? 0) > 0) {
                    $lignesMensuel[] = ['nom' => $suiviOpt['label'], 'prix' => $suiviOpt['mensuel']];
                }
            }
        }
    }

    // Majoration express
    $majorationPourcent = 0;
    if ($delai === 'express' && !$surDevis && isset($tarifs['delai']['options']['express'])) {
        $majorationPourcent = ($tarifs['delai']['options']['express']['majoration'] ?? 0.20) * 100;
        $supplement = round($creation * ($majorationPourcent / 100) / 10) * 10;
        $creation += $supplement;
        if ($supplement > 0) {
            $lignesCreation[] = ['nom' => 'Livraison express (+' . intval($majorationPourcent) . '%)', 'prix' => $supplement];
        }
    }

    // Formater les prix
    function formatPrixFr($prix) {
        if ($prix === 'Sur devis') return $prix;
        return number_format($prix, 0, ',', ' ') . ' €';
    }

    // Construire le détail texte
    $detailCreationTexte = '';
    foreach ($lignesCreation as $ligne) {
        $detailCreationTexte .= "  • " . $ligne['nom'] . " : " . formatPrixFr($ligne['prix']) . "\n";
    }

    $detailMensuelTexte = '';
    foreach ($lignesMensuel as $ligne) {
        $detailMensuelTexte .= "  • " . $ligne['nom'] . " : " . formatPrixFr($ligne['prix']) . "/mois\n";
    }

    // Charger les paramètres admin
    $settings_file = __DIR__ . '/admin/settings.json';
    $settings = file_exists($settings_file) ? json_decode(file_get_contents($settings_file), true) : [];
    $adminEmail = $settings['contact_email'] ?? $tarifs['config']['contact_email'] ?? 'contact@pertec.fr';
    $contactTel = $settings['contact_phone'] ?? $tarifs['config']['contact_tel'] ?? '';
    $siteName = $settings['site_name'] ?? 'Pertec';

    // Email HTML pour le client
    $totalCreationAffiche = $surDevis ? 'Sur devis' : formatPrixFr($creation);
    $totalMensuelAffiche = formatPrixFr($mensuel) . '/mois';

    $htmlClient = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre estimation - {$siteName}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #1a1a1a; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1 style="color: #2563eb; font-size: 24px; margin-bottom: 24px;">Votre estimation de site internet</h1>

    <div style="background: #f8fafc; border-radius: 8px; padding: 20px; margin-bottom: 24px;">
        <h2 style="font-size: 18px; margin: 0 0 16px 0;">Création du site</h2>
        <p style="font-size: 28px; font-weight: 600; color: #2563eb; margin: 0 0 16px 0;">{$totalCreationAffiche}</p>
        <ul style="margin: 0; padding-left: 20px; color: #64748b;">
HTML;

    foreach ($lignesCreation as $ligne) {
        $prixFormate = formatPrixFr($ligne['prix']);
        $htmlClient .= "<li>{$ligne['nom']} : {$prixFormate}</li>";
    }

    $htmlClient .= <<<HTML
        </ul>
    </div>

    <div style="background: #f8fafc; border-radius: 8px; padding: 20px; margin-bottom: 24px;">
        <h2 style="font-size: 18px; margin: 0 0 16px 0;">Suivi mensuel</h2>
        <p style="font-size: 28px; font-weight: 600; color: #2563eb; margin: 0 0 16px 0;">{$totalMensuelAffiche}</p>
        <ul style="margin: 0; padding-left: 20px; color: #64748b;">
HTML;

    foreach ($lignesMensuel as $ligne) {
        $prixFormate = formatPrixFr($ligne['prix']);
        $htmlClient .= "<li>{$ligne['nom']} : {$prixFormate}/mois</li>";
    }

    $htmlClient .= <<<HTML
        </ul>
    </div>

    <p style="color: #64748b; font-size: 14px;">
        Cette estimation est indicative et sans valeur de devis.<br>
        Je vous recontacte sous 24 h pour en discuter ensemble.
    </p>

    <p style="margin-top: 24px;">
        À très vite,<br>
        <strong>{$siteName}</strong><br>
HTML;

    if ($contactTel) {
        $htmlClient .= "<a href=\"tel:" . preg_replace('/\s/', '', $contactTel) . "\" style=\"color: #2563eb;\">{$contactTel}</a>";
    }

    $htmlClient .= <<<HTML
    </p>
</body>
</html>
HTML;

    // Version texte pour le client
    $texteClient = <<<TEXT
VOTRE ESTIMATION DE SITE INTERNET
==================================

CRÉATION DU SITE : {$totalCreationAffiche}
{$detailCreationTexte}
SUIVI MENSUEL : {$totalMensuelAffiche}
{$detailMensuelTexte}
Cette estimation est indicative et sans valeur de devis.
Je vous recontacte sous 24 h pour en discuter ensemble.

À très vite,
{$siteName}
TEXT;

    if ($contactTel) {
        $texteClient .= "\n" . $contactTel;
    }

    // Email pour l'admin
    $dateFormatee = date('d/m/Y à H:i');
    $telephoneAffiche = $telephone ?: 'Non renseigné';

    $htmlAdmin = <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle estimation - {$siteName}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #1a1a1a; max-width: 600px; margin: 0 auto; padding: 20px;">
    <h1 style="color: #2563eb; font-size: 24px; margin-bottom: 24px;">📩 Nouvelle demande d'estimation</h1>

    <div style="background: #f0f9ff; border-left: 4px solid #2563eb; padding: 16px; margin-bottom: 24px;">
        <p style="margin: 0 0 8px 0;"><strong>Email :</strong> <a href="mailto:{$email}">{$email}</a></p>
        <p style="margin: 0 0 8px 0;"><strong>Téléphone :</strong> {$telephoneAffiche}</p>
        <p style="margin: 0;"><strong>Date :</strong> {$dateFormatee}</p>
    </div>

    <h2 style="font-size: 18px; margin: 24px 0 16px 0;">Configuration choisie</h2>

    <table style="width: 100%; border-collapse: collapse;">
        <tr style="background: #f8fafc;">
            <td style="padding: 12px; border: 1px solid #e2e8f0;"><strong>Création</strong></td>
            <td style="padding: 12px; border: 1px solid #e2e8f0; text-align: right; font-weight: 600; color: #2563eb;">{$totalCreationAffiche}</td>
        </tr>
        <tr>
            <td style="padding: 12px; border: 1px solid #e2e8f0;"><strong>Mensuel</strong></td>
            <td style="padding: 12px; border: 1px solid #e2e8f0; text-align: right; font-weight: 600; color: #2563eb;">{$totalMensuelAffiche}</td>
        </tr>
    </table>

    <h3 style="font-size: 16px; margin: 24px 0 12px 0;">Détail création</h3>
    <ul style="margin: 0; padding-left: 20px;">
HTML;

    foreach ($lignesCreation as $ligne) {
        $prixFormate = formatPrixFr($ligne['prix']);
        $htmlAdmin .= "<li>{$ligne['nom']} : {$prixFormate}</li>";
    }

    $htmlAdmin .= <<<HTML
    </ul>

    <h3 style="font-size: 16px; margin: 24px 0 12px 0;">Détail mensuel</h3>
    <ul style="margin: 0; padding-left: 20px;">
HTML;

    foreach ($lignesMensuel as $ligne) {
        $prixFormate = formatPrixFr($ligne['prix']);
        $htmlAdmin .= "<li>{$ligne['nom']} : {$prixFormate}/mois</li>";
    }

    $htmlAdmin .= <<<HTML
    </ul>

    <p style="margin-top: 24px; padding: 16px; background: #fef3c7; border-radius: 8px; color: #92400e;">
        💡 Pensez à recontacter ce prospect sous 24 h !
    </p>
</body>
</html>
HTML;

    // Version texte admin
    $texteAdmin = <<<TEXT
NOUVELLE DEMANDE D'ESTIMATION
=============================

Email : {$email}
Téléphone : {$telephoneAffiche}
Date : {$dateFormatee}

CONFIGURATION CHOISIE
---------------------
Création : {$totalCreationAffiche}
Mensuel : {$totalMensuelAffiche}

Détail création :
{$detailCreationTexte}
Détail mensuel :
{$detailMensuelTexte}
TEXT;

    // Envoyer les emails
    require_once __DIR__ . '/admin/mailer.php';
    $mailer = new PertecMailer();

    // Email au client
    $mailClient = $mailer->send(
        $email,
        'Votre estimation - ' . $siteName,
        $htmlClient,
        $texteClient
    );

    // Email à l'admin
    $mailAdmin = $mailer->send(
        $adminEmail,
        '📩 Nouvelle estimation de ' . $email,
        $htmlAdmin,
        $texteAdmin,
        $email // Reply-To
    );

    // Log CSV
    $csvFile = __DIR__ . '/data/estimations.csv';
    $csvExists = file_exists($csvFile);

    $fp = fopen($csvFile, 'a');
    if ($fp) {
        flock($fp, LOCK_EX);

        // En-têtes si nouveau fichier
        if (!$csvExists) {
            fputcsv($fp, [
                'date',
                'email',
                'telephone',
                'type_site',
                'options',
                'suivi',
                'delai',
                'creation',
                'mensuel',
                'ip'
            ], ';');
        }

        fputcsv($fp, [
            date('Y-m-d H:i:s'),
            $email,
            $telephone,
            $typeSite,
            implode(',', $options),
            implode(',', $suivi),
            $delai,
            $surDevis ? 'devis' : $creation,
            $mensuel,
            $ip
        ], ';');

        flock($fp, LOCK_UN);
        fclose($fp);
    }

    $response = ['success' => true];

} catch (Exception $e) {
    $response = ['success' => false, 'message' => $e->getMessage()];
}

echo json_encode($response);
