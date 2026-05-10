<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="gold-wrapper">

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

    <!-- Hero Gold -->
    <div class="gold-hero">
        <div class="gold-stars">★ ★ ★</div>
        <h1 class="gold-hero-title">Option <span>Gold</span></h1>
        <p class="gold-hero-sub">Accédez à tous les avantages premium en un seul paiement</p>
    </div>

    <div class="gold-layout">

        <!-- Carte avantages -->
        <div class="gold-benefits-card">
            <h2 class="gold-section-title">Ce que vous obtenez</h2>

            <ul class="benefits-list">
                <li class="benefit-item">
                    <div class="benefit-icon">%</div>
                    <div class="benefit-text">
                        <strong>15% de remise permanente</strong>
                        <span>Sur tous les régimes disponibles, sans exception</span>
                    </div>
                </li>
                <li class="benefit-item">
                    <div class="benefit-icon">⭐</div>
                    <div class="benefit-text">
                        <strong>Badge Gold exclusif</strong>
                        <span>Affiché sur votre profil et votre portefeuille</span>
                    </div>
                </li>
                <li class="benefit-item">
                    <div class="benefit-icon">♾</div>
                    <div class="benefit-text">
                        <strong>Accès à vie</strong>
                        <span>Un seul paiement, aucun abonnement, aucune surprise</span>
                    </div>
                </li>
                <li class="benefit-item">
                    <div class="benefit-icon">💰</div>
                    <div class="benefit-text">
                        <strong>Économies garanties</strong>
                        <span>Rentabilisé dès votre 3ème régime acheté</span>
                    </div>
                </li>
            </ul>

            <!-- Exemple d'économie -->
            <div class="savings-example">
                <p class="savings-title">Exemple d'économie</p>
                <div class="savings-row">
                    <span>Régime à 44.99 €</span>
                    <span class="savings-before">44.99 €</span>
                </div>
                <div class="savings-row highlight">
                    <span>Prix Gold (−15%)</span>
                    <span class="savings-after">38.24 €</span>
                </div>
                <div class="savings-row total">
                    <span>Vous économisez</span>
                    <span class="savings-gain">6.75 €</span>
                </div>
            </div>
        </div>

        <!-- Carte paiement -->
        <div class="gold-payment-card">

            <?php if ($wallet['is_gold']): ?>

                <!-- Déjà Gold -->
                <div class="already-gold">
                    <div class="gold-check">✓</div>
                    <h3>Vous êtes déjà Gold !</h3>
                    <p>Profitez de vos 15% de remise sur tous les régimes.</p>
                    <a href="/suggestions" class="btn-gold-action">Voir les régimes →</a>
                </div>

            <?php else: ?>

                <h2 class="gold-section-title">Activer l'option Gold</h2>

                <div class="gold-price-display">
                    <span class="gold-price-amount"><?= number_format($goldPrice, 2) ?></span>
                    <span class="gold-price-currency">€</span>
                    <span class="gold-price-label">paiement unique</span>
                </div>

                <!-- Solde disponible -->
                <div class="wallet-balance-row <?= $canAfford ? 'sufficient' : 'insufficient' ?>">
                    <span class="balance-label">Votre solde actuel</span>
                    <span class="balance-val"><?= number_format($wallet['balance'], 2) ?> €</span>
                </div>

                <?php if (!$canAfford): ?>
                    <div class="insufficient-msg">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="14" height="14"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Il vous manque <strong><?= number_format($goldPrice - $wallet['balance'], 2) ?> €</strong>.
                        <a href="/wallet">Recharger mon portefeuille</a>
                    </div>
                <?php endif; ?>

                <form method="post" action="/gold/activate">
                    <?= csrf_field() ?>
                    <button
                        type="submit"
                        class="btn-gold-activate <?= !$canAfford ? 'disabled' : '' ?>"
                        <?= !$canAfford ? 'disabled' : '' ?>
                    >
                        ⭐ Activer l'option Gold pour <?= number_format($goldPrice, 2) ?> €
                    </button>
                </form>

                <p class="gold-notice">Le montant sera débité de votre portefeuille RégimePro.</p>

            <?php endif; ?>

        </div>

    </div>

</div>

<?= $this->endSection() ?>