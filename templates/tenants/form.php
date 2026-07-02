<?php /** @var array|null $tenant */
$t = $tenant; $isEdit = $t !== null;
$val = fn($k) => e((string) ($t[$k] ?? ''));
$action = $isEdit ? url('/locataires/'.$t['id']) : url('/locataires');
?>
<div class="page-head">
    <h1><?= $isEdit ? 'Modifier le locataire' : 'Nouveau locataire' ?></h1>
    <a href="<?= url('/locataires') ?>" class="btn">Annuler</a>
</div>

<form method="post" action="<?= $action ?>">
    <?= csrf_field() ?>
    <div class="card">
        <div class="form-grid">
            <div class="field"><label>Prénom *</label><input name="first_name" value="<?= $val('first_name') ?>" required></div>
            <div class="field"><label>Nom *</label><input name="last_name" value="<?= $val('last_name') ?>" required></div>
            <div class="field"><label>Email</label><input type="email" name="email" value="<?= $val('email') ?>"></div>
            <div class="field"><label>Téléphone</label><input name="phone" value="<?= $val('phone') ?>"></div>
        </div>
        <div class="field"><label>Notes</label><textarea name="notes"><?= $val('notes') ?></textarea></div>
    </div>
    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Enregistrer' : 'Ajouter' ?></button>
</form>
