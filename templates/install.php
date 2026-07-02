<?php /** @var array $errors */ /** @var array $old */ ?>
<h1 class="center">Installation de PerTec</h1>
<p class="muted center small">Renseignez la base de données et créez votre compte.</p>

<?php foreach ($errors as $err): ?>
    <div class="flash flash-error"><?= e($err) ?></div>
<?php endforeach; ?>

<form method="post" action="<?= url('/install') ?>">
    <fieldset>
        <legend>Base de données MySQL / MariaDB</legend>
        <div class="form-grid">
            <div class="field"><label>Hôte</label><input name="db_host" value="<?= e($old['db_host'] ?? '127.0.0.1') ?>"></div>
            <div class="field"><label>Port</label><input name="db_port" value="<?= e($old['db_port'] ?? '3306') ?>"></div>
        </div>
        <div class="field"><label>Nom de la base</label><input name="db_name" value="<?= e($old['db_name'] ?? 'pertec') ?>" required></div>
        <div class="form-grid">
            <div class="field"><label>Utilisateur</label><input name="db_user" value="<?= e($old['db_user'] ?? 'root') ?>"></div>
            <div class="field"><label>Mot de passe</label><input type="password" name="db_pass" value=""></div>
        </div>
        <p class="hint">La base sera créée si elle n'existe pas.</p>
    </fieldset>

    <fieldset>
        <legend>Compte administrateur</legend>
        <div class="form-grid">
            <div class="field"><label>Identifiant</label><input name="admin_user" value="<?= e($old['admin_user'] ?? 'admin') ?>" required></div>
            <div class="field"><label>Mot de passe (min. 6)</label><input type="password" name="admin_pass" required></div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Options</legend>
        <div class="field">
            <label>Sous-dossier (base_url)</label>
            <input name="base_url" value="<?= e($old['base_url'] ?? '') ?>" placeholder="laisser vide si à la racine, sinon /pertec">
            <p class="hint">Si le site est accessible via https://domaine.fr/pertec, saisissez <code>/pertec</code>.</p>
        </div>
    </fieldset>

    <button type="submit" class="btn btn-primary" style="width:100%">Installer</button>
</form>
