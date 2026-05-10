<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-wrapper">
    
    <h2>Informations de santé</h2>

    <?php if (isset($errors)): ?>
        <ul style="color:red">
            <?php foreach ($errors as $e): ?>
                <li><?= esc($e) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="/register/health">
        <?= csrf_field() ?>

        <div>
            <label>Poids (kg)</label>
            <input type="number" step="0.1" name="weight" value="<?= esc($old['weight'] ?? '') ?>" required>
        </div>

        <div>
            <label>Taille (cm)</label>
            <input type="number" step="0.1" name="height" value="<?= esc($old['height'] ?? '') ?>" required>
        </div>

        <div>
            <label>Âge</label>
            <input type="number" name="age" value="<?= esc($old['age'] ?? '') ?>" required>
        </div>

        <button type="submit">Créer mon compte ✓</button>
    </form>

</div>

<?= $this->endSection() ?>
