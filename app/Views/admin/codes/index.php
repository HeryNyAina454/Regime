<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<!-- Alertes -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success" style="margin-bottom:1.5rem">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-error" style="margin-bottom:1.5rem">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/></svg>
        <ul style="margin:0;padding-left:1rem">
            <?php foreach (session()->getFlashdata('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- KPIs codes -->
<div class="kpi-grid" style="margin-bottom:1.5rem">
    <div class="kpi-card">
        <div class="kpi-icon kpi-blue">🎟️</div>
        <div class="kpi-info"><span class="kpi-value"><?= $totalCodes ?></span><span class="kpi-label">Codes total</span></div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon kpi-green">✅</div>
        <div class="kpi-info"><span class="kpi-value"><?= $usedCodes ?></span><span class="kpi-label">Codes utilisés</span></div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon kpi-orange">⏳</div>
        <div class="kpi-info"><span class="kpi-value"><?= $totalCodes - $usedCodes ?></span><span class="kpi-label">Codes disponibles</span></div>
    </div>
    <div class="kpi-card">
        <div class="kpi-icon kpi-purple">💶</div>
        <div class="kpi-info"><span class="kpi-value"><?= $maxRecharge ?> €</span><span class="kpi-label">Max par code</span></div>
    </div>
</div>

<div class="crud-topbar">
    <div>
        <h2 class="crud-title">Codes portefeuille</h2>
        <p class="crud-sub">Gérez et validez les codes de recharge</p>
    </div>
    <button class="btn-primary" onclick="openModal('modal-create')" style="width:auto;margin-top:0">+ Nouveau code</button>
</div>

<div class="crud-table-wrap">
    <table class="dash-table">
        <thead>
            <tr><th>Code</th><th>Montant</th><th>Statut</th><th>Utilisé par</th><th>Date utilisation</th><th>Créé le</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php if (empty($codes)): ?>
            <tr><td colspan="7" class="empty-state">Aucun code.</td></tr>
        <?php endif; ?>
        <?php foreach ($codes as $c): ?>
        <tr>
            <td><code class="code-mono"><?= esc($c['code']) ?></code></td>
            <td><strong><?= number_format($c['amount'], 2) ?> €</strong></td>
            <td>
                <?php if ($c['is_used']): ?>
                    <span class="status-badge status-used">Utilisé</span>
                <?php else: ?>
                    <span class="status-badge status-available">Disponible</span>
                <?php endif; ?>
            </td>
            <td><?= $c['used_by_name'] ? esc($c['used_by_name']) : '—' ?></td>
            <td><?= $c['used_at'] ? date('d/m/Y H:i', strtotime($c['used_at'])) : '—' ?></td>
            <td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
            <td>
                <div class="action-btns">
                    <?php if ($c['is_used']): ?>
                        <a href="/admin/codes/validate/<?= $c['id'] ?>"
                           class="btn-validate"
                           onclick="return confirm('Réinitialiser ce code ?')">
                           Réinitialiser
                        </a>
                    <?php endif; ?>
                    <a href="/admin/codes/delete/<?= $c['id'] ?>"
                       class="btn-del"
                       onclick="return confirm('Supprimer ce code ?')">Supprimer</a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modale créer code -->
<div id="modal-create" class="modal-overlay" style="display:none">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Nouveau code de recharge</h3>
            <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
        </div>
        <form method="post" action="/admin/codes/store" class="modal-form">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Code</label>
                <div style="display:flex;gap:.5rem">
                    <input type="text" name="code" id="code-input" placeholder="Ex : PROMO-ETE-2025"
                           style="text-transform:uppercase;flex:1" maxlength="50" required>
                    <button type="button" class="btn-generate" onclick="generateCode()">Générer</button>
                </div>
                <p class="code-hint">Majuscules, chiffres et tirets uniquement.</p>
            </div>
            <div class="form-group">
                <label>Montant (€) — max <?= $maxRecharge ?> €</label>
                <input type="number" name="amount" step="0.01" min="1" max="<?= $maxRecharge ?>" placeholder="Ex : 20.00" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-create')">Annuler</button>
                <button type="submit" class="btn-primary" style="width:auto;margin-top:0">Créer le code</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id)  { document.getElementById(id).style.display='flex'; document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).style.display='none'; document.body.style.overflow=''; }
document.querySelectorAll('.modal-overlay').forEach(m => m.addEventListener('click', e => { if(e.target===m) closeModal(m.id); }));

function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    const parts = [4,4,4].map(n => Array.from({length:n}, () => chars[Math.floor(Math.random()*chars.length)]).join(''));
    document.getElementById('code-input').value = 'CODE-' + parts.join('-');
}

const modal = '<?= session()->getFlashdata('modal') ?>';
if (modal === 'create') openModal('modal-create');
</script>

<?= $this->endSection() ?>