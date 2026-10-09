<?php /** @var string $step */ /** @var string $email */ ?>
<div class="card portal-login">
<?php if ($step === 'email'): ?>
    <h1>Connexion</h1>
    <p class="muted">Saisissez l'adresse email communiquée à votre bailleur : vous recevrez un code de connexion. Aucun mot de passe n'est nécessaire.</p>
    <form method="post" action="<?= url('/locataire') ?>">
        <?= csrf_field() ?>
        <div class="field"><label>Adresse email</label><input type="email" name="email" value="<?= e($email) ?>" required autofocus autocomplete="email"></div>
        <button class="btn btn-primary" style="width:100%">Recevoir mon code</button>
    </form>
<?php else: ?>
    <h1>Code de connexion</h1>
    <p class="muted">Un code à 6 chiffres a été envoyé à <strong><?= e($email) ?></strong> (pensez à vérifier les courriers indésirables). Il est valable <?= TenantPortal::CODE_MINUTES ?> minutes.</p>
    <form method="post" action="<?= url('/locataire/code') ?>">
        <?= csrf_field() ?>
        <div class="field"><label>Code reçu</label>
            <input name="code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9 ]{6,7}" maxlength="7" required autofocus
                   class="portal-code" placeholder="123456"></div>
        <button class="btn btn-primary" style="width:100%">Valider</button>
    </form>
    <p class="small mt"><a href="<?= url('/locataire') ?>">← Changer d'adresse ou renvoyer un code</a></p>
<?php endif; ?>
</div>
