<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord - RégimePro</title>
</head>
<body>
    <h2>Bienvenue, <?= esc(session()->get('username')) ?> !</h2>
    <a href="/logout">Se déconnecter</a>
</body>
</html>