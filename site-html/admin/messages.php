<?php
require_once __DIR__ . '/config.php';
require_login();

$messages_file = __DIR__ . '/messages.json';
$messages = [];
if (file_exists($messages_file)) {
    $messages = json_decode(file_get_contents($messages_file), true) ?: [];
}

// Marquer comme lu
if (isset($_GET['view']) && is_numeric($_GET['view'])) {
    $idx = (int)$_GET['view'];
    if (isset($messages[$idx])) {
        $messages[$idx]['read'] = true;
        file_put_contents($messages_file, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

// Supprimer un message
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $idx = (int)$_GET['delete'];
    if (isset($messages[$idx])) {
        array_splice($messages, $idx, 1);
        file_put_contents($messages_file, json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header('Location: messages.php?deleted=1');
        exit;
    }
}

// Vue d'un message
$viewing = null;
if (isset($_GET['view']) && is_numeric($_GET['view'])) {
    $idx = (int)$_GET['view'];
    if (isset($messages[$idx])) {
        $viewing = $messages[$idx];
        $viewing_idx = $idx;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages - Admin Pertec</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>

        <main class="admin-main">
            <header class="admin-header">
                <h1>Messages</h1>
                <div class="header-actions">
                    <span class="badge-info"><?= count($messages) ?> message(s)</span>
                </div>
            </header>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">Message supprimé.</div>
            <?php endif; ?>

            <?php if ($viewing): ?>
                <div class="card message-detail">
                    <div class="message-header">
                        <a href="messages.php" class="btn btn-secondary">&larr; Retour</a>
                        <a href="messages.php?delete=<?= $viewing_idx ?>" class="btn btn-danger" onclick="return confirm('Supprimer ce message ?')">Supprimer</a>
                    </div>

                    <div class="message-meta">
                        <p><strong>Date :</strong> <?= e($viewing['date'] ?? '') ?></p>
                        <p><strong>Nom :</strong> <?= e($viewing['nom'] ?? '') ?></p>
                        <p><strong>Entreprise :</strong> <?= e($viewing['entreprise'] ?? '-') ?></p>
                        <p><strong>Email :</strong> <a href="mailto:<?= e($viewing['email'] ?? '') ?>"><?= e($viewing['email'] ?? '') ?></a></p>
                        <p><strong>Téléphone :</strong> <a href="tel:<?= e($viewing['telephone'] ?? '') ?>"><?= e($viewing['telephone'] ?? '') ?></a></p>
                        <p><strong>Ville :</strong> <?= e($viewing['ville'] ?? '-') ?></p>
                        <p><strong>Type de projet :</strong> <?= e($viewing['projet'] ?? '-') ?></p>
                    </div>

                    <div class="message-body">
                        <h3>Message</h3>
                        <p><?= nl2br(e($viewing['message'] ?? '')) ?></p>
                    </div>

                    <div class="message-actions">
                        <a href="mailto:<?= e($viewing['email'] ?? '') ?>?subject=Re: Votre demande de devis - Pertec" class="btn btn-primary">Répondre par email</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <?php if (empty($messages)): ?>
                        <p class="empty-state">Aucun message reçu pour le moment.</p>
                    <?php else: ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Nom</th>
                                    <th>Email</th>
                                    <th>Téléphone</th>
                                    <th>Projet</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_reverse($messages, true) as $i => $msg): ?>
                                <tr class="<?= ($msg['read'] ?? false) ? '' : 'unread' ?>">
                                    <td><?= e($msg['date'] ?? '') ?></td>
                                    <td><?= e($msg['nom'] ?? '') ?></td>
                                    <td><?= e($msg['email'] ?? '') ?></td>
                                    <td><?= e($msg['telephone'] ?? '') ?></td>
                                    <td><?= e($msg['projet'] ?? '-') ?></td>
                                    <td>
                                        <a href="messages.php?view=<?= $i ?>" class="btn btn-small">Voir</a>
                                        <a href="messages.php?delete=<?= $i ?>" class="btn btn-small btn-danger" onclick="return confirm('Supprimer ?')">X</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
