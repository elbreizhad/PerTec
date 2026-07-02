<?php /** @var array $settings */ /** @var array $user */
$s = $settings;
$v = fn($k) => e((string) ($s[$k] ?? '')); ?>
<h1>Paramètres</h1>

<form method="post" action="<?= url('/parametres') ?>">
    <?= csrf_field() ?>
    <fieldset>
        <legend>Coordonnées du bailleur</legend>
        <p class="hint">Ces informations apparaissent sur les quittances et les contrats.</p>
        <div class="form-grid">
            <div class="field"><label>Nom / Raison sociale</label><input name="landlord_name" value="<?= $v('landlord_name') ?>"></div>
            <div class="field"><label>SIRET (optionnel)</label><input name="landlord_siret" value="<?= $v('landlord_siret') ?>"></div>
        </div>
        <div class="field"><label>Adresse</label><textarea name="landlord_address" rows="2"><?= $v('landlord_address') ?></textarea></div>
        <div class="form-grid">
            <div class="field"><label>Code postal + Ville</label><input name="landlord_city" value="<?= $v('landlord_city') ?>"></div>
            <div class="field"><label>Ville de signature</label><input name="signature_city" value="<?= $v('signature_city') ?>" placeholder="par défaut : votre ville"></div>
            <div class="field"><label>Email</label><input type="email" name="landlord_email" value="<?= $v('landlord_email') ?>"></div>
            <div class="field"><label>Téléphone</label><input name="landlord_phone" value="<?= $v('landlord_phone') ?>"></div>
        </div>
    </fieldset>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
</form>

<form method="post" action="<?= url('/parametres/motdepasse') ?>" class="mt">
    <?= csrf_field() ?>
    <fieldset>
        <legend>Changer le mot de passe</legend>
        <p class="hint">Connecté en tant que <strong><?= e($user['username'] ?? '') ?></strong>.</p>
        <div class="form-grid">
            <div class="field"><label>Mot de passe actuel</label><input type="password" name="current_password" required></div>
            <div class="field"><label>Nouveau mot de passe</label><input type="password" name="new_password" required></div>
        </div>
    </fieldset>
    <button type="submit" class="btn">Modifier le mot de passe</button>
</form>
