<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="suggestions-wrapper">

    <!-- En-tête résumé -->
    <div class="suggestions-hero">
        <div class="hero-left">
            <p class="hero-label">Votre objectif</p>
            <h1 class="hero-title"><?= esc($goalLabel) ?></h1>
            <p class="hero-sub">IMC actuel : <strong><?= $imc ?></strong> — Voici les programmes adaptés pour vous</p>
        </div>
        <div class="hero-wallet">
            <div class="wallet-info">
                <div class="wallet-label">Portefeuille</div>
                <div class="wallet-amount"><?= number_format($wallet['balance'], 2) ?> €</div>
            </div>
            <?php if ($wallet['is_gold']): ?>
                <span class="badge-gold">⭐ Gold — 15% de remise</span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Alertes -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            <span><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Régimes suggérés -->
    <section class="section-block">
        <h2 class="section-title">
            <span class="section-icon">🥗</span> Régimes recommandés
        </h2>

        <div class="cards-grid">
            <?php foreach ($regimes as $regime): ?>
            <?php
                $isOwned    = in_array($regime['id'], $orderedIds);
                $price      = $regime['price'];
                $priceGold  = round($price * 0.85, 2);
                $showGold   = $wallet['is_gold'];
            ?>
            <div class="regime-card <?= $isOwned ? 'owned' : '' ?>">
                <?php if ($isOwned): ?>
                    <span class="card-badge badge-owned">✓ Acheté</span>
                <?php endif; ?>

                <div class="card-header">
                    <h3 class="card-title"><?= esc($regime['name']) ?></h3>
                    <div class="card-duration"><?= $regime['duration_days'] ?> jours</div>
                </div>

                <p class="card-desc"><?= esc($regime['description']) ?></p>

                <!-- Composition -->
                <div class="composition">
                    <div class="comp-bar">
                        <div class="comp-seg seg-meat"    style="width:<?= $regime['meat_pct'] ?>%"    title="Viande <?= $regime['meat_pct'] ?>%"></div>
                        <div class="comp-seg seg-fish"    style="width:<?= $regime['fish_pct'] ?>%"    title="Poisson <?= $regime['fish_pct'] ?>%"></div>
                        <div class="comp-seg seg-poultry" style="width:<?= $regime['poultry_pct'] ?>%" title="Volaille <?= $regime['poultry_pct'] ?>%"></div>
                    </div>
                    <div class="comp-legend">
                        <span><i class="dot dot-meat"></i> Viande <?= $regime['meat_pct'] ?>%</span>
                        <span><i class="dot dot-fish"></i> Poisson <?= $regime['fish_pct'] ?>%</span>
                        <span><i class="dot dot-poultry"></i> Volaille <?= $regime['poultry_pct'] ?>%</span>
                    </div>
                </div>

                <!-- Prix & achat -->
                <div class="card-footer">
                    <div class="price-block">
                        <?php if ($showGold): ?>
                            <span class="price-original"><?= number_format($price, 2) ?> €</span>
                            <span class="price-main"><?= number_format($priceGold, 2) ?> €</span>
                        <?php else: ?>
                            <span class="price-main"><?= number_format($price, 2) ?> €</span>
                        <?php endif; ?>
                    </div>

                    <?php if (!$isOwned): ?>
                    <form method="post" action="/suggestions/buy">
                        <?= csrf_field() ?>
                        <input type="hidden" name="regime_id" value="<?= $regime['id'] ?>">
                        <button type="submit" class="btn-buy">Acheter</button>
                    </form>
                    <?php else: ?>
                        <div class="owned-actions">
                            <span class="btn-owned">✓ Acheté</span>
                            <a href="/export/preview/<?= $regime['id'] ?>" class="btn-preview">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                </svg>
                                Exporter PDF
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Activités sportives -->
    <section class="section-block">
        <h2 class="section-title">
            <span class="section-icon">🏃</span> Activités sportives conseillées
        </h2>

        <div class="activities-grid">
            <?php foreach ($activities as $activity): ?>
            <div class="activity-card">
                <div class="activity-header">
                    <h3 class="activity-name"><?= esc($activity['name']) ?></h3>
                    <div class="activity-meta">
                        <span>⏱ <?= $activity['duration_minutes'] ?> min</span>
                        <span>📅 <?= $activity['frequency_per_week'] ?>x / semaine</span>
                    </div>
                </div>
                <p class="activity-desc"><?= esc($activity['description']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2>Besoin de changer d'objectif ?</h2>
            <p>Retournez à votre profil pour mettre à jour vos informations et découvrir de nouveaux régimes</p>
            <a href="/profile" class="cta-btn">← Modifier mon objectif</a>
        </div>
    </section>

</div>

<?= $this->endSection() ?>