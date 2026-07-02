<form method="post" action="<?= url('/login') ?>">
    <?= csrf_field() ?>
    <div class="field">
        <label for="username">Identifiant</label>
        <input type="text" id="username" name="username" autofocus required>
    </div>
    <div class="field">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary" style="width:100%">Se connecter</button>
</form>
