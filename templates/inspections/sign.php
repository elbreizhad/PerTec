<?php /** @var array $insp */ /** @var array $lease */ /** @var string $role */ /** @var array $settings */
$l = $lease;
$id = (int) $insp['id'];
$defaultName = $role === 'bailleur' ? ($settings['landlord_name'] ?? '') : trim($l['first_name'] . ' ' . $l['last_name']);
?>
<div class="page-head">
    <div>
        <h1>Signature — <?= e(Inspection::title($insp)) ?></h1>
        <p class="muted"><?= e(Inspection::ROLES[$role]) ?> · <?= e($l['property_label']) ?> · Locataire : <?= e($l['first_name'] . ' ' . $l['last_name']) ?></p>
    </div>
    <a href="<?= url('/edl/' . $id) ?>" class="btn">Retour</a>
</div>

<div class="card">
    <h3>1. Relire l'état des lieux</h3>
    <p class="small muted"><a href="<?= url('/edl/' . $id . '/document/pdf') ?>" target="_blank">Ouvrir en PDF</a></p>
    <iframe src="<?= url('/edl/' . $id . '/document') ?>" title="État des lieux" style="width:100%;height:60vh;border:1px solid #d1d5db;border-radius:8px;background:#fff"></iframe>
</div>

<form method="post" action="<?= url('/edl/' . $id . '/signer/' . $role) ?>" class="card" id="sign-form">
    <?= csrf_field() ?>
    <h3>2. Signer</h3>
    <?php if ($role === 'locataire'): ?>
        <div class="field"><label>Observations du locataire (facultatif)</label>
            <textarea name="tenant_observations" rows="3" placeholder="Réserves éventuelles sur l'état des lieux"><?= e($insp['data']['tenant']['observations']) ?></textarea></div>
    <?php endif; ?>
    <div class="form-grid">
        <div class="field"><label>Nom et prénom du signataire</label><input name="signer_name" value="<?= e($defaultName) ?>" required></div>
    </div>
    <?php view('partials/signature_draw', ['formId' => 'sign-form', 'savedSignature' => $role === 'bailleur' ? ($settings['landlord_signature'] ?? null) : null], null); ?>
    <div class="field mt">
        <label style="font-weight:400"><input type="checkbox" name="approve" value="1" required style="width:auto">
            <strong>Lu et approuvé.</strong> J'ai relu l'état des lieux et je le signe électroniquement.</label>
    </div>
    <p class="small muted">Signature électronique (art. 1366 et 1367 du Code civil) : la date, l'heure, l'adresse IP, le navigateur et
        l'empreinte numérique du document sont enregistrés comme preuve. Toute modification ultérieure rend la signature caduque.</p>
    <button class="btn btn-primary">Signer l'état des lieux</button>
</form>
