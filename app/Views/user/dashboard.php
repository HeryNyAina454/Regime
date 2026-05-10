<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="dash-wrapper">

    <!-- Bienvenue -->
    <div class="dash-welcome">
        <div>
            <h1 class="dash-title">Bienvenue, <?= esc(session()->get('username')) ?> 👋</h1>
            <p class="dash-sub">Voici un résumé de votre activité sur RégimePro</p>
        </div>
        <?php if ($wallet['is_gold']): ?>
            <span class="badge-gold">⭐ Membre Gold</span>
        <?php endif; ?>
    </div>

    <!-- KPIs -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-icon kpi-blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 12V22H4V12"/><path d="M22 7H2v5h20V7z"/><path d="M12 22V7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>
            </div>
            <div class="kpi-info">
                <span class="kpi-value"><?= number_format($wallet['balance'], 2) ?> €</span>
                <span class="kpi-label">Solde portefeuille</span>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon kpi-green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            </div>
            <div class="kpi-info">
                <span class="kpi-value"><?= $ordersCount ?></span>
                <span class="kpi-label">Régimes achetés</span>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon kpi-purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            </div>
            <div class="kpi-info">
                <span class="kpi-value"><?= $usedCodes ?></span>
                <span class="kpi-label">Codes utilisés</span>
            </div>
        </div>
        <div class="kpi-card">
            <div class="kpi-icon kpi-orange">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
            </div>
            <div class="kpi-info">
                <span class="kpi-value"><?= $imc ?? '—' ?></span>
                <span class="kpi-label">Votre IMC</span>
            </div>
        </div>
    </div>

    <div class="dash-grid">

        <!-- Graphe dépenses mensuelles -->
        <div class="dash-card dash-chart-card">
            <h2 class="dash-card-title">Dépenses des 6 derniers mois</h2>
            <canvas id="spendChart" height="200"></canvas>
        </div>

        <!-- IMC + objectif -->
        <div class="dash-card">
            <h2 class="dash-card-title">Mon profil santé</h2>
            <?php if ($profile && $imc): ?>
                <div class="imc-block imc-<?= $imcColor ?>" style="margin-bottom:1rem">
                    <div class="imc-value"><?= $imc ?></div>
                    <div class="imc-info">
                        <span class="imc-label">Votre IMC</span>
                        <span class="imc-status"><?= $imcLabel ?></span>
                    </div>
                </div>
                <div class="profile-info-row">
                    <span>🎯 Objectif</span>
                    <strong><?= esc($goalLabel) ?></strong>
                </div>
                <div class="profile-info-row">
                    <span>⚖️ Poids</span>
                    <strong><?= $profile['weight'] ?> kg</strong>
                </div>
                <div class="profile-info-row">
                    <span>📏 Taille</span>
                    <strong><?= $profile['height'] ?> cm</strong>
                </div>
                <a href="/profile" class="btn-secondary" style="margin-top:1rem;display:inline-flex;font-size:.8rem">Modifier →</a>
            <?php else: ?>
                <p class="empty-state">Profil incomplet. <a href="/profile">Compléter →</a></p>
            <?php endif; ?>
        </div>

        <!-- Dernières commandes -->
        <div class="dash-card dash-full">
            <h2 class="dash-card-title">Dernières commandes</h2>
            <?php if (empty($orders)): ?>
                <p class="empty-state">Aucune commande. <a href="/suggestions">Voir les régimes →</a></p>
            <?php else: ?>
                <table class="dash-table">
                    <thead><tr><th>Régime</th><th>Date</th><th>Montant</th></tr></thead>
                    <tbody>
                        <?php foreach (array_slice($orders, 0, 5) as $o): ?>
                        <tr>
                            <td><?= esc($o['regime_name']) ?></td>
                            <td><?= date('d/m/Y', strtotime($o['ordered_at'])) ?></td>
                            <td><span class="debit-badge">-<?= number_format($o['price_paid'], 2) ?> €</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const months = <?= json_encode(array_column($monthlySpend, 'month')) ?>;
const totals = <?= json_encode(array_map('floatval', array_column($monthlySpend, 'total'))) ?>;

new Chart(document.getElementById('spendChart'), {
    type: 'bar',
    data: {
        labels: months.length ? months : ['Aucune donnée'],
        datasets: [{
            label: 'Dépenses (€)',
            data: totals.length ? totals : [0],
            backgroundColor: 'rgba(37,99,168,0.15)',
            borderColor: '#2563a8',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
            x: { grid: { display: false } }
        }
    }
});
</script>

<?= $this->endSection() ?>