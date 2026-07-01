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
        <div class="form-grid">
            <div class="field"><label>Type</label>
                <select name="lease_type">
                    <option value="vide" <?= ($l['lease_type']??'vide')==='vide'?'selected':'' ?>>Location vide</option>
                    <option value="meuble" <?= ($l['lease_type']??'')==='meuble'?'selected':'' ?>>Location meublée</option>
                </select>
            </div>
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
    </fieldset>

    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Enregistrer' : 'Créer le bail' ?></button>
</form>
