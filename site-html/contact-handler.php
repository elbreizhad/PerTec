<?php
/**
 * Gestionnaire de formulaire de contact Pertec
 * Enregistre les messages et envoie un email de notification
 */

// Charger la config admin si disponible
$settings = [];
$settings_file = __DIR__ . '/admin/settings.json';
if (file_exists($settings_file)) {
    $settings = json_decode(file_get_contents($settings_file), true) ?: [];
}

// Configuration par défaut
$contact_email = $settings['contact_email'] ?? 'contact@pertec.fr';
$site_name = $settings['site_name'] ?? 'Pertec';

// Headers CORS pour les requêtes AJAX
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Vérifier la méthode
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

// Récupérer et valider les données
$nom = trim($_POST['nom'] ?? '');
$entreprise = trim($_POST['entreprise'] ?? '');
$email = trim($_POST['email'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');
$ville = trim($_POST['ville'] ?? '');
$projet = trim($_POST['projet'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validation
$errors = [];

if (empty($nom)) {
    $errors[] = 'Le nom est requis';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email invalide';
}

if (empty($telephone)) {
    $errors[] = 'Le téléphone est requis';
}

// Protection anti-spam basique (honeypot)
if (!empty($_POST['website'])) {
    // Champ honeypot rempli = bot
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Message envoyé']);
    exit;
}

// Si erreurs
if (!empty($errors)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Créer le message
$new_message = [
    'date' => date('d/m/Y H:i'),
    'nom' => $nom,
    'entreprise' => $entreprise,
    'email' => $email,
    'telephone' => $telephone,
    'ville' => $ville,
    'projet' => $projet,
    'message' => $message,
    'read' => false,
    'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
];

// Sauvegarder dans le fichier JSON
$messages_file = __DIR__ . '/admin/messages.json';
$messages = [];
if (file_exists($messages_file)) {
    $messages = json_decode(file_get_contents($messages_file), true) ?: [];
}
$messages[] = $new_message;
file_put_contents($messages_file, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Préparer l'email
$subject = "[$site_name] Nouvelle demande de devis - $nom";

$email_body = "
Nouvelle demande de devis reçue sur $site_name

--------------------------------------------------
INFORMATIONS CLIENT
--------------------------------------------------

Nom : $nom
Entreprise : $entreprise
Email : $email
Téléphone : $telephone
Ville : $ville
Type de projet : $projet

--------------------------------------------------
MESSAGE
--------------------------------------------------

$message

--------------------------------------------------
INFORMATIONS TECHNIQUES
--------------------------------------------------
Date : " . date('d/m/Y H:i:s') . "
IP : " . ($_SERVER['REMOTE_ADDR'] ?? 'inconnue') . "
";

// Envoyer l'email
$headers = [
    'From' => ($settings['smtp_from_name'] ?? $site_name) . ' <' . ($settings['smtp_from'] ?? $contact_email) . '>',
    'Reply-To' => "$nom <$email>",
    'Content-Type' => 'text/plain; charset=UTF-8',
    'X-Mailer' => 'PHP/' . phpversion(),
];

$headers_string = '';
foreach ($headers as $key => $value) {
    $headers_string .= "$key: $value\r\n";
}

$email_sent = @mail($contact_email, $subject, $email_body, $headers_string);

// Email de confirmation au client
$confirm_subject = "Votre demande de devis - $site_name";
$confirm_body = "
Bonjour $nom,

Merci pour votre demande de devis !

J'ai bien reçu votre message et je vous recontacterai dans les 24 heures.

Récapitulatif de votre demande :
- Entreprise : $entreprise
- Projet : $projet

À très bientôt,
$site_name

--
Ce message est automatique, merci de ne pas y répondre directement.
Pour me contacter : $contact_email
";

$confirm_headers = [
    'From' => "$site_name <" . ($settings['smtp_from'] ?? $contact_email) . ">",
    'Content-Type' => 'text/plain; charset=UTF-8',
];

$confirm_headers_string = '';
foreach ($confirm_headers as $key => $value) {
    $confirm_headers_string .= "$key: $value\r\n";
}

@mail($email, $confirm_subject, $confirm_body, $confirm_headers_string);

// Réponse
echo json_encode([
    'success' => true,
    'message' => 'Votre message a été envoyé ! Je vous recontacte sous 24h.',
    'email_sent' => $email_sent
]);
