<?php /** @var array|null $lease */ /** @var array $properties */ /** @var array $tenants */
$l = $lease; $isEdit = $l !== null;
$val = fn($k, $d='') => e((string) ($l[$k] ?? $d));
$action = $isEdit ? url('/baux/'.$l['id']) : url('/baux');
?>
<div class="page-head">
    <h1><?= $isEdit ? 'Modifier le bail' : 'Nouveau bail' ?></h1>
    <a href="<?= $isEdit ? url('/baux/'.$l['id']) : url('/baux') ?>" class="btn">Annuler</a>
</div>

<?php if (!$properties || !$tenants): ?>
    <div class="flash flash-error">Vous devez avoir au moins un bien et un locataire.
        <?= !$properties ? '<a href="'.url('/biens/new').'">Créer un bien</a>' : '' ?>
        <?= !$tenants ? '<a href="'.url('/locataires/new').'">Créer un locataire</a>' : '' ?>
    </div>
<?php endif; ?>

<form method="post" action="<?= $action ?>">
    <?= csrf_field() ?>
    <fieldset>
        <legend>Bien & locataire</legend>
        <div class="form-grid">
            <div class="field"><label>Bien *</label>
                <select name="property_id" required>
                    <?php foreach ($properties as $p): ?>
                        <option value="<?= $p['id'] ?>" <?= ((int)($l['property_id']??0))===(int)$p['id']?'selected':'' ?>><?= e($p['label']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field"><label>Locataire *</label>
                <select name="tenant_id" required>
                    <?php foreach ($tenants as $t): ?>
                        <option value="<?= $t['id'] ?>" <?= ((int)($l['tenant_id']??0))===(int)$t['id']?'selected':'' ?>><?= e($t['first_name'].' '.$t['last_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Conditions du bail</legend>
        <div class="field">
            <label>Type de location</label>
            <select name="lease_type">
                <option value="vide" <?= ($l['lease_type']??'vide')==='vide'?'selected':'' ?>>Location vide (bail classique)</option>
                <option value="meuble" <?= ($l['lease_type']??'')==='meuble'?'selected':'' ?>>Location meublée (LMNP)</option>
            </select>
            <p class="hint">👉 Choisissez <strong>« Location meublée (LMNP) »</strong> pour générer un bail meublé
            (avec inventaire du mobilier) au lieu d'un bail de location vide.</p>
        </div>
        <div class="form-grid">
            <div class="field"><label>Date de début *</label><input type="date" name="start_date" value="<?= $val('start_date', date('Y-m-d')) ?>" required></div>
            <div class="field"><label>Date de fin</label><input type="date" name="end_date" value="<?= $val('end_date') ?>"></div>
            <div class="field"><label>Loyer hors charges (€)</label><input name="rent_amount" value="<?= $val('rent_amount','0') ?>"></div>
            <div class="field"><label>Provision charges (€)</label><input name="charges_amount" value="<?= $val('charges_amount','0') ?>"></div>
            <div class="field"><label>Dépôt de garantie (€)</label><input name="deposit_amount" value="<?= $val('deposit_amount','0') ?>"></div>
            <div class="field"><label>Jour d'échéance</label><input type="number" min="1" max="28" name="payment_day" value="<?= $val('payment_day','1') ?>"></div>
            <div class="field"><label>Statut</label>
                <select name="status">
                    <option value="active" <?= ($l['status']??'active')==='active'?'selected':'' ?>>Actif</option>
                    <option value="terminated" <?= ($l['status']??'')==='terminated'?'selected':'' ?>>Terminé</option>
                </select>
            </div>
        </div>
        <div class="field"><label>Notes</label><textarea name="notes"><?= $val('notes') ?></textarea></div>
        <div class="field">
            <label>Équipements complémentaires (meublé — un par ligne)</label>
            <textarea name="furniture_extra" placeholder="Ex. Téléviseur&#10;Lave-linge&#10;Meuble TV&#10;Cabine de douche"><?= $val('furniture_extra') ?></textarea>
            <p class="hint">Ajoutés en annexe 1 (inventaire du mobilier) en plus des éléments obligatoires.</p>
        </div>
    </fieldset>

    <fieldset>
        <legend>Garant (caution solidaire)</legend>
        <p class="hint">Facultatif. Renseigné, un <strong>acte de cautionnement solidaire</strong> imprimable / PDF
            devient disponible sur la fiche du bail.</p>
        <div class="form-grid">
            <div class="field"><label>Nom et prénom du garant</label><input name="guarantor_name" value="<?= $val('guarantor_name') ?>"></div>
            <div class="field"><label>Montant maximal garanti (€, optionnel)</label><input name="guarantor_max_amount" value="<?= $val('guarantor_max_amount') ?>" placeholder="ex. 3 ans de loyer"></div>
        </div>
        <div class="field"><label>Adresse du garant</label><textarea name="guarantor_address" rows="2"><?= $val('guarantor_address') ?></textarea></div>
        <div class="form-grid">
            <div class="field"><label>Date de naissance</label><input type="date" name="guarantor_birth_date" value="<?= $val('guarantor_birth_date') ?>"></div>
            <div class="field"><label>Lieu de naissance</label><input name="guarantor_birth_place" value="<?= $val('guarantor_birth_place') ?>"></div>
            <div class="field"><label>Email</label><input type="email" name="guarantor_email" value="<?= $val('guarantor_email') ?>"></div>
            <div class="field"><label>Téléphone</label><input name="guarantor_phone" value="<?= $val('guarantor_phone') ?>"></div>
        </div>
        <div class="field"><label>Durée de l'engagement</label>
            <select name="guarantor_duration">
                <option value="indeterminee" <?= ($l['guarantor_duration'] ?? 'indeterminee')==='indeterminee'?'selected':'' ?>>Durée indéterminée (résiliable par le garant)</option>
                <option value="determinee" <?= ($l['guarantor_duration'] ?? '')==='determinee'?'selected':'' ?>>Durée déterminée (durée du bail initial + renouvellements)</option>
            </select>
        </div>
    </fieldset>

    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Enregistrer' : 'Créer le bail' ?></button>
</form>

<script>
(function () {
    var startInput = document.querySelector('input[name="start_date"]');
    var endInput = document.querySelector('input[name="end_date"]');
    var typeSelect = document.querySelector('select[name="lease_type"]');
    var rentInput = document.querySelector('input[name="rent_amount"]');
    var depositInput = document.querySelector('input[name="deposit_amount"]');

    // Date de fin = date de début + 1 an - 1 jour (ne touche pas une date déjà saisie).
    startInput.addEventListener('change', function () {
        if (endInput.value || !startInput.value) return;
        var d = new Date(startInput.value + 'T00:00:00');
        d.setFullYear(d.getFullYear() + 1);
        d.setDate(d.getDate() - 1);
        endInput.value = d.toISOString().slice(0, 10);
    });

    // Dépôt de garantie suggéré : 2 mois hors charges en meublé, 1 mois en location vide.
    function suggestDeposit() {
        var rent = parseFloat(rentInput.value);
        if (!rent || (depositInput.value && parseFloat(depositInput.value) !== 0)) return;
        var months = typeSelect.value === 'meuble' ? 2 : 1;
        depositInput.value = (rent * months).toFixed(2);
    }
    rentInput.addEventListener('change', suggestDeposit);
    typeSelect.addEventListener('change', suggestDeposit);
})();
</script>
