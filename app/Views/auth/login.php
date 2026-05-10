<?= $this->extend('layouts/auth') ?>
<?= $this->section('content') ?>

<div class="auth-wrapper">
    <div class="auth-card">

            <h2>Connexion</h2>

            <?php if (session()->getFlashdata('success')): ?>
                <p style="color:green"><?= session()->getFlashdata('success') ?></p>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <p style="color:red"><?= esc($error) ?></p>
            <?php endif; ?>

            <form method="post" action="/login">
                <?= csrf_field() ?>

                <div>
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div>
                    <label>Mot de passe</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit">Se connecter</button>
            </form>

            <p>Pas encore de compte ? <a href="/register">S'inscrire</a></p>

    </div>
</div>

<?= $this->endSection() ?>

