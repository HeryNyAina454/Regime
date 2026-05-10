<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>

<?php
$goalLabels = ['gain'=>'Augmenter','lose'=>'Réduire','ideal'=>'IMC idéal'];
$goalColors = ['gain'=>'kpi-green','lose'=>'kpi-orange','ideal'=>'kpi-blue'];
?>

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
            <?php foreach (session()->getFlashdata('errors') as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="crud-topbar">
    <div>
        <h2 class="crud-title">Activités sportives</h2>
        <p class="crud-sub"><?= count($activities) ?> activité(s)</p>
    </div>
    <button class="btn-primary" onclick="openModal('modal-create')" style="width:auto;margin-top:0">+ Nouvelle activité</button>
</div>

<div class="crud-table-wrap">
    <table class="dash-table">
        <thead>
            <tr><th>Nom</th><th>Objectif</th><th>Durée</th><th>Fréquence</th><th>Description</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php if (empty($activities)): ?>
            <tr><td colspan="6" class="empty-state">Aucune activité.</td></tr>
        <?php endif; ?>
        <?php foreach ($activities as $a): ?>
        <tr>
            <td><strong><?= esc($a['name']) ?></strong></td>
            <td><span class="goal-badge <?= $goalColors[$a['goal']] ?? '' ?>"><?= $goalLabels[$a['goal']] ?? '—' ?></span></td>
            <td><?= $a['duration_minutes'] ?> min</td>
            <td><?= $a['frequency_per_week'] ?>x / semaine</td>
            <td style="max-width:200px;font-size:.8rem;color:#475569"><?= esc(substr($a['description'],0,60)) ?>...</td>
            <td>
                <div class="action-btns">
                    <button class="btn-edit" onclick='openEditActivity(<?= json_encode($a) ?>)'>Modifier</button>
                    <a href="/admin/activities/delete/<?= $a['id'] ?>" class="btn-del" onclick="return confirm('Supprimer ?')">Supprimer</a>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modale créer -->
<div id="modal-create" class="modal-overlay" style="display:none">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Nouvelle activité</h3>
            <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
        </div>
        <form method="post" action="/admin/activities/store" class="modal-form">
            <?= csrf_field() ?>
            <?= $this->include('admin/activities/_form') ?>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-create')">Annuler</button>
                <button type="submit" class="btn-primary" style="width:auto;margin-top:0">Créer</button>
            </div>
        </form>
    </div>
</div>

<!-- Modale éditer -->
<div id="modal-edit" class="modal-overlay" style="display:none">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Modifier l'activité</h3>
            <button class="modal-close" onclick="closeModal('modal-edit')">✕</button>
        </div>
        <form method="post" id="edit-form" action="" class="modal-form">
            <?= csrf_field() ?>
            <?= $this->include('admin/activities/_form') ?>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-edit')">Annuler</button>
                <button type="submit" class="btn-primary" style="width:auto;margin-top:0">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id)  { document.getElementById(id).style.display='flex'; document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).style.display='none'; document.body.style.overflow=''; }
document.querySelectorAll('.modal-overlay').forEach(m => m.addEventListener('click', e => { if(e.target===m) closeModal(m.id); }));

function openEditActivity(a) {
    const form = document.getElementById('edit-form');
    form.action = '/admin/activities/update/' + a.id;
    const set = (name, val) => { const el = form.querySelector('[name="'+name+'"]'); if(el) el.value = val ?? ''; };
    set('name', a.name);
    set('description', a.description);
    set('goal', a.goal);
    set('duration_minutes', a.duration_minutes);
    set('frequency_per_week', a.frequency_per_week);
    openModal('modal-edit');
}

const modal = '<?= session()->getFlashdata('modal') ?>';
if (modal === 'create') openModal('modal-create');
</script>

<?= $this->endSection() ?>