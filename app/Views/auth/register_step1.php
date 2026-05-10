<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-wrapper">
    
    <div class="auth-card">
        <!-- Header -->
        <div class="auth-header">
            <div class="auth-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <svg viewBox="0 0 24 24" fill="currentColor" width="32" height="32">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" fill="white"/>
                </svg>
            </div>
            <h1 class="auth-title">Créer un compte</h1>
            <p class="auth-subtitle">Rejoignez RégimePro et commencez votre parcours santé</p>
        </div>

        <!-- Error Messages -->
        <?php if (isset($errors)): ?>
            <div class="alert alert-error">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="12"/>
                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <ul class="error-list">
                    <?php foreach ($errors as $e): ?>
                        <li><?= esc($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Stepper -->
        <div class="stepper">
            <div class="step active">
                <div class="step-num">1</div>
                <span class="step-label">Infos personnelles</span>
            </div>
            <div class="step-line"></div>
            <div class="step">
                <div class="step-num">2</div>
                <span class="step-label">Objectifs</span>
            </div>
        </div>

        <!-- Registration Form Step 1 -->
        <form method="post" action="/register" class="auth-form">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="username" class="form-label">Nom d'utilisateur</label>
                <input 
                    type="text" 
                    id="username"
                    name="username" 
                    class="form-input"
                    value="<?= esc($old['username'] ?? '') ?>"
                    placeholder="Jean Dupont"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    id="email"
                    name="email" 
                    class="form-input"
                    value="<?= esc($old['email'] ?? '') ?>"
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

            <div class="form-group">
                <label for="gender" class="form-label">Genre</label>
                <select name="gender" id="gender" class="form-input" required>
                    <option value="">-- Choisir votre genre --</option>
                    <option value="H" <?= (($old['gender'] ?? '') === 'H') ? 'selected' : '' ?>>Homme</option>
                    <option value="F" <?= (($old['gender'] ?? '') === 'F') ? 'selected' : '' ?>>Femme</option>
                </select>
            </div>

            <button type="submit" class="auth-btn">Suivant →</button>
        </form>

        <!-- Footer Link -->
        <p class="auth-footer">
            Déjà inscrit ? <a href="/login" class="auth-link">Se connecter</a>
        </p>
    </div>
</div>

<?= $this->endSection() ?>