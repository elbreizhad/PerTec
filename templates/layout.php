<?php /** @var string $content */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(App::config('app')['name']) ?></title>
    <link rel="stylesheet" href="<?= url('/assets/style.css') ?>">
</head>
<body>
<header class="topbar">
    <a class="brand" href="<?= url('/') ?>">🏠 PerTec</a>
    <nav class="mainnav">
        <?php $cur = App::currentPath();
        $links = ['/' => 'Tableau de bord', '/biens' => 'Biens', '/locataires' => 'Locataires', '/baux' => 'Baux', '/loyers' => 'Loyers', '/fiscalite' => 'Fiscalité'];
        foreach ($links as $href => $label):
            $active = ($href === '/' ? $cur === '/' : str_starts_with($cur, $href)) ? ' class="active"' : ''; ?>
            <a href="<?= url($href) ?>"<?= $active ?>><?= $label ?></a>
        <?php endforeach; ?>
    </nav>
    <div class="topright">
        <a href="<?= url('/parametres') ?>" title="Paramètres">⚙️</a>
        <a href="<?= url('/logout') ?>" class="btn-link">Déconnexion</a>
    </div>
</header>

<main class="container">
    <?php foreach ((flash() ?: []) as $f): ?>
        <div class="flash flash-<?= e($f['type']) ?>"><?= e($f['msg']) ?></div>
    <?php endforeach; ?>
    <?= $content ?>
</main>

<footer class="footer">PerTec — Gestion locative · <?= date('Y') ?></footer>
</body>
</html>
