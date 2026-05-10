<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1e293b; background: #fff; }

        .page { padding: 40px; }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .logo   { font-size: 20px; font-weight: bold; color: #0a2540; }
        .logo span { color: #2563a8; }
        .header-right { text-align: right; font-size: 11px; color: #475569; }

        hr { border: none; border-top: 1.5px solid #e2e8f0; margin: 20px 0; }

        /* Section */
        .section-title {
            font-size: 13px; font-weight: bold; color: #2563a8;
            text-transform: uppercase; letter-spacing: 0.05em;
            margin-bottom: 12px; padding-bottom: 4px;
            border-bottom: 2px solid #dbeafe;
        }

        /* Stats patient */
        .stats-grid { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 8px; }
        .stat-box {
            background: #f8fafc; border: 1px solid #e2e8f0;
            border-radius: 6px; padding: 8px 14px;
            min-width: 90px; text-align: center;
        }
        .stat-label { font-size: 9px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.04em; display: block; }
        .stat-value { font-size: 14px; font-weight: bold; color: #1e293b; display: block; margin-top: 2px; }
        .imc-val    { color: #2563a8; }

        /* Régime */
        .regime-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
        .regime-name   { font-size: 15px; font-weight: bold; color: #0a2540; }
        .regime-dur    { background: #eff6ff; color: #1a4a7a; border-radius: 99px; padding: 3px 10px; font-size: 10px; font-weight: bold; }
        .regime-desc   { color: #475569; font-size: 11px; line-height: 1.5; margin-bottom: 14px; }

        /* Composition */
        .comp-title { font-size: 10px; font-weight: bold; color: #475569; text-transform: uppercase; margin-bottom: 8px; }
        .comp-row   { display: flex; gap: 16px; }
        .comp-item  { text-align: center; }
        .comp-pct   { font-size: 16px; font-weight: bold; }
        .comp-name  { font-size: 9px; color: #94a3b8; display: block; margin-top: 2px; }
        .meat    { color: #ef4444; }
        .fish    { color: #3b82f6; }
        .poultry { color: #f59e0b; }

        /* Table activités */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #2563a8; color: #fff; }
        thead th { padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.04em; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        tbody td { padding: 8px 10px; font-size: 11px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .act-name { font-weight: bold; color: #0a2540; }

        /* Footer */
        .footer { margin-top: 30px; padding-top: 14px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
<div class="page">

    <!-- Header -->
    <div class="header">
        <div class="logo">Régime<span>Pro</span></div>
        <div class="header-right">
            <div>Généré le <?= date('d/m/Y à H:i') ?></div>
            <div>Patient : <strong><?= esc($username) ?></strong></div>
        </div>
    </div>

    <hr>

    <!-- Profil patient -->
    <div class="section-title">Profil du patient</div>
    <div class="stats-grid">
        <div class="stat-box"><span class="stat-label">Genre</span><span class="stat-value"><?= $profile['gender'] === 'H' ? 'Homme' : 'Femme' ?></span></div>
        <div class="stat-box"><span class="stat-label">Âge</span><span class="stat-value"><?= $profile['age'] ?> ans</span></div>
        <div class="stat-box"><span class="stat-label">Poids</span><span class="stat-value"><?= $profile['weight'] ?> kg</span></div>
        <div class="stat-box"><span class="stat-label">Taille</span><span class="stat-value"><?= $profile['height'] ?> cm</span></div>
        <div class="stat-box"><span class="stat-label">IMC</span><span class="stat-value imc-val"><?= $imc ?></span></div>
        <div class="stat-box"><span class="stat-label">Objectif</span><span class="stat-value" style="font-size:10px"><?= esc($goalLabel) ?></span></div>
    </div>

    <hr>

    <!-- Régime -->
    <div class="section-title">Régime prescrit</div>
    <div class="regime-header">
        <span class="regime-name"><?= esc($regime['name']) ?></span>
        <span class="regime-dur"><?= $regime['duration_days'] ?> jours</span>
    </div>
    <p class="regime-desc"><?= esc($regime['description']) ?></p>

    <div class="comp-title">Composition alimentaire</div>
    <div class="comp-row">
        <div class="comp-item"><span class="comp-pct meat"><?= $regime['meat_pct'] ?>%</span><span class="comp-name">Viande</span></div>
        <div class="comp-item"><span class="comp-pct fish"><?= $regime['fish_pct'] ?>%</span><span class="comp-name">Poisson</span></div>
        <div class="comp-item"><span class="comp-pct poultry"><?= $regime['poultry_pct'] ?>%</span><span class="comp-name">Volaille</span></div>
    </div>

    <hr>

    <!-- Activités sportives -->
    <div class="section-title">Activités sportives recommandées</div>
    <table>
        <thead>
            <tr>
                <th>Activité</th>
                <th>Durée</th>
                <th>Fréquence</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activities as $a): ?>
            <tr>
                <td><span class="act-name"><?= esc($a['name']) ?></span></td>
                <td><?= $a['duration_minutes'] ?> min</td>
                <td><?= $a['frequency_per_week'] ?>x / sem.</td>
                <td><?= esc($a['description']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        Ce document a été généré automatiquement par RégimePro. Consultez un professionnel de santé avant de suivre tout régime alimentaire.
    </div>

</div>
</body>
</html>