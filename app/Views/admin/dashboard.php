<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- KPIs -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-icon kpi-blue">👥</div>
        <div class="kpi-info"><span class="kpi-value"><?= $totalUsers ?></span><span class="kpi-label">Utilisateurs</span></div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon kpi-green">🛒</div>
        <div class="kpi-info"><span class="kpi-value"><?= $totalOrders ?></span><span class="kpi-label">Commandes</span></div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon kpi-orange">💰</div>
        <div class="kpi-info"><span class="kpi-value"><?= number_format($totalRevenue, 2) ?> €</span><span class="kpi-label">Revenus totaux</span></div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon kpi-gold">⭐</div>
        <div class="kpi-info"><span class="kpi-value"><?= $goldUsers ?></span><span class="kpi-label">Membres Gold</span></div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon kpi-purple">🎟️</div>
        <div class="kpi-info"><span class="kpi-value"><?= $usedCodes ?> / <?= $totalCodes ?></span><span class="kpi-label">Codes utilisés</span></div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon kpi-teal">📊</div>
        <div class="kpi-info"><span class="kpi-value"><?= $imcData->avg_imc ?? '—' ?></span><span class="kpi-label">IMC moyen</span></div>
    </div>
</div>

<div class="dash-grid">

    <!-- Revenus mensuels -->
    <div class="dash-card dash-chart-card">
        <h2 class="dash-card-title">Revenus mensuels</h2>
        <canvas id="revenueChart" height="200"></canvas>
    </div>

    <!-- Inscriptions mensuelles -->
    <div class="dash-card">
        <h2 class="dash-card-title">Nouvelles inscriptions</h2>
        <canvas id="usersChart" height="200"></canvas>
    </div>

    <!-- Répartition objectifs -->
    <div class="dash-card">
        <h2 class="dash-card-title">Répartition des objectifs</h2>
        <canvas id="goalChart" height="220"></canvas>
        <div class="chart-legend" id="goalLegend"></div>
    </div>

    <!-- Répartition IMC -->
    <div class="dash-card">
        <h2 class="dash-card-title">Répartition IMC utilisateurs</h2>
        <canvas id="imcChart" height="220"></canvas>
    </div>

    <!-- Top régimes -->
    <div class="dash-card">
        <h2 class="dash-card-title">Top régimes vendus</h2>
        <table class="dash-table">
            <thead><tr><th>Régime</th><th>Ventes</th><th>Revenus</th></tr></thead>
            <tbody>
                <?php foreach ($topRegimes as $r): ?>
                <tr>
                    <td><?= esc($r['name']) ?></td>
                    <td><span class="badge-count"><?= $r['sales'] ?></span></td>
                    <td><?= number_format($r['revenue'], 2) ?> €</td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($topRegimes)): ?>
                    <tr><td colspan="3" class="empty-state">Aucune vente</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Derniers utilisateurs -->
    <div class="dash-card dash-full">
        <h2 class="dash-card-title">Derniers inscrits</h2>
        <table class="dash-table">
            <thead>
                <tr><th>Utilisateur</th><th>Email</th><th>Objectif</th><th>Solde</th><th>Gold</th><th>Inscription</th></tr>
            </thead>
            <tbody>
                <?php
                $goalLabels = ['gain'=>'Augmenter','lose'=>'Réduire','ideal'=>'IMC idéal'];
                foreach ($recentUsers as $u): ?>
                <tr>
                    <td><strong><?= esc($u['username']) ?></strong></td>
                    <td><?= esc($u['email']) ?></td>
                    <td><?= esc($goalLabels[$u['goal']] ?? '—') ?></td>
                    <td><?= number_format($u['balance'] ?? 0, 2) ?> €</td>
                    <td><?= $u['is_gold'] ? '<span class="badge-gold-sm">⭐ Gold</span>' : '—' ?></td>
                    <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($recentUsers)): ?>
                    <tr><td colspan="6" class="empty-state">Aucun utilisateur</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenus mensuels
const revMonths  = <?= json_encode(array_column($monthlyRevenue, 'month')) ?>;
const revTotals  = <?= json_encode(array_map('floatval', array_column($monthlyRevenue, 'revenue'))) ?>;
new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
        labels: revMonths.length ? revMonths : ['—'],
        datasets: [{ label: 'Revenus (€)', data: revTotals.length ? revTotals : [0],
            borderColor:'#2563a8', backgroundColor:'rgba(37,99,168,0.08)',
            borderWidth:2, fill:true, tension:0.4, pointRadius:4 }]
    },
    options: { responsive:true, plugins:{legend:{display:false}},
        scales:{ y:{beginAtZero:true,grid:{color:'#f1f5f9'}}, x:{grid:{display:false}} } }
});

// Inscriptions mensuelles
const uMonths = <?= json_encode(array_column($monthlyUsers, 'month')) ?>;
const uTotals = <?= json_encode(array_map('intval', array_column($monthlyUsers, 'total'))) ?>;
new Chart(document.getElementById('usersChart'), {
    type: 'bar',
    data: {
        labels: uMonths.length ? uMonths : ['—'],
        datasets: [{ label: 'Inscriptions', data: uTotals.length ? uTotals : [0],
            backgroundColor:'rgba(22,163,74,0.15)', borderColor:'#16a34a',
            borderWidth:2, borderRadius:6 }]
    },
    options: { responsive:true, plugins:{legend:{display:false}},
        scales:{ y:{beginAtZero:true,grid:{color:'#f1f5f9'}}, x:{grid:{display:false}} } }
});

// Objectifs (donut)
const goalData  = <?= json_encode($goalStats) ?>;
const goalMap   = { gain:'Augmenter', lose:'Réduire', ideal:'IMC idéal' };
new Chart(document.getElementById('goalChart'), {
    type: 'doughnut',
    data: {
        labels: goalData.map(g => goalMap[g.goal] || g.goal),
        datasets: [{ data: goalData.map(g => g.total),
            backgroundColor:['#2563a8','#16a34a','#f59e0b'],
            borderWidth:0, hoverOffset:6 }]
    },
    options: { responsive:true, cutout:'65%',
        plugins:{ legend:{ position:'bottom', labels:{ padding:16, font:{size:12} } } } }
});

// IMC répartition (donut)
const imc = <?= json_encode((array)$imcData) ?>;
new Chart(document.getElementById('imcChart'), {
    type: 'doughnut',
    data: {
        labels: ['Insuffisance', 'Normal', 'Surpoids', 'Obésité'],
        datasets: [{ data: [imc.under||0, imc.normal||0, imc.over||0, imc.obese||0],
            backgroundColor:['#3b82f6','#16a34a','#f59e0b','#ef4444'],
            borderWidth:0, hoverOffset:6 }]
    },
    options: { responsive:true, cutout:'65%',
        plugins:{ legend:{ position:'bottom', labels:{ padding:16, font:{size:12} } } } }
});
</script>

<?= $this->endSection() ?>