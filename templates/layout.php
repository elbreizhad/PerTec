<?php /** @var string $content */
$cur = App::currentPath();
$u = Auth::user() ?? [];
$icons = [
    'dashboard' => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/></svg>',
    'home'      => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/><path d="M9.5 21v-6h5v6"/></svg>',
    'users'     => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.2"/><path d="M3.5 20a5.5 5.5 0 0 1 11 0"/><path d="M16 5.2a3 3 0 0 1 0 5.6"/><path d="M17.5 20a5.5 5.5 0 0 0-3-4.9"/></svg>',
    'file'      => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2.5h8l4 4V21a.5.5 0 0 1-.5.5h-11A.5.5 0 0 1 6 21z"/><path d="M14 2.5V6.5h4"/><path d="M9 12h6M9 15.5h6"/></svg>',
    'euro'      => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 6.5A6 6 0 1 0 16 17.5"/><path d="M4.5 10.5h8M4.5 13.5h7"/></svg>',
    'chart'     => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20V4"/><path d="M4 20h16"/><rect x="7.5" y="12" width="3" height="5"/><rect x="13.5" y="8" width="3" height="9"/></svg>',
    'gear'      => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19 12a7 7 0 0 0-.1-1.2l2-1.5-2-3.4-2.3 1a7 7 0 0 0-2-1.2l-.3-2.5h-4l-.3 2.5a7 7 0 0 0-2 1.2l-2.3-1-2 3.4 2 1.5A7 7 0 0 0 5 12a7 7 0 0 0 .1 1.2l-2 1.5 2 3.4 2.3-1a7 7 0 0 0 2 1.2l.3 2.5h4l.3-2.5a7 7 0 0 0 2-1.2l2.3 1 2-3.4-2-1.5A7 7 0 0 0 19 12z"/></svg>',
    'logout'    => '<svg class="ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4.5H5.5A1.5 1.5 0 0 0 4 6v12a1.5 1.5 0 0 0 1.5 1.5H9"/><path d="M15 8l4 4-4 4"/><path d="M19 12H9"/></svg>',
];
$links = [
    ['/',            'Tableau de bord', 'dashboard'],
    ['/biens',       'Biens',           'home'],
    ['/locataires',  'Locataires',      'users'],
    ['/baux',        'Baux',            'file'],
    ['/loyers',      'Loyers',          'euro'],
    ['/fiscalite',   'Fiscalité',       'chart'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(App::config('app')['name']) ?></title>
    <link rel="stylesheet" href="<?= url('/assets/style.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="app">
    <aside class="sidebar">
        <div class="side-profile">
            <div class="side-avatar">🏠</div>
            <div class="side-name">PerTec</div>
            <div class="side-mail"><?= e($u['username'] ?? 'Gestion locative') ?></div>
        </div>
        <nav class="side-nav">
            <?php foreach ($links as [$href, $label, $ico]):
                $active = ($href === '/' ? $cur === '/' : str_starts_with($cur, $href)) ? ' class="active"' : ''; ?>
                <a href="<?= url($href) ?>"<?= $active ?>><?= $icons[$ico] ?><span><?= $label ?></span></a>
            <?php endforeach; ?>
        </nav>
        <div class="side-bottom">
            <a href="<?= url('/parametres') ?>"><?= $icons['gear'] ?><span>Paramètres</span></a>
            <a href="<?= url('/logout') ?>"><?= $icons['logout'] ?><span>Déconnexion</span></a>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <button class="burger" onclick="document.body.classList.toggle('nav-open')" aria-label="Menu">☰</button>
            <span class="topbar-title"><?= e(App::config('app')['name']) ?></span>
            <div class="topbar-right">
                <span class="topbar-chip">👤 <?= e($u['username'] ?? '') ?></span>
            </div>
        </header>

        <main class="content">
            <?php foreach ((flash() ?: []) as $f): ?>
                <div class="flash flash-<?= e($f['type']) ?>"><?= e($f['msg']) ?></div>
            <?php endforeach; ?>
            <?= $content ?>
        </main>

        <footer class="footer">PerTec — Gestion locative · <?= date('Y') ?></footer>
    </div>

    <div class="backdrop" onclick="document.body.classList.remove('nav-open')"></div>
</div>
</body>
</html>
