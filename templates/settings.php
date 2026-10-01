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
    <fieldset>
        <legend>Indice de référence des loyers (IRL)</legend>
        <p class="hint">Indice retenu pour la clause de révision des baux (voir la dernière valeur publiée sur insee.fr).</p>
        <div class="form-grid">
            <div class="field"><label>Trimestre</label>
                <select name="irl_quarter">
                    <?php foreach ([1, 2, 3, 4] as $q): ?>
                        <option value="<?= $q ?>" <?= ($s['irl_quarter'] ?? '') == $q ? 'selected' : '' ?>><?= $q ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field"><label>Année</label><input name="irl_year" value="<?= $v('irl_year') ?>" placeholder="<?= date('Y') ?>"></div>
        </div>
    </fieldset>
    <button type="submit" class="btn btn-primary">Enregistrer</button>
</form>

<?php if (!empty($s['landlord_signature'])): ?>
    <div class="card mt"><strong>Signature actuelle</strong><br>
        <img src="<?= e($s['landlord_signature']) ?>" alt="Signature" style="max-height:80px;margin-top:.4rem">
    </div>
<?php endif; ?>
<?= render_template('partials/signature_pad', ['signature' => $s['landlord_signature'] ?? null, 'back' => '/parametres']) ?>

<form method="post" action="<?= url('/parametres/email') ?>" class="mt">
    <?= csrf_field() ?>
    <fieldset>
        <legend>Envoi des emails (quittances)</legend>
        <p class="hint">Réglages enregistrés en base de données : ils ne sont jamais écrasés par un déploiement.
            Chez PlanetHoster : serveur SMTP de votre hébergement (ex. <code>mail.votre-domaine.fr</code>), port 465 en SSL,
            identifiant = adresse email complète. Sans serveur SMTP, la fonction mail() du serveur est utilisée.</p>
        <div class="form-grid">
            <div class="field"><label>Adresse d'expédition</label><input type="email" name="mail_from" value="<?= $v('mail_from') ?>" placeholder="<?= $v('landlord_email') ?: 'contact@votre-domaine.fr' ?>"></div>
            <div class="field"><label>Nom affiché</label><input name="mail_from_name" value="<?= $v('mail_from_name') ?>" placeholder="<?= $v('landlord_name') ?>"></div>
            <div class="field"><label>Serveur SMTP</label><input name="smtp_host" value="<?= $v('smtp_host') ?>" placeholder="mail.votre-domaine.fr"></div>
            <div class="field"><label>Sécurité</label>
                <select name="smtp_secure">
                    <?php foreach (['ssl' => 'SSL (port 465)', 'tls' => 'STARTTLS (port 587)', 'none' => 'Aucune (port 25)'] as $k => $lbl): ?>
                        <option value="<?= $k ?>" <?= ($s['smtp_secure'] ?? 'ssl') === $k ? 'selected' : '' ?>><?= $lbl ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field"><label>Port</label><input name="smtp_port" value="<?= $v('smtp_port') ?>" placeholder="465"></div>
            <div class="field"><label>Identifiant SMTP</label><input name="smtp_user" value="<?= $v('smtp_user') ?>" autocomplete="off"></div>
            <div class="field"><label>Mot de passe SMTP</label><input type="password" name="smtp_pass" autocomplete="new-password" placeholder="<?= !empty($s['smtp_pass']) ? '•••••• (inchangé si vide)' : '' ?>"></div>
        </div>
    </fieldset>
    <button type="submit" class="btn btn-primary" name="action" value="save">Enregistrer</button>
    <button type="submit" class="btn" name="action" value="test">Enregistrer et envoyer un email de test</button>
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
