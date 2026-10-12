<?php
require_once __DIR__ . '/config.php';
require_login();

$estimations_file = pertec_data_file('estimations.json');
$estimations = [];
if (file_exists($estimations_file)) {
    $estimations = json_decode(file_get_contents($estimations_file), true) ?: [];
}

// Marquer comme lu
if (isset($_GET['view']) && is_numeric($_GET['view'])) {
    $idx = (int)$_GET['view'];
    if (isset($estimations[$idx])) {
        $estimations[$idx]['read'] = true;
        file_put_contents($estimations_file, json_encode($estimations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}

// Supprimer
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $idx = (int)$_GET['delete'];
    if (isset($estimations[$idx])) {
        array_splice($estimations, $idx, 1);
        file_put_contents($estimations_file, json_encode($estimations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        header('Location: estimations.php?deleted=1');
        exit;
    }
}

// Format prix
function formatPrix($prix) {
    if ($prix === 'Sur devis' || $prix === null) return 'Sur devis';
    return number_format($prix, 0, ',', ' ') . ' €';
}

// Vue d'une estimation
$viewing = null;
if (isset($_GET['view']) && is_numeric($_GET['view'])) {
    $idx = (int)$_GET['view'];
    if (isset($estimations[$idx])) {
        $viewing = $estimations[$idx];
        $viewing_idx = $idx;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estimations tarifs - Admin Pertec</title>
    <link rel="stylesheet" href="style.css?v=<?= filemtime(__DIR__ . '/style.css') ?>">
</head>
<body>
    <div class="admin-layout">
        <?php include 'sidebar.php'; ?>

        <main class="admin-main">
            <header class="admin-header">
                <h1>Estimations tarifs</h1>
                <div class="header-actions">
                    <span class="badge-info"><?= count($estimations) ?> demande(s)</span>
                </div>
            </header>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">Estimation supprimée.</div>
            <?php endif; ?>

            <?php if ($viewing): ?>
                <div class="card message-detail">
                    <div class="message-header">
                        <a href="estimations.php" class="btn btn-secondary">&larr; Retour</a>
                        <a href="estimations.php?delete=<?= $viewing_idx ?>" class="btn btn-danger" onclick="return confirm('Supprimer cette estimation ?')">Supprimer</a>
                    </div>

                    <div class="message-meta">
                        <p><strong>Date :</strong> <?= e($viewing['date'] ?? '') ?></p>
                        <p><strong>Email :</strong> <a href="mailto:<?= e($viewing['email'] ?? '') ?>"><?= e($viewing['email'] ?? '') ?></a></p>
                        <p><strong>Téléphone :</strong> <?= e($viewing['telephone'] ?? '-') ?: '-' ?></p>
                    </div>

                    <div class="estimation-details">
                        <div class="estimation-totals" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin: 1.5rem 0;">
                            <div style="background: #f0f9ff; padding: 1rem; border-radius: 8px; text-align: center;">
                                <p style="color: #64748b; margin-bottom: 0.5rem;">Création</p>
                                <p style="font-size: 1.5rem; font-weight: 700; color: #2563eb;"><?= formatPrix($viewing['creation']) ?></p>
                            </div>
                            <div style="background: #f0fdf4; padding: 1rem; border-radius: 8px; text-align: center;">
                                <p style="color: #64748b; margin-bottom: 0.5rem;">Mensuel</p>
                                <p style="font-size: 1.5rem; font-weight: 700; color: #16a34a;"><?= formatPrix($viewing['mensuel']) ?>/mois</p>
                            </div>
                        </div>

                        <h3>Configuration choisie</h3>
                        <table class="data-table" style="margin-bottom: 1.5rem;">
                            <tr>
                                <td><strong>Type de site</strong></td>
                                <td><?= e($viewing['type_site'] ?? '-') ?></td>
                            </tr>
                            <tr>
                                <td><strong>Options</strong></td>
                                <td><?= !empty($viewing['options']) ? e(implode(', ', $viewing['options'])) : '-' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Suivi</strong></td>
                                <td><?= !empty($viewing['suivi']) ? e(implode(', ', $viewing['suivi'])) : 'Base uniquement' ?></td>
                            </tr>
                            <tr>
                                <td><strong>Délai</strong></td>
                                <td><?= e($viewing['delai'] ?? '-') ?></td>
                            </tr>
                        </table>

                        <?php if (!empty($viewing['detail_creation'])): ?>
                        <h4>Détail création</h4>
                        <ul style="margin-bottom: 1rem;">
                            <?php foreach ($viewing['detail_creation'] as $ligne): ?>
                            <li><?= e($ligne['nom']) ?> : <?= formatPrix($ligne['prix']) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>

                        <?php if (!empty($viewing['detail_mensuel'])): ?>
                        <h4>Détail mensuel</h4>
                        <ul>
                            <?php foreach ($viewing['detail_mensuel'] as $ligne): ?>
                            <li><?= e($ligne['nom']) ?> : <?= formatPrix($ligne['prix']) ?>/mois</li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>

                    <div class="message-actions" style="margin-top: 1.5rem;">
                        <a href="mailto:<?= e($viewing['email'] ?? '') ?>?subject=Votre estimation de site - Pertec" class="btn btn-primary">Répondre par email</a>
                        <?php if ($viewing['telephone']): ?>
                        <a href="tel:<?= e($viewing['telephone']) ?>" class="btn btn-secondary">Appeler</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="card">
                    <?php if (empty($estimations)): ?>
                        <p class="empty-state">Aucune demande d'estimation pour le moment.</p>
                    <?php else: ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Email</th>
                                    <th>Type de site</th>
                                    <th>Création</th>
                                    <th>Mensuel</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_reverse($estimations, true) as $i => $est): ?>
                                <tr class="<?= ($est['read'] ?? false) ? '' : 'unread' ?>">
                                    <td><?= e($est['date'] ?? '') ?></td>
                                    <td><?= e($est['email'] ?? '') ?></td>
                                    <td><?= e($est['type_site'] ?? '-') ?></td>
                                    <td><strong><?= formatPrix($est['creation']) ?></strong></td>
                                    <td><?= formatPrix($est['mensuel']) ?>/mois</td>
                                    <td>
                                        <a href="estimations.php?view=<?= $i ?>" class="btn btn-small">Voir</a>
                                        <a href="estimations.php?delete=<?= $i ?>" class="btn btn-small btn-danger" onclick="return confirm('Supprimer ?')">X</a>
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
