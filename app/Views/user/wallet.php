<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="wallet-wrapper">

    <!-- En-tête solde -->
    <div class="wallet-hero">
        <div class="wallet-hero-left">
            <p class="hero-label">Mon portefeuille</p>
            <div class="wallet-balance-display">
                <span class="balance-amount"><?= number_format($wallet['balance'], 2) ?></span>
                <span class="balance-currency">€</span>
            </div>
            <?php if ($wallet['is_gold']): ?>
                <span class="badge-gold">⭐ Option Gold — 15% de remise sur tous les régimes</span>
            <?php else: ?>
                <span class="badge-standard">Compte Standard</span>
            <?php endif; ?>
        </div>

        <div class="wallet-hero-right">
            <div class="wallet-stat">
                <span class="wstat-value"><?= count($orders) ?></span>
                <span class="wstat-label">Régime(s) acheté(s)</span>
            </div>
            <div class="wallet-stat">
                <span class="wstat-value"><?= count($usedCodes) ?></span>
                <span class="wstat-label">Code(s) utilisé(s)</span>
            </div>
        </div>
    </div>

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

    <div class="wallet-grid">

        <!-- Colonne gauche : saisie code + historique codes -->
        <div class="wallet-col">

            <!-- Saisie code -->
            <div class="wallet-card">
                <h2 class="wallet-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-4 0v2"/><line x1="12" y1="12" x2="12" y2="16"/><circle cx="12" cy="12" r="1" fill="currentColor"/></svg>
                    Recharger avec un code
                </h2>
                <p class="wallet-card-desc">Entrez votre code de recharge pour créditer votre solde instantanément.</p>

                <form method="post" action="/wallet/recharge" class="code-form">
                    <?= csrf_field() ?>
                    <div class="code-input-group">
                        <input
                            type="text"
                            name="code"
                            placeholder="Ex : REGIME-BOOST-20"
                            maxlength="50"
                            autocomplete="off"
                            style="text-transform:uppercase"
                            required
                        >
                        <button type="submit" class="btn-recharge">Valider</button>
                    </div>
                    <p class="code-hint">Le code est insensible à la casse.</p>
                </form>
            </div>

            <!-- Historique codes utilisés -->
            <div class="wallet-card">
                <h2 class="wallet-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                    Codes utilisés
                </h2>

                <?php if (empty($usedCodes)): ?>
                    <p class="empty-state">Aucun code utilisé pour l'instant.</p>
                <?php else: ?>
                    <div class="history-list">
                        <?php foreach ($usedCodes as $c): ?>
                        <div class="history-item">
                            <div class="history-left">
                                <span class="history-code"><?= esc($c['code']) ?></span>
                                <span class="history-date"><?= date('d/m/Y à H:i', strtotime($c['used_at'])) ?></span>
                            </div>
                            <span class="history-amount credit">+<?= number_format($c['amount'], 2) ?> €</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        </div>

        <!-- Colonne droite : historique achats -->
        <div class="wallet-col">
            <div class="wallet-card">
                <h2 class="wallet-card-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    Historique des achats
                </h2>

                <?php if (empty($orders)): ?>
                    <p class="empty-state">Aucun achat effectué pour l'instant.</p>
                    <a href="/suggestions" class="btn-secondary" style="margin-top:1rem;display:inline-flex">Voir les régimes →</a>
                <?php else: ?>
                    <div class="history-list">
                        <?php foreach ($orders as $order): ?>
                        <div class="history-item">
                            <div class="history-left">
                                <span class="history-regime"><?= esc($order['regime_name']) ?></span>
                                <span class="history-date"><?= date('d/m/Y à H:i', strtotime($order['ordered_at'])) ?></span>
                            </div>
                            <span class="history-amount debit">-<?= number_format($order['price_paid'], 2) ?> €</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

</div>

<?= $this->endSection() ?>