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
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <ul style="margin:0;padding-left:1rem">
            <?php foreach (session()->getFlashdata('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- Header -->
<div class="crud-topbar">
    <div>
        <h2 class="crud-title">Régimes alimentaires</h2>
        <p class="crud-sub"><?= count($regimes) ?> régime(s) disponible(s)</p>
    </div>
    <button class="btn-primary" onclick="openModal('modal-create')" style="width:auto;margin-top:0">
        + Nouveau régime
    </button>
</div>

<!-- Tableau -->
<div class="crud-table-wrap">
    <table class="dash-table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Objectif</th>
                <th>Composition</th>
                <th>Variation poids</th>
                <th>Tarifs</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($regimes)): ?>
            <tr><td colspan="6" class="empty-state">Aucun régime. Créez-en un !</td></tr>
        <?php endif; ?>
        <?php
        $goalLabels = ['gain'=>'Augmenter','lose'=>'Réduire','ideal'=>'IMC idéal'];
        $goalColors = ['gain'=>'kpi-green','lose'=>'kpi-orange','ideal'=>'kpi-blue'];
        foreach ($regimes as $regime):
        ?>
        <tr>
            <td>
                <strong><?= esc($regime['name']) ?></strong>
                <div style="font-size:.75rem;color:#94a3b8;margin-top:.15rem"><?= esc(substr($regime['description'],0,50)) ?>...</div>
            </td>
            <td><span class="goal-badge <?= $goalColors[$regime['goal']] ?? '' ?>"><?= $goalLabels[$regime['goal']] ?? '—' ?></span></td>
            <td>
                <div class="comp-mini">
                    <span class="dot dot-meat"></span><?= $regime['meat_pct'] ?>%
                    <span class="dot dot-fish" style="margin-left:.4rem"></span><?= $regime['fish_pct'] ?>%
                    <span class="dot dot-poultry" style="margin-left:.4rem"></span><?= $regime['poultry_pct'] ?>%
                </div>
            </td>
            <td>
                <?php $w = $regime['weight_change_kg']; ?>
                <span class="weight-badge <?= $w >= 0 ? 'weight-up' : 'weight-down' ?>">
                    <?= $w >= 0 ? '+' : '' ?><?= $w ?> kg
                </span>
            </td>
            <td>
                <div class="pricing-list">
                    <?php foreach ($regime['pricing'] as $p): ?>
                        <span class="pricing-chip"><?= $p['duration_days'] ?>j — <?= number_format($p['price'],2) ?>€</span>
                    <?php endforeach; ?>
                    <?php if (empty($regime['pricing'])): ?>
                        <span style="color:#94a3b8;font-size:.75rem">Aucun tarif</span>
                    <?php endif; ?>
                </div>
            </td>
            <td>
                <div class="action-btns">
                    <button class="btn-edit" onclick='openEditModal(<?= json_encode($regime) ?>)'>Modifier</button>
                    <a href="/admin/regimes/delete/<?= $regime['id'] ?>"
                       class="btn-del"
                       onclick="return confirm('Supprimer ce régime ?')">Supprimer</a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- ── MODALE CRÉER ─────────────────────────────────────────── -->
<div id="modal-create" class="modal-overlay" style="display:none">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Nouveau régime</h3>
            <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
        </div>
        <form method="post" action="/admin/regimes/store" class="modal-form">
            <?= csrf_field() ?>
            <?= $this->include('admin/regimes/_form') ?>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-create')">Annuler</button>
                <button type="submit" class="btn-primary" style="width:auto;margin-top:0">Créer le régime</button>
            </div>
        </form>
    </div>
</div>

<!-- ── MODALE ÉDITER ───────────────────────────────────────── -->
<div id="modal-edit" class="modal-overlay" style="display:none">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Modifier le régime</h3>
            <button class="modal-close" onclick="closeModal('modal-edit')">✕</button>
        </div>
        <form method="post" id="edit-form" action="" class="modal-form">
            <?= csrf_field() ?>
            <?= $this->include('admin/regimes/_form') ?>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-edit')">Annuler</button>
                <button type="submit" class="btn-primary" style="width:auto;margin-top:0">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
// Ouvrir/fermer modales
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
    document.body.style.overflow = '';
}
document.querySelectorAll('.modal-overlay').forEach(m => {
    m.addEventListener('click', e => { if (e.target === m) closeModal(m.id); });
});

// Ouvrir modale édition et pré-remplir
function openEditModal(regime) {
    const form = document.getElementById('edit-form');
    form.action = '/admin/regimes/update/' + regime.id;

    const set = (name, val) => {
        const el = form.querySelector('[name="' + name + '"]');
        if (el) el.value = val ?? '';
    };

    set('name',             regime.name);
    set('description',      regime.description);
    set('goal',             regime.goal);
    set('meat_pct',         regime.meat_pct);
    set('fish_pct',         regime.fish_pct);
    set('poultry_pct',      regime.poultry_pct);
    set('weight_change_kg', regime.weight_change_kg);

    // Remplir les lignes de tarifs
    const tbody = form.querySelector('#pricing-rows');
    tbody.innerHTML = '';
    if (regime.pricing && regime.pricing.length) {
        regime.pricing.forEach(p => addPricingRow(tbody, p.duration_days, p.price));
    } else {
        addPricingRow(tbody);
    }

    openModal('modal-edit');
}

// Gestion des lignes de tarifs dynamiques
function addPricingRow(tbody, days='', price='') {
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="number" name="duration_days[]" value="${days}" placeholder="ex: 30" min="1" class="pricing-input" required></td>
        <td><input type="number" name="prices[]" value="${price}" placeholder="ex: 29.99" step="0.01" min="0" class="pricing-input" required></td>
        <td><button type="button" class="btn-del-row" onclick="this.closest('tr').remove()">✕</button></td>
    `;
    tbody.appendChild(tr);
}

// Initialiser les modales au chargement
document.addEventListener('DOMContentLoaded', () => {
    ['modal-create','modal-edit'].forEach(id => {
        const tbody = document.querySelector('#' + id + ' #pricing-rows');
        if (tbody && tbody.children.length === 0) addPricingRow(tbody);
    });

    document.querySelectorAll('.add-pricing-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const tbody = btn.closest('form').querySelector('#pricing-rows');
            addPricingRow(tbody);
        });
    });

    // Rouvrir modale si erreur flash
    const modal = '<?= session()->getFlashdata('modal') ?>';
    if (modal === 'create') openModal('modal-create');
    else if (modal && modal.startsWith('edit_')) {
        // On ne peut pas ré-ouvrir l'edit sans les données, rediriger simplement
    }
});
</script>

<?= $this->endSection() ?>