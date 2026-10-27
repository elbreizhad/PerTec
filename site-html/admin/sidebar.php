<aside class="admin-sidebar">
    <div class="sidebar-logo">
        <h2>Per<span>Tec</span></h2>
        <p>Administration</p>
    </div>

    <nav class="sidebar-nav">
        <a href="dashboard.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>">
            <span class="nav-icon">📊</span>
            Dashboard
        </a>
        <a href="stats.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'stats.php' ? 'active' : '' ?>">
            <span class="nav-icon">📈</span>
            Statistiques
        </a>
        <a href="articles.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'articles.php' ? 'active' : '' ?>">
            <span class="nav-icon">📝</span>
            Articles
        </a>
        <a href="messages.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'messages.php' ? 'active' : '' ?>">
            <span class="nav-icon">📧</span>
            Messages
            <?php
            $mf = pertec_data_file('messages.json');
            $msgs = file_exists($mf) ? (json_decode(file_get_contents($mf), true) ?: []) : [];
            $unread = count(array_filter($msgs, fn($m) => !($m['read'] ?? false)));
            if ($unread > 0): ?>
                <span class="badge"><?= $unread ?></span>
            <?php endif; ?>
        </a>
        <a href="estimations.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'estimations.php' ? 'active' : '' ?>">
            <span class="nav-icon">💰</span>
            Estimations
            <?php
            $ef = pertec_data_file('estimations.json');
            $ests = file_exists($ef) ? (json_decode(file_get_contents($ef), true) ?: []) : [];
            $unreadEst = count(array_filter($ests, fn($e) => !($e['read'] ?? false)));
            if ($unreadEst > 0): ?>
                <span class="badge"><?= $unreadEst ?></span>
            <?php endif; ?>
        </a>
        <a href="settings.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'active' : '' ?>">
            <span class="nav-icon">⚙️</span>
            Paramètres
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="index.php?logout=1" class="nav-item">
            <span class="nav-icon">🚪</span>
            Déconnexion
        </a>
    </div>
</aside>
