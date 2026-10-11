<?php
require_once __DIR__ . '/config.php';

$error = '';

// Déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Traitement du login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';

    if (check_login($user, $pass)) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Identifiants incorrects';
    }
}

// Si déjà connecté, rediriger
if (is_logged_in()) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - <?= e(SITE_NAME) ?></title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .login-box {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 400px;
        }
        .logo { text-align: center; margin-bottom: 2rem; }
        .logo h1 { color: #fff; font-size: 1.75rem; }
        .logo span { background: linear-gradient(135deg, #6366f1, #8b5cf6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .logo p { color: #9ca3af; font-size: 0.875rem; margin-top: 0.5rem; }
        .form-group { margin-bottom: 1.25rem; }
        label { display: block; color: #9ca3af; font-size: 0.875rem; margin-bottom: 0.5rem; }
        input {
            width: 100%;
            padding: 0.875rem 1rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            transition: border-color 0.2s;
        }
        input:focus { outline: none; border-color: #6366f1; }
        .btn {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: none;
            border-radius: 8px;
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(99,102,241,0.3); }
        .error {
            background: rgba(239,68,68,0.1);
            border: 1px solid rgba(239,68,68,0.3);
            color: #f87171;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            font-size: 0.875rem;
        }
        .back-link { display: block; text-align: center; margin-top: 1.5rem; color: #9ca3af; text-decoration: none; font-size: 0.875rem; }
        .back-link:hover { color: #fff; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo">
            <h1>Per<span>Tec</span></h1>
            <p>Administration</p>
        </div>

        <?php if ($error): ?>
            <div class="error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="username">Identifiant</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn">Se connecter</button>
        </form>

        <a href="../index.html" class="back-link">Retour au site</a>
    </div>
</body>
</html>
