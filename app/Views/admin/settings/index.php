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

<div class="crud-topbar">
    <div>
        <h2 class="crud-title">Paramètres de l'application</h2>
        <p class="crud-sub">Configurez les valeurs clés de RégimePro</p>
    </div>
    <button class="btn-primary" onclick="openModal('modal-create')" style="width:auto;margin-top:0">+ Nouveau paramètre</button>
</div>

<div class="settings-layout">

    <!-- Paramètres principaux -->
    <div class="settings-main-card">
        <h3 class="settings-section-title">⚙️ Paramètres principaux</h3>
        <form method="post" action="/admin/settings/update" class="modal-form" style="padding:0">
            <?= csrf_field() ?>

            <div class="setting-row">
                <div class="setting-info">
                    <span class="setting-label"><?= esc($settings['gold_price']['label'] ?? 'Prix Option Gold') ?></span>
                    <span class="setting-desc"><?= esc($settings['gold_price']['description'] ?? '') ?></span>
                </div>
                <div class="setting-control">
                    <div class="input-with-unit">
                        <input type="number" name="gold_price" step="0.01" min="1"
                               value="<?= esc($settings['gold_price']['value'] ?? '99.99') ?>" required>
                        <span class="unit">€</span>
                    </div>
                </div>
            </div>

            <div class="setting-row">
                <div class="setting-info">
                    <span class="setting-label"><?= esc($settings['gold_discount']['label'] ?? 'Remise Gold') ?></span>
                    <span class="setting-desc"><?= esc($settings['gold_discount']['description'] ?? '') ?></span>
                </div>
                <div class="setting-control">
                    <div class="input-with-unit">
                        <input type="number" name="gold_discount" min="1" max="100"
                               value="<?= esc($settings['gold_discount']['value'] ?? '15') ?>" required>
                        <span class="unit">%</span>
                    </div>
                </div>
            </div>

            <div class="setting-row">
                <div class="setting-info">
                    <span class="setting-label"><?= esc($settings['max_recharge']['label'] ?? 'Max recharge') ?></span>
                    <span class="setting-desc"><?= esc($settings['max_recharge']['description'] ?? '') ?></span>
                </div>
                <div class="setting-control">
                    <div class="input-with-unit">
                        <input type="number" name="max_recharge" step="0.01" min="1"
                               value="<?= esc($settings['max_recharge']['value'] ?? '500') ?>" required>
                        <span class="unit">€</span>
                    </div>
                </div>
            </div>

            <div style="padding:1.25rem 0 0;display:flex;justify-content:flex-end">
                <button type="submit" class="btn-primary" style="width:auto;margin-top:0">💾 Enregistrer les paramètres</button>
            </div>
        </form>
    </div>

    <!-- Tous les paramètres -->
    <div class="settings-all-card">
        <h3 class="settings-section-title">📋 Tous les paramètres</h3>
        <table class="dash-table">
            <thead><tr><th>Clé</th><th>Libellé</th><th>Valeur</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach ($settings as $key => $s): ?>
            <tr>
                <td><code class="code-mono"><?= esc($s['key_name']) ?></code></td>
                <td><?= esc($s['label']) ?></td>
                <td><strong><?= esc($s['value']) ?></strong></td>
                <td>
                    <?php if (!in_array($key, ['gold_price','gold_discount','max_recharge'])): ?>
                        <a href="/admin/settings/delete/<?= $s['id'] ?>"
                           class="btn-del"
                           onclick="return confirm('Supprimer ce paramètre ?')">Supprimer</a>
                    <?php else: ?>
                        <span style="font-size:.75rem;color:#94a3b8">Protégé</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($settings)): ?>
                <tr><td colspan="4" class="empty-state">Aucun paramètre.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- Modale nouveau paramètre -->
<div id="modal-create" class="modal-overlay" style="display:none">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Nouveau paramètre</h3>
            <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
        </div>
        <form method="post" action="/admin/settings/store" class="modal-form">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Clé (key_name) <span class="field-hint">unique, sans espaces</span></label>
                <input type="text" name="key_name" placeholder="ex : feature_x_enabled" required>
            </div>
            <div class="form-group">
                <label>Libellé</label>
                <input type="text" name="label" placeholder="ex : Activer la feature X" required>
            </div>
            <div class="form-group">
                <label>Valeur</label>
                <input type="text" name="value" placeholder="ex : true" required>
            </div>
            <div class="form-group">
                <label>Description <span class="field-hint">optionnel</span></label>
                <textarea name="description" rows="2" placeholder="Décrivez ce paramètre..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-create')">Annuler</button>
                <button type="submit" class="btn-primary" style="width:auto;margin-top:0">Ajouter</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id)  { document.getElementById(id).style.display='flex'; document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).style.display='none'; document.body.style.overflow=''; }
document.querySelectorAll('.modal-overlay').forEach(m => m.addEventListener('click', e => { if(e.target===m) closeModal(m.id); }));

const modal = '<?= session()->getFlashdata('modal') ?>';
if (modal === 'create') openModal('modal-create');
</script>

<?= $this->endSection() ?>