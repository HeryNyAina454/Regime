<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="auth-wrapper">
    <div class="auth-card profile-card">
        
        <div class="auth-wrapper">
        <div class="auth-card profile-card">

            <!-- Brand -->
            <div class="auth-brand">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm1 14.93V18a1 1 0 0 1-2 0v-1.07A8 8 0 0 1 4.07 11H6a1 1 0 0 1 0 2 6 6 0 0 0 5 5.92zm0-9.86A6 6 0 0 0 7.08 11H6a1 1 0 0 1 0-2 8 8 0 0 1 6.93-4V6a1 1 0 0 1 2 0v-.93A8 8 0 0 1 19.93 11H18a1 1 0 0 1 0-2 6 6 0 0 0-5-5.93z"/>
                    </svg>
                </div>
                <span class="brand-name">RégimePro</span>
            </div>

            <h1 class="auth-title">Mon profil</h1>
            <p class="auth-subtitle">Choisissez votre objectif pour recevoir des recommandations adaptées</p>

            <!-- Alertes -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Bloc IMC -->
            <?php if ($imc !== null): ?>
            <div class="imc-block imc-<?= $imcColor ?>">
                <div class="imc-value"><?= $imc ?></div>
                <div class="imc-info">
                    <span class="imc-label">Votre IMC</span>
                    <span class="imc-status"><?= $imcLabel ?></span>
                </div>
                <div class="imc-gauge">
                    <div class="imc-gauge-bar">
                        <div class="imc-gauge-fill" style="width: <?= min(max(($imc / 40) * 100, 5), 100) ?>%"></div>
                    </div>
                    <div class="imc-scale">
                        <span>0</span><span>18.5</span><span>25</span><span>30</span><span>40</span>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Choix objectif -->
            <form method="post" action="/profile/save-goal">
                <?= csrf_field() ?>

                <p class="goal-heading">Quel est votre objectif ?</p>

                <div class="goal-grid">

                    <label class="goal-card <?= (($profile['goal'] ?? '') === 'gain') ? 'selected' : '' ?>">
                        <input type="radio" name="goal" value="gain" <?= (($profile['goal'] ?? '') === 'gain') ? 'checked' : '' ?>>
                        <div class="goal-icon">⬆️</div>
                        <div class="goal-title">Augmenter le poids</div>
                        <div class="goal-desc">Prise de masse et renforcement musculaire</div>
                    </label>

                    <label class="goal-card <?= (($profile['goal'] ?? '') === 'lose') ? 'selected' : '' ?>">
                        <input type="radio" name="goal" value="lose" <?= (($profile['goal'] ?? '') === 'lose') ? 'checked' : '' ?>>
                        <div class="goal-icon">⬇️</div>
                        <div class="goal-title">Réduire le poids</div>
                        <div class="goal-desc">Perte de poids progressive et durable</div>
                    </label>

                    <label class="goal-card <?= (($profile['goal'] ?? '') === 'ideal') ? 'selected' : '' ?>">
                        <input type="radio" name="goal" value="ideal" <?= (($profile['goal'] ?? '') === 'ideal') ? 'checked' : '' ?>>
                        <div class="goal-icon">🎯</div>
                        <div class="goal-title">IMC idéal</div>
                        <div class="goal-desc">Atteindre et maintenir un IMC entre 18.5 et 25</div>
                    </label>

                </div>

                <button type="submit" class="btn-primary">Enregistrer mon objectif</button>
            </form>

            <div class="auth-footer">
                <a href="/dashboard">← Retour au tableau de bord</a>
            </div>

        </div>
    </div>

    <script>
    // Sélection visuelle des cartes objectif
    document.querySelectorAll('.goal-card input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', () => {
            document.querySelectorAll('.goal-card').forEach(c => c.classList.remove('selected'));
            if (radio.checked) radio.closest('.goal-card').classList.add('selected');
        });
    });
    </script>


    </div>
</div>

<?= $this->endSection() ?>
