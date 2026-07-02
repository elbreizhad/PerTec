<?php
/** @var array|null $property */
$p = $property;
$val = fn($k, $d = '') => e((string) ($p[$k] ?? $d));
$isEdit = $p !== null;
$action = $isEdit ? url('/biens/' . $p['id']) : url('/biens');
?>
<div class="page-head">
    <h1><?= $isEdit ? 'Modifier le bien' : 'Nouveau bien' ?></h1>
    <a href="<?= $isEdit ? url('/biens/'.$p['id']) : url('/biens') ?>" class="btn">Annuler</a>
</div>

<form method="post" action="<?= $action ?>">
    <?= csrf_field() ?>

    <fieldset>
        <legend>Identification</legend>
        <div class="field"><label>Nom du bien *</label><input name="label" value="<?= $val('label') ?>" required placeholder="Ex : Studio rue Victor Hugo"></div>
        <div class="form-grid">
            <div class="field">
                <label>Type</label>
                <select name="type">
                    <?php foreach (Property::TYPES as $t): ?>
                        <option value="<?= e($t) ?>" <?= ($p['type'] ?? 'appartement')===$t?'selected':'' ?>><?= e(ucfirst($t)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field"><label>Surface (m²)</label><input name="surface_m2" value="<?= $val('surface_m2') ?>"></div>
            <div class="field"><label>Pièces</label><input name="rooms" value="<?= $val('rooms') ?>"></div>
        </div>
        <div class="field"><label>Adresse</label><input name="address" value="<?= $val('address') ?>"></div>
        <div class="form-grid">
            <div class="field"><label>Code postal</label><input name="postal_code" value="<?= $val('postal_code') ?>"></div>
            <div class="field"><label>Ville</label><input name="city" value="<?= $val('city') ?>"></div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Coût d'acquisition (investissement)</legend>
        <div class="form-grid">
            <div class="field"><label>Date d'achat</label><input type="date" name="purchase_date" value="<?= $val('purchase_date') ?>"></div>
            <div class="field"><label>Prix d'achat (€)</label><input name="purchase_price" value="<?= $val('purchase_price','0') ?>"></div>
            <div class="field"><label>Frais de notaire (€)</label><input name="notary_fees" value="<?= $val('notary_fees','0') ?>"></div>
            <div class="field"><label>Frais d'agence (€)</label><input name="agency_fees" value="<?= $val('agency_fees','0') ?>"></div>
            <div class="field"><label>Travaux (€)</label><input name="works_cost" value="<?= $val('works_cost','0') ?>"></div>
            <div class="field"><label>Autres frais (€)</label><input name="other_costs" value="<?= $val('other_costs','0') ?>"></div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Financement</legend>
        <div class="form-grid">
            <div class="field"><label>Capital emprunté (€)</label><input name="loan_amount" value="<?= $val('loan_amount','0') ?>"></div>
            <div class="field"><label>Taux annuel (%)</label><input name="loan_rate" value="<?= $val('loan_rate','0') ?>"></div>
            <div class="field"><label>Durée (mois)</label><input name="loan_duration_months" value="<?= $val('loan_duration_months','0') ?>"></div>
            <div class="field"><label>Mensualité (€, assurance incl.)</label><input name="loan_monthly" value="<?= $val('loan_monthly','0') ?>"></div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Charges annuelles récurrentes</legend>
        <div class="form-grid">
            <div class="field"><label>Taxe foncière (€/an)</label><input name="property_tax" value="<?= $val('property_tax','0') ?>"></div>
            <div class="field"><label>Assurance PNO (€/an)</label><input name="insurance_year" value="<?= $val('insurance_year','0') ?>"></div>
            <div class="field"><label>Charges copro non récup. (€/an)</label><input name="charges_year" value="<?= $val('charges_year','0') ?>"></div>
            <div class="field"><label>Frais de gestion (% du loyer)</label><input name="mgmt_fees_pct" value="<?= $val('mgmt_fees_pct','0') ?>"></div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Paramètres fiscaux LMNP</legend>
        <p class="hint">Utilisés pour le calcul des amortissements et du résultat en location meublée.</p>
        <div class="form-grid">
            <div class="field">
                <label>Régime fiscal</label>
                <select name="tax_regime">
                    <option value="reel" <?= ($p['tax_regime'] ?? 'reel')==='reel'?'selected':'' ?>>Réel</option>
                    <option value="micro" <?= ($p['tax_regime'] ?? '')==='micro'?'selected':'' ?>>Micro-BIC</option>
                </select>
            </div>
            <div class="field"><label>Part du terrain (%)</label><input name="land_share_pct" value="<?= $val('land_share_pct','15') ?>"><p class="hint">Non amortissable (souvent 10–20 %).</p></div>
            <div class="field"><label>Amort. bâti (ans)</label><input name="amort_years_building" value="<?= $val('amort_years_building','30') ?>"></div>
            <div class="field"><label>Amort. mobilier (ans)</label><input name="amort_years_furniture" value="<?= $val('amort_years_furniture','7') ?>"></div>
            <div class="field"><label>Amort. travaux (ans)</label><input name="amort_years_works" value="<?= $val('amort_years_works','10') ?>"></div>
            <div class="field"><label>Frais de comptable (€/an)</label><input name="accountant_fees" value="<?= $val('accountant_fees','0') ?>"></div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Notes</legend>
        <div class="field"><textarea name="notes"><?= $val('notes') ?></textarea></div>
    </fieldset>

    <div class="actions">
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Enregistrer' : 'Créer le bien' ?></button>
        <?php if ($isEdit): ?>
            <button form="delete-prop" class="btn btn-danger" onclick="return confirm('Supprimer ce bien et ses baux/loyers associés ?')">Supprimer</button>
        <?php endif; ?>
    </div>
</form>

<?php if ($isEdit): ?>
<form id="delete-prop" method="post" action="<?= url('/biens/'.$p['id'].'/delete') ?>"><?= csrf_field() ?></form>
<?php endif; ?>
