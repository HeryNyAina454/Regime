<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Regime</title>
</head>
<body>

<h2>Informations personnelles</h2>

<?php if (isset($errors)): ?>
    <ul style="color:red">
        <?php foreach ($errors as $e): ?>
            <li><?= esc($e) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form method="post" action="/register">
    <?= csrf_field() ?>

    <div>
        <label>Nom d'utilisateur</label>
        <input type="text" name="username" value="<?= esc($old['username'] ?? '') ?>" required>
    </div>

    <div>
        <label>Email</label>
        <input type="email" name="email" value="<?= esc($old['email'] ?? '') ?>" required>
    </div>

    <div>
        <label>Mot de passe</label>
        <input type="password" name="password" required>
    </div>

    <div>
        <label>Genre</label>
        <select name="gender" required>
            <option value="">-- Choisir --</option>
            <option value="H" <?= (($old['gender'] ?? '') === 'H') ? 'selected' : '' ?>>Homme</option>
            <option value="F" <?= (($old['gender'] ?? '') === 'F') ? 'selected' : '' ?>>Femme</option>
        </select>
    </div>

    <button type="submit">Suivant →</button>
</form>

<p>Déjà inscrit ? <a href="/login">Se connecter</a></p>

</body>
</html>