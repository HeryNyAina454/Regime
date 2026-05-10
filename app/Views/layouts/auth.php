<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'RégimePro' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Source+Sans+3:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('style.css') ?>">
</head>
<body>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

</body>
</html>