<?php
require_once __DIR__ . '/config.php';
require_login();

$settings = get_settings();

// Charger les messages de contact
$messages_file = pertec_data_file('messages.json');
$messages = [];
if (file_exists($messages_file)) {
    $messages = json_decode(file_get_contents($messages_file), true) ?: [];
}
$unread_count = count(array_filter($messages, fn($m) => !($m['read'] ?? false)));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin Pertec</title>
    <link rel="stylesheet" href="style.css?v=<?= filemtime(__DIR__ . '/style.css') ?>">
</head>
<body>
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>

        <main class="admin-main">
            <header class="admin-header">
                <h1>Dashboard</h1>
                <div class="header-actions">
                    <a href="../" target="_blank" class="btn btn-secondary">Voir le site</a>
                </div>
            </header>

            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-icon">📧</div>
                    <div class="stat-content">
                        <span class="stat-value"><?= count($messages) ?></span>
                        <span class="stat-label">Messages reçus</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">🔔</div>
                    <div class="stat-content">
                        <span class="stat-value"><?= $unread_count ?></span>
                        <span class="stat-label">Non lus</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">🌐</div>
                    <div class="stat-content">
                        <span class="stat-value">En ligne</span>
                        <span class="stat-label">Statut du site</span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">📅</div>
                    <div class="stat-content">
                        <span class="stat-value"><?= date('d/m/Y') ?></span>
                        <span class="stat-label">Date du jour</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <h2>Derniers messages</h2>
                <?php if (empty($messages)): ?>
                    <p class="empty-state">Aucun message pour le moment.</p>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Projet</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice(array_reverse($messages), 0, 5) as $i => $msg): ?>
                            <tr class="<?= ($msg['read'] ?? false) ? '' : 'unread' ?>">
                                <td><?= e($msg['date'] ?? '') ?></td>
                                <td><?= e($msg['nom'] ?? '') ?></td>
                                <td><a href="mailto:<?= e($msg['email'] ?? '') ?>"><?= e($msg['email'] ?? '') ?></a></td>
                                <td><?= e($msg['projet'] ?? '-') ?></td>
                                <td><a href="messages.php?view=<?= count($messages) - 1 - $i ?>" class="btn btn-small">Voir</a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="messages.php" class="btn btn-secondary" style="margin-top: 1rem;">Voir tous les messages</a>
                <?php endif; ?>
            </div>

            <div class="card">
                <h2>Liens rapides</h2>
                <div class="quick-links">
                    <a href="settings.php" class="quick-link">
                        <span class="quick-icon">⚙️</span>
                        <span>Paramètres</span>
                    </a>
                    <a href="messages.php" class="quick-link">
                        <span class="quick-icon">📬</span>
                        <span>Messages</span>
                    </a>
                    <a href="../contact.php" target="_blank" class="quick-link">
                        <span class="quick-icon">📝</span>
                        <span>Formulaire</span>
                    </a>
                    <a href="index.php?logout=1" class="quick-link">
                        <span class="quick-icon">🚪</span>
                        <span>Déconnexion</span>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
