<?php /** @var string $content */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(App::config('app')['name']) ?></title>
    <link rel="stylesheet" href="<?= url('/assets/style.css') ?>">
</head>
<body class="bare">
<div class="auth-box">
    <h1 class="auth-brand">🏠 PerTec</h1>
    <?php foreach ((flash() ?: []) as $f): ?>
        <div class="flash flash-<?= e($f['type']) ?>"><?= e($f['msg']) ?></div>
    <?php endforeach; ?>
    <?= $content ?>
</div>
</body>
</html>
