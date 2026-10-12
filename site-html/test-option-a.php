<?php
/**
 * Test Option A - Fichier PHP servi via réécriture .html
 * Accéder à : https://pertec.fr/test-option-a.html
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test Option A - Pertec</title>
    <style>
        body { font-family: system-ui; max-width: 600px; margin: 50px auto; padding: 20px; background: #0a0a0f; color: #fff; }
        .success { background: #22c55e; color: #fff; padding: 20px; border-radius: 8px; }
        code { background: #1e1e2e; padding: 2px 8px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="success">
        <h1>✅ Option A fonctionne !</h1>
        <p>Ce fichier PHP est servi via l'URL <code>.html</code></p>
        <p>URL demandée : <code><?= htmlspecialchars($_SERVER['REQUEST_URI']) ?></code></p>
        <p>Fichier exécuté : <code><?= htmlspecialchars($_SERVER['SCRIPT_FILENAME']) ?></code></p>
    </div>
    <p style="margin-top: 20px;">
        <a href="/" style="color: #8b5cf6;">← Retour à l'accueil</a>
    </p>
</body>
</html>
