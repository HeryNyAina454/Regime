<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin — RégimePro' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('style.css') ?>">
</head>
<body class="admin-body">

    <aside class="admin-sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24"><path fill="white" d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 14.93V18a1 1 0 0 1-2 0v-1.07A8 8 0 0 1 4.07 11H6a1 1 0 0 1 0 2 6 6 0 0 0 5 5.92zm0-9.86A6 6 0 0 0 7.08 11H6a1 1 0 0 1 0-2 8 8 0 0 1 6.93-4V6a1 1 0 0 1 2 0v-.93A8 8 0 0 1 19.93 11H18a1 1 0 0 1 0-2 6 6 0 0 0-5-5.93z"/></svg>
            </div>
            <span>RégimePro</span>
            <small>Admin</small>
        </div>

        <nav class="sidebar-nav">
            <a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link <?= str_contains(uri_string(), 'admin/dashboard') ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
                    <rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
                </svg>
                Tableau de bord
            </a>

            <a href="<?= base_url('admin/activities') ?>" class="sidebar-link <?= str_contains(uri_string(), 'admin/activities') ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
                Activités sportives
            </a>

            <a href="<?= base_url('admin/codes') ?>" class="sidebar-link <?= str_contains(uri_string(), 'admin/codes') ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-4 0v2"/>
                </svg>
                Codes promo
            </a>


            <a href="<?= base_url('admin/settings') ?>" class="sidebar-link <?= str_contains(uri_string(), 'admin/settings') ? 'active' : '' ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                Paramètres
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="<?= base_url('logout') ?>" class="sidebar-logout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Déconnexion
            </a>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-topbar">
            <h1 class="admin-page-title"><?= $title ?? 'Tableau de bord' ?></h1>
            <span class="admin-user">👤 <?= esc(session()->get('username')) ?></span>
        </header>
        <main class="admin-content">
            <?= $this->renderSection('content') ?>
        </main>
    </div>

</body>
</html>
