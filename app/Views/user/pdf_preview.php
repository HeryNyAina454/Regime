<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="preview-wrapper">

    <!-- Actions -->
    <div class="preview-topbar">
        <a href="/suggestions" class="btn-secondary">← Retour</a>
        <a href="/export/pdf/<?= $regime['id'] ?>" class="btn-download">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Télécharger le PDF
        </a>
    </div>

    <!-- Prévisualisation -->
    <div class="pdf-preview-card">

        <!-- En-tête -->
        <div class="pdf-header">
            <div class="pdf-logo">
                <div class="pdf-logo-icon">R</div>
                <span class="pdf-logo-text">RégimePro</span>
            </div>
            <div class="pdf-header-right">
                <p class="pdf-date">Généré le <?= date('d/m/Y') ?></p>
                <p class="pdf-patient">Patient : <strong><?= esc($username) ?></strong></p>
            </div>
        </div>

        <hr class="pdf-divider">

        <!-- Résumé patient -->
        <div class="pdf-section">
            <h2 class="pdf-section-title">Profil du patient</h2>
            <div class="pdf-stats">
                <div class="pdf-stat">
                    <span class="stat-label">Genre</span>
                    <span class="stat-value"><?= $profile['gender'] === 'H' ? 'Homme' : 'Femme' ?></span>
                </div>
                <div class="pdf-stat">
                    <span class="stat-label">Âge</span>
                    <span class="stat-value"><?= $profile['age'] ?> ans</span>
                </div>
                <div class="pdf-stat">
                    <span class="stat-label">Poids</span>
                    <span class="stat-value"><?= $profile['weight'] ?> kg</span>
                </div>
                <div class="pdf-stat">
                    <span class="stat-label">Taille</span>
                    <span class="stat-value"><?= $profile['height'] ?> cm</span>
                </div>
                <div class="pdf-stat">
                    <span class="stat-label">IMC</span>
                    <span class="stat-value imc-highlight"><?= $imc ?></span>
                </div>
                <div class="pdf-stat">
                    <span class="stat-label">Objectif</span>
                    <span class="stat-value"><?= esc($goalLabel) ?></span>
                </div>
            </div>
        </div>

        <hr class="pdf-divider">

        <!-- Régime -->
        <div class="pdf-section">
            <h2 class="pdf-section-title">Régime prescrit</h2>
            <div class="pdf-regime-header">
                <h3 class="pdf-regime-name"><?= esc($regime['name']) ?></h3>
                <span class="pdf-regime-duration"><?= $regime['duration_days'] ?> jours</span>
            </div>
            <p class="pdf-regime-desc"><?= esc($regime['description']) ?></p>

            <!-- Composition -->
            <div class="pdf-composition">
                <p class="comp-title">Composition alimentaire</p>
                <div class="comp-items">
                    <div class="comp-item">
                        <div class="comp-circle" style="--pct: <?= $regime['meat_pct'] ?>; --color: #ef4444;">
                            <span><?= $regime['meat_pct'] ?>%</span>
                        </div>
                        <span class="comp-name">Viande</span>
                    </div>
                    <div class="comp-item">
                        <div class="comp-circle" style="--pct: <?= $regime['fish_pct'] ?>; --color: #3b82f6;">
                            <span><?= $regime['fish_pct'] ?>%</span>
                        </div>
                        <span class="comp-name">Poisson</span>
                    </div>
                    <div class="comp-item">
                        <div class="comp-circle" style="--pct: <?= $regime['poultry_pct'] ?>; --color: #f59e0b;">
                            <span><?= $regime['poultry_pct'] ?>%</span>
                        </div>
                        <span class="comp-name">Volaille</span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="pdf-divider">

        <!-- Activités sportives -->
        <div class="pdf-section">
            <h2 class="pdf-section-title">Activités sportives recommandées</h2>
            <table class="pdf-table">
                <thead>
                    <tr>
                        <th>Activité</th>
                        <th>Durée</th>
                        <th>Fréquence</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($activities as $a): ?>
                    <tr>
                        <td><strong><?= esc($a['name']) ?></strong></td>
                        <td><?= $a['duration_minutes'] ?> min</td>
                        <td><?= $a['frequency_per_week'] ?>x / semaine</td>
                        <td><?= esc($a['description']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer PDF -->
        <div class="pdf-footer">
            <p>Ce document a été généré automatiquement par RégimePro. Consultez un professionnel de santé avant de suivre tout régime alimentaire.</p>
        </div>

    </div>
</div>

<?= $this->endSection() ?>