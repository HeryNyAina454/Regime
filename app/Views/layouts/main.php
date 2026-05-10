<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Regime' ?></title>
    <link rel="stylesheet" href="<?= base_url('style.css') ?>">
</head>
<body>

    <?= $this->include('layouts/header') ?>

    <main class="main-content">
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->include('layouts/footer') ?>

</body>
</html>