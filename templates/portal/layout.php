<?php /** @var string $content */
$ps = Setting::all();
$owner = trim((string) ($ps['mail_from_name'] ?? '')) ?: (string) ($ps['landlord_name'] ?? '');
$logged = !empty($_SESSION['tenant_ids']) && empty($_GET['apercu']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>Espace locataire<?= $owner ? ' — ' . e($owner) : '' ?></title>
    <link rel="stylesheet" href="<?= asset('/assets/style.css') ?>">
</head>
<body class="portal">
<header class="portal-top">
    <div class="portal-wrap">
        <div><strong>Espace locataire</strong><?php if ($owner): ?><span class="muted small"> · <?= e($owner) ?></span><?php endif; ?></div>
        <?php if ($logged): ?><a class="btn btn-sm" href="<?= url('/locataire/deconnexion') ?>">Se déconnecter</a><?php endif; ?>
    </div>
</header>
<main class="portal-wrap portal-main">
    <?php foreach ((flash() ?: []) as $f): ?>
        <div class="flash flash-<?= e($f['type']) ?>"><?= e($f['msg']) ?></div>
    <?php endforeach; ?>
    <?= $content ?>
</main>
</body>
</html>
