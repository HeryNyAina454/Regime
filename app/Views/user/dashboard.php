<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="dashboard-wrapper">
    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="welcome-content">
            <h1>Bienvenue, <?= esc(session()->get('username')) ?> ! 👋</h1>
            <p class="welcome-subtitle">Gérez votre régime alimentaire et suivez vos progrès</p>
        </div>
        <div class="welcome-actions">
            <a href="/profile" class="btn btn-primary">Mon Profil</a>
            <a href="/suggestions" class="btn btn-secondary">Découvrir</a>
        </div>
    </section>

    <!-- Dashboard Grid -->
    <section class="dashboard-grid">
        <!-- Stats Cards -->
        <div class="stats-section">
            <h2 class="section-title">Vos Statistiques</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(37, 99, 168, 0.1); color: #2563a8;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-13c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z"/>
                        </svg>
                    </div>
                    <h3 class="stat-label">Profil</h3>
                    <p class="stat-desc">Consultez et modifiez vos informations</p>
                    <a href="/profile" class="stat-link">Accéder →</a>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                    <h3 class="stat-label">Régimes</h3>
                    <p class="stat-desc">Explorez nos régimes personnalisés</p>
                    <a href="/suggestions" class="stat-link">Explorer →</a>
                </div>

                <div class="stat-card">
                    <div class="stat-icon" style="background: rgba(251, 146, 60, 0.1); color: #fb923c;">
                        <svg viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                        </svg>
                    </div>
                    <h3 class="stat-label">Support</h3>
                    <p class="stat-desc">Besoin d'aide ou d'informations ?</p>
                    <a href="#" class="stat-link">Nous contacter →</a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="actions-section">
            <h2 class="section-title">Actions Rapides</h2>
            <div class="actions-list">
                <a href="/profile" class="action-item">
                    <span class="action-icon">👤</span>
                    <span class="action-text">Voir mon profil</span>
                </a>
                <a href="/suggestions" class="action-item">
                    <span class="action-icon">🍎</span>
                    <span class="action-text">Découvrir régimes</span>
                </a>
                <a href="/profile" class="action-item">
                    <span class="action-icon">📊</span>
                    <span class="action-text">Mes statistiques</span>
                </a>
                <a href="/logout" class="action-item logout">
                    <span class="action-icon">🚪</span>
                    <span class="action-text">Déconnexion</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="info-section">
        <div class="info-card">
            <h3>💡 Conseil du jour</h3>
            <p>Une bonne nutrition est la clé d'une bonne santé. Consultez nos régimes personnalisés pour atteindre vos objectifs.</p>
        </div>
    </section>
</div>

<?= $this->endSection() ?>