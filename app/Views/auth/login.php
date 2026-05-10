<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-wrapper">
    <div class="auth-card">
        <!-- Header -->
        <div class="auth-header">
            <div class="auth-icon" style="background: linear-gradient(135deg, #2563a8, #1a4a7a);">
                <svg viewBox="0 0 24 24" fill="currentColor" width="32" height="32">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="white"/>
                </svg>
            </div>
            <h1 class="auth-title">Connexion</h1>
            <p class="auth-subtitle">Bienvenue sur RégimePro — Votre assistant nutrition</p>
        </div>

        <!-- Success Message -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                <span><?= session()->getFlashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span><?= esc($error) ?></span>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="post" action="/login" class="auth-form">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    id="email"
                    name="email" 
                    class="form-input"
                    placeholder="votre@email.com"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Mot de passe</label>
                <input 
                    type="password" 
                    id="password"
                    name="password" 
                    class="form-input"
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit" class="auth-btn">Se connecter</button>
        </form>

        <!-- Footer Link -->
        <p class="auth-footer">
            Pas encore de compte ? <a href="/register" class="auth-link">S'inscrire gratuitement</a>
        </p>
    </div>
</div>

<?= $this->endSection() ?>
