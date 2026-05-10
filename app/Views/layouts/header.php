<header class="site-header">
    <div class="header-inner">

        <!-- Logo -->
        <a href="/dashboard" class="header-brand">
            <span class="brand-name">Regime</span>
        </a>

        <!-- Nav -->
        <nav class="header-nav">
            <a href="/dashboard" class="nav-link <?= (uri_string() === 'dashboard') ? 'active' : '' ?>">Accueil</a>
            <a href="/profile"   class="nav-link <?= (uri_string() === 'profile')   ? 'active' : '' ?>">Mon profil</a>
            <a href="/suggestions" class="nav-link <?= (uri_string() === 'suggestions') ? 'active' : '' ?>">Suggestions</a>
        </nav>

        <!-- User menu -->
        <div class="header-user">
            <span class="user-name"><?= esc(session()->get('username')) ?></span>
            <a href="/logout" class="btn-logout">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Déconnexion
            </a>
        </div>

        <!-- Burger mobile -->
        <button class="burger" onclick="toggleMenu()" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>

    </div>
</header>

<script>
function toggleMenu() {
    document.querySelector('.header-nav').classList.toggle('open');
}
</script>