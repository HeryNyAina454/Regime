<div class="form-grid-2">
    <div class="form-group">
        <label>Nom de l'activité</label>
        <input type="text" name="name" placeholder="Ex : Musculation" required>
    </div>
    <div class="form-group">
        <label>Objectif ciblé</label>
        <select name="goal" required>
            <option value="">-- Choisir --</option>
            <option value="gain">Augmenter le poids</option>
            <option value="lose">Réduire le poids</option>
            <option value="ideal">Atteindre l'IMC idéal</option>
        </select>
    </div>
</div>

<div class="form-group">
    <label>Description</label>
    <textarea name="description" rows="3" placeholder="Décrivez l'activité..." required></textarea>
</div>

<div class="form-grid-2">
    <div class="form-group">
        <label>Durée (minutes)</label>
        <input type="number" name="duration_minutes" min="1" max="300" placeholder="Ex : 45" required>
    </div>
    <div class="form-group">
        <label>Fréquence (fois / semaine)</label>
        <input type="number" name="frequency_per_week" min="1" max="7" placeholder="Ex : 3" required>
    </div>
</div>