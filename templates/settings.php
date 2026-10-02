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
            identifiant = adresse email complète.<br>
            Avec Gmail : serveur <code>smtp.gmail.com</code>, port 465 en SSL, identifiant = votre adresse Gmail,
            mot de passe = un <strong>mot de passe d'application</strong> de 16 caractères (<a href="https://myaccount.google.com/apppasswords" target="_blank" rel="noopener">à créer ici</a>, la validation en 2 étapes doit être activée), pas votre mot de passe Gmail.<br>
            Sans serveur SMTP, la fonction mail() du serveur est utilisée.</p>
        <div class="form-grid">
            <div class="field"><label>Adresse d'expédition</label><input type="email" name="mail_from" value="<?= $v('mail_from') ?>" placeholder="<?= $v('landlord_email') ?: 'contact@votre-domaine.fr' ?>"></div>
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

<?php
$tplSubject = QuittanceMail::subjectTemplate($s);
$tplBody    = QuittanceMail::bodyTemplate($s);
$sample     = QuittanceMail::sampleVars($s);
$fromName   = trim((string) ($s['mail_from_name'] ?? '')) ?: trim((string) ($s['landlord_name'] ?? ''));
$fromAddr   = trim((string) ($s['mail_from'] ?? '')) ?: trim((string) ($s['landlord_email'] ?? ''));
?>
<form method="post" action="<?= url('/parametres/modele-email') ?>" class="mt" id="modele-email">
    <?= csrf_field() ?>
    <fieldset>
        <legend>Email envoyé au locataire</legend>
        <p class="hint">C'est exactement ce que reçoit le locataire avec sa quittance en PDF. Vous pourrez encore retoucher le texte au moment de chaque envoi.</p>
        <div class="form-grid">
            <div class="field"><label>Nom de l'expéditeur (vu par le locataire)</label>
                <input name="mail_from_name" id="tpl-from" value="<?= $v('mail_from_name') ?>" placeholder="<?= $v('landlord_name') ?: 'ex. Jean Dupont' ?>">
                <span class="hint">Vide = votre nom (« <?= $v('landlord_name') ?: 'Coordonnées du bailleur' ?> »).</span>
            </div>
        </div>
        <div class="field"><label>Objet</label>
            <input name="mail_quittance_subject" id="tpl-subject" value="<?= e($tplSubject) ?>">
        </div>
        <div class="field"><label>Message</label>
            <textarea name="mail_quittance_body" id="tpl-body" rows="9"><?= e($tplBody) ?></textarea>
        </div>
        <p class="hint">Champs remplis automatiquement (cliquez pour les insérer dans le message) :</p>
        <p class="actions" style="flex-wrap:wrap">
            <?php foreach (QuittanceMail::tags() as $tag => $desc): ?>
                <button type="button" class="btn btn-sm tpl-tag" data-tag="<?= e($tag) ?>" title="<?= e($desc) ?>"><?= e($tag) ?> <span class="muted small"><?= e($desc) ?></span></button>
            <?php endforeach; ?>
        </p>
        <div class="field"><label style="font-weight:400"><input type="checkbox" name="mail_quittance_copy" value="1" style="width:auto;display:inline;margin-right:.4rem" <?= ($s['mail_quittance_copy'] ?? '1') === '1' ? 'checked' : '' ?>>
            M'envoyer une copie de chaque email (à <?= e(QuittanceMail::copyAddress($s) ?: 'votre email') ?>), pour garder une trace de ce qui a été envoyé</label></div>

        <h3 style="margin-top:1rem">Aperçu (exemple avec une locataire fictive)</h3>
        <div class="card" style="background:#f9fafb;border:1px solid #e5e7eb;font-family:sans-serif">
            <div class="small muted">De : <strong id="pv-from"><?= e($fromName) ?></strong> &lt;<?= e($fromAddr ?: 'adresse d\'expédition') ?>&gt;</div>
            <div class="small muted">À : marie.martin@exemple.fr</div>
            <div style="margin:.4rem 0"><strong id="pv-subject"></strong></div>
            <div id="pv-body" style="white-space:pre-wrap;border-top:1px solid #e5e7eb;padding-top:.5rem"></div>
            <div class="small muted" style="margin-top:.6rem">📎 <?= e(QuittanceMail::attachmentName(['period_month' => (int) date('n'), 'period_year' => date('Y')])) ?></div>
        </div>
    </fieldset>
    <button type="submit" class="btn btn-primary" name="action" value="save">Enregistrer le modèle</button>
    <button type="submit" class="btn" name="action" value="reset" onclick="return confirm('Remettre le modèle par défaut ?')">Remettre par défaut</button>
</form>
<script>
(function () {
    var vars = <?= json_encode($sample, JSON_UNESCAPED_UNICODE) ?>, defName = <?= json_encode($s['landlord_name'] ?? '', JSON_UNESCAPED_UNICODE) ?>;
    var subj = document.getElementById('tpl-subject'), body = document.getElementById('tpl-body'), from = document.getElementById('tpl-from');
    function fill(t) { return Object.keys(vars).reduce(function (acc, k) { return acc.split(k).join(vars[k]); }, t); }
    function update() {
        document.getElementById('pv-subject').textContent = fill(subj.value);
        document.getElementById('pv-body').textContent = fill(body.value);
        document.getElementById('pv-from').textContent = from.value.trim() || defName;
    }
    [subj, body, from].forEach(function (el) { el.addEventListener('input', update); });
    document.querySelectorAll('.tpl-tag').forEach(function (b) {
        b.addEventListener('click', function () {
            var t = b.dataset.tag, p = body.selectionStart || body.value.length;
            body.value = body.value.slice(0, p) + t + body.value.slice(body.selectionEnd || p);
            body.focus(); body.selectionStart = body.selectionEnd = p + t.length; update();
        });
    });
    update();
})();
</script>

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
