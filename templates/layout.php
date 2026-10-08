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
// Menu de gauche : groupes dépliables avec sous-menus.
$propertiesMenu = [];
try { $propertiesMenu = Property::all(); } catch (Throwable $e) { /* base indisponible */ }
$propertyTabs = [
    'apercu' => 'Vue d\'ensemble', 'depenses' => 'Dépenses & travaux', 'charges' => 'Charges et impôts',
    'documents' => 'Documents légaux', 'baux' => 'Baux',
];
$biensChildren = [['/biens', 'Tous les biens', true]];
foreach ($propertiesMenu as $pm) {
    $href = '/biens/' . (int) $pm['id'];
    $sub = [];
    if ($cur === $href) foreach ($propertyTabs as $k => $lbl) $sub[] = [$href . '#' . $k, $lbl];
    $biensChildren[] = [$href, $pm['label'], false, $sub];
}
$biensChildren[] = ['/biens/new', '+ Ajouter un bien', true];
$menu = [
    ['/',      'Tableau de bord', 'dashboard', []],
    ['/biens', 'Biens',           'home',      $biensChildren],
    ['/baux',  'Locations',       'file',      [['/locataires', 'Locataires'], ['/baux', 'Baux'], ['/loyers', 'Loyers & quittances']]],
    ['/bilan', 'Finances',        'chart',     [['/bilan', 'Bilan'], ['/projection', 'Projection'], ['/fiscalite', 'Fiscalité LMNP']]],
    ['/parametres', 'Paramètres', 'gear',      [['/parametres#coordonnees', 'Coordonnées & IRL'], ['/parametres#signature', 'Signature'],
                                                ['/parametres#emails', 'Envoi des emails'], ['/parametres#modele-email', 'Modèle d\'email'],
                                                ['/parametres#motdepasse', 'Mot de passe']]],
];
/** Lien actif : correspondance exacte, ou préfixe (sauf liens « exacts »). */
$isActive = function (string $href, bool $exact = false) use ($cur): bool {
    $path = strtok($href, '#');
    if ($path === '/') return $cur === '/';
    return $exact ? $cur === $path : ($cur === $path || str_starts_with($cur, $path . '/'));
};
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(App::config('app')['name']) ?></title>
    <link rel="stylesheet" href="<?= asset('/assets/style.css') ?>">
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
        <nav class="side-nav" id="side-nav">
            <?php foreach ($menu as [$href, $label, $ico, $children]):
                if (!$children): ?>
                <a class="side-link<?= $isActive($href) ? ' active' : '' ?>" href="<?= url($href) ?>"><?= $icons[$ico] ?><span><?= $label ?></span></a>
            <?php else:
                $groupActive = false;
                foreach ($children as $c) if ($isActive($c[0], $c[2] ?? false)) $groupActive = true; ?>
                <details class="side-group<?= $groupActive ? ' current' : '' ?>"<?= $groupActive ? ' open' : '' ?>>
                    <summary><?= $icons[$ico] ?><span class="side-label"><?= $label ?></span>
                        <svg class="chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 6l6 6-6 6"/></svg></summary>
                    <div class="side-sub">
                    <?php foreach ($children as $c):
                        [$chref, $clabel] = $c;
                        $anchor = str_contains($chref, '#');
                        $cact = !$anchor && $isActive($chref, $c[2] ?? false);
                        $link = $anchor ? url(strtok($chref, '#')) . '#' . substr($chref, strpos($chref, '#') + 1) : url($chref); ?>
                        <a href="<?= $link ?>" title="<?= e($clabel) ?>"<?= $cact ? ' class="active"' : '' ?><?= $anchor ? ' data-anchor="' . e(substr($chref, strpos($chref, '#') + 1)) . '"' : '' ?>><?= e($clabel) ?></a>
                        <?php if (!empty($c[3])): ?>
                            <div class="side-sub2">
                            <?php foreach ($c[3] as [$thref, $tlabel]): $tid = substr($thref, strpos($thref, '#') + 1); ?>
                                <a href="<?= url(strtok($thref, '#')) . '#' . $tid ?>" data-tab-link="<?= e($tid) ?>"><?= e($tlabel) ?></a>
                            <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </div>
                </details>
            <?php endif; endforeach; ?>
        </nav>
        <div class="side-bottom">
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
            <?php if (class_exists('Migrator') && Migrator::$error): ?>
                <div class="flash flash-error">⚠️ Mise à jour de la base de données incomplète — certaines fonctions peuvent échouer.<br><small><?= e(Migrator::$error) ?></small></div>
            <?php endif; ?>
            <?php foreach ((flash() ?: []) as $f): ?>
                <div class="flash flash-<?= e($f['type']) ?>"><?= e($f['msg']) ?></div>
            <?php endforeach; ?>
            <?= $content ?>
        </main>

        <footer class="footer">PerTec — Gestion locative · <?= date('Y') ?></footer>
    </div>

    <div class="backdrop" onclick="document.body.classList.remove('nav-open')"></div>
</div>
<script src="<?= asset('/assets/tabs.js') ?>"></script>
<script>
// Menu en accordéon : un seul groupe ouvert à la fois.
(function () {
    var groups = document.querySelectorAll('#side-nav .side-group');
    groups.forEach(function (g) {
        g.querySelector('summary').addEventListener('click', function (e) {
            if (g.open) return; // fermeture : comportement normal
            groups.forEach(function (o) { if (o !== g) o.open = false; });
        });
    });
    // Ancres du menu (ex. Paramètres) : surligne la rubrique de l'URL courante.
    function mark() {
        var h = location.hash.slice(1);
        document.querySelectorAll('#side-nav [data-anchor]').forEach(function (a) {
            a.classList.toggle('active', !!h && a.dataset.anchor === h && a.pathname === location.pathname);
        });
    }
    window.addEventListener('hashchange', mark); mark();
})();
</script>
</body>
</html>
