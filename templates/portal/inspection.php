<?php /** @var array $insp */ /** @var array $lease */ /** @var array $settings */ /** @var int $preview */ /** @var callable $link */
$l = $lease;
$d = $insp['data'];
$id = (int) $insp['id'];
$sortie = $insp['kind'] === 'sortie';
$sigs = Inspection::valid($insp, $l, $settings);
$complete = count($sigs) === count(Inspection::ROLES);
$mine = $sigs['locataire'] ?? null;
?>
<?php if ($preview): ?>
    <div class="flash" style="background:#eff6ff;color:#1e3a8a;border:1px solid #bfdbfe">
        👁 <strong>Aperçu administrateur</strong> — le locataire voit cette page ; le formulaire de signature est désactivé dans l'aperçu.
        <a href="<?= url('/edl/' . $id) ?>">← Retour à l'état des lieux</a></div>
<?php endif; ?>

<p><a href="<?= $link('/locataire/espace') ?>">← Mon espace</a></p>
<div class="page-head">
    <div>
        <h1><?= e(Inspection::title($insp)) ?></h1>
        <p class="muted"><?= e(trim(($l['address'] ?? '') . ', ' . ($l['postal_code'] ?? '') . ' ' . ($l['city'] ?? ''), ', ') ?: $l['property_label']) ?>
            <?= $insp['inspected_at'] ? ' · ' . e(date('d/m/Y à H\hi', strtotime($insp['inspected_at']))) : '' ?></p>
    </div>
    <a class="btn btn-primary" href="<?= $link('/locataire/edl/' . $id . '/pdf') ?>" target="_blank">📄 <?= $complete ? 'Télécharger la version signée (PDF)' : 'Télécharger (PDF)' ?></a>
</div>

<div class="card">
    <p class="small">
        <?php foreach (Inspection::ROLES as $role => $label): $sg = $sigs[$role] ?? null; ?>
            <?= $label ?> : <?= $sg ? '<span class="badge badge-paid">Signé le ' . e(date('d/m/Y', strtotime($sg['signed_at']))) . '</span>' : '<span class="badge badge-pending">Pas encore signé</span>' ?><br>
        <?php endforeach; ?></p>
    <iframe src="<?= $link('/locataire/edl/' . $id . '/document') ?>" title="État des lieux" style="width:100%;height:65vh;border:1px solid #d1d5db;border-radius:8px;background:#fff"></iframe>
</div>

<?php if ($complete): ?>
    <div class="card"><h3>✔ État des lieux signé par les deux parties</h3>
        <p class="muted">Conservez le PDF signé : il fait foi<?= $sortie ? ' pour la restitution de votre dépôt de garantie' : ' et sera comparé à l\'état des lieux de sortie' ?>.</p></div>
<?php elseif ($mine): ?>
    <div class="card"><h3>✔ Vous avez signé le <?= e(date('d/m/Y à H:i', strtotime($mine['signed_at']))) ?></h3>
        <p class="muted">L'état des lieux sera complet après la signature du bailleur.</p></div>
<?php else: ?>
<form method="post" action="<?= url('/locataire/edl/' . $id) ?>" class="card" id="sign-form">
    <?= csrf_field() ?>
    <fieldset <?= $preview ? 'disabled' : '' ?> style="border:none;padding:0;margin:0">
    <h3>Compléter et signer</h3>
    <?php if ($sortie): ?>
        <p class="small muted">Pour la restitution de votre dépôt de garantie :</p>
        <div class="form-grid">
            <div class="field"><label>Votre nouvelle adresse</label><textarea name="new_address" rows="2"><?= e($d['tenant']['new_address']) ?></textarea></div>
            <div class="field"><label>IBAN du compte de restitution</label><input name="iban" value="<?= e($d['tenant']['iban']) ?>" placeholder="FR76 …" autocomplete="off"></div>
        </div>
    <?php endif; ?>
    <div class="field"><label>Vos observations (facultatif)</label>
        <textarea name="tenant_observations" rows="3" placeholder="Si vous n'êtes pas d'accord avec un point, indiquez-le ici avant de signer."><?= e($d['tenant']['observations']) ?></textarea></div>
    <p><button class="btn" name="save_only" value="1" formnovalidate>Enregistrer sans signer</button></p>

    <div class="form-grid">
        <div class="field"><label>Nom et prénom du signataire</label><input name="signer_name" value="<?= e(trim($l['first_name'] . ' ' . $l['last_name'])) ?>" required></div>
    </div>
    <?php if (!$preview) view('partials/signature_draw', ['formId' => 'sign-form', 'savedSignature' => null], null); ?>
    <div class="field mt">
        <label style="font-weight:400"><input type="checkbox" name="approve" value="1" required style="width:auto">
            <strong>Lu et approuvé.</strong> J'ai relu l'état des lieux et je le signe électroniquement.</label>
    </div>
    <p class="small muted">Signature électronique (art. 1366 et 1367 du Code civil) : la date, l'heure, l'adresse IP, le navigateur et
        l'empreinte numérique du document sont enregistrés comme preuve.</p>
    <button class="btn btn-primary">Signer l'état des lieux</button>
    </fieldset>
</form>
<?php endif; ?>
