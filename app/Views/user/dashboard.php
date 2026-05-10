<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<div class="dashboard-wrapper">
    <h2>Bienvenue, <?= esc(session()->get('username')) ?> !</h2>
</div>

<?= $this->endSection() ?>