<div class="form-grid-2">
    <div class="form-group">
        <label>Nom du régime</label>
        <input type="text" name="name" placeholder="Ex : Régime Minceur" required>
    </div>
    <div class="form-group">
        <label>Objectif</label>
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
    <textarea name="description" rows="3" placeholder="Décrivez le régime..." required></textarea>
</div>

<!-- Composition -->
<div class="form-group">
    <label>Composition alimentaire <span class="pct-total-hint">(total : <span id="pct-total">0</span>%)</span></label>
    <div class="form-grid-3">
        <div>
            <label class="sublabel"><span class="dot dot-meat"></span> Viande %</label>
            <input type="number" name="meat_pct" min="0" max="100" placeholder="0" oninput="updateTotal(this)" required>
        </div>
        <div>
            <label class="sublabel"><span class="dot dot-fish"></span> Poisson %</label>
            <input type="number" name="fish_pct" min="0" max="100" placeholder="0" oninput="updateTotal(this)" required>
        </div>
        <div>
            <label class="sublabel"><span class="dot dot-poultry"></span> Volaille %</label>
            <input type="number" name="poultry_pct" min="0" max="100" placeholder="0" oninput="updateTotal(this)" required>
        </div>
    </div>
</div>

<!-- Variation poids -->
<div class="form-group">
    <label>Variation de poids (kg)
        <span class="field-hint">Positif = prise de poids, négatif = perte de poids</span>
    </label>
    <input type="number" name="weight_change_kg" step="0.1" placeholder="Ex : -3.5 ou +4.0" required>
</div>

<!-- Tarifs par durée -->
<div class="form-group">
    <label>Tarifs selon la durée</label>
    <table class="pricing-table">
        <thead>
            <tr>
                <th>Durée (jours)</th>
                <th>Prix (€)</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="pricing-rows"></tbody>
    </table>
    <button type="button" class="add-pricing-btn" onclick="addPricingRow(this.closest('form').querySelector('#pricing-rows'))">
        + Ajouter une durée
    </button>
</div>

<script>
function updateTotal(el) {
    const form   = el.closest('form');
    const meat   = parseInt(form.querySelector('[name="meat_pct"]').value)    || 0;
    const fish   = parseInt(form.querySelector('[name="fish_pct"]').value)    || 0;
    const poultry= parseInt(form.querySelector('[name="poultry_pct"]').value) || 0;
    const total  = meat + fish + poultry;
    const hint   = form.querySelector('#pct-total');
    if (hint) {
        hint.textContent = total;
        hint.style.color = total === 100 ? '#16a34a' : '#dc2626';
    }
}
</script>