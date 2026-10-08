<?php
/** @var array $lease */ /** @var string $role */ /** @var array $settings */ /** @var string $contractUrl */
/** @var string $action */ /** @var string $defaultName */ /** @var ?string $savedSignature */ /** @var bool $public */
$l = $lease;
$adresse = trim(($l['address'] ?? '') . ', ' . ($l['postal_code'] ?? '') . ' ' . ($l['city'] ?? ''), ', ');
$already = $public ? (($signatures ?? [])['locataire'] ?? null) : null;
$complete = $public && count($signatures ?? []) === count(LeaseSignature::ROLES);
if ($public): ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signature du bail</title>
    <link rel="stylesheet" href="<?= url('/assets/style.css') ?>">
</head>
<body style="background:#f3f4f6">
<main style="max-width:900px;margin:0 auto;padding:1rem">
    <?php foreach ((flash() ?: []) as $f): ?>
        <div class="flash flash-<?= e($f['type']) ?>"><?= e($f['msg']) ?></div>
    <?php endforeach; ?>
<?php endif; ?>

<div class="page-head">
    <div>
        <h1>Signature du bail — <?= e(LeaseSignature::ROLES[$role]) ?></h1>
        <p class="muted">Logement : <?= e($adresse ?: $l['property_label']) ?> · Bailleur : <?= e($settings['landlord_name'] ?? '') ?>
            · Locataire : <?= e($l['first_name'] . ' ' . $l['last_name']) ?></p>
    </div>
    <?php if (!$public): ?><a href="<?= url('/baux/' . (int) $l['id']) ?>" class="btn">Retour au bail</a><?php endif; ?>
</div>

<div class="card">
    <h3>1. Lire le contrat</h3>
    <p class="small muted">Lisez l'intégralité du contrat et de ses annexes avant de signer.
        <a href="<?= e($public ? $pdfUrl : url('/contrat/' . (int) $l['id'] . '/pdf')) ?>" target="_blank">Ouvrir en PDF</a></p>
    <iframe src="<?= e($contractUrl) ?>" title="Contrat de location" style="width:100%;height:60vh;border:1px solid #d1d5db;border-radius:8px;background:#fff"></iframe>
</div>

<?php if ($complete): ?>
    <div class="card"><h3>✔ Bail signé par les deux parties</h3>
        <p><a class="btn btn-primary" href="<?= e($pdfUrl) ?>" target="_blank">Télécharger le bail signé (PDF)</a></p></div>
<?php elseif ($already): ?>
    <div class="card"><h3>✔ Vous avez signé le <?= e(date('d/m/Y à H:i', strtotime($already['signed_at']))) ?></h3>
        <p class="muted">Le bail sera complet après la signature du bailleur. Vous pourrez alors télécharger le contrat signé avec ce même lien.</p></div>
<?php else: ?>
<form method="post" action="<?= e($action) ?>" class="card" id="sign-form">
    <?= csrf_field() ?>
    <h3>2. Signer</h3>
    <div class="form-grid">
        <div class="field"><label>Nom et prénom du signataire</label>
            <input name="signer_name" value="<?= e($defaultName) ?>" required></div>
    </div>

    <?php if ($savedSignature): ?>
        <div class="field">
            <label style="font-weight:400"><input type="radio" name="use_saved" value="1" checked style="width:auto"> Utiliser ma signature enregistrée</label>
            <img src="<?= e($savedSignature) ?>" alt="Signature enregistrée" style="max-height:70px;margin:.3rem 0 .6rem 1.6rem;display:block">
            <label style="font-weight:400"><input type="radio" name="use_saved" value="" style="width:auto"> Dessiner une nouvelle signature</label>
        </div>
    <?php endif; ?>

    <div id="draw-zone" <?= $savedSignature ? 'style="display:none"' : '' ?>>
        <p class="small muted">Dessinez votre signature avec la souris ou le doigt :</p>
        <canvas id="sig-canvas" style="width:100%;max-width:520px;height:170px;background:#fff;border:1px solid #d1d5db;border-radius:8px;touch-action:none;cursor:crosshair;display:block"></canvas>
        <button type="button" class="btn btn-sm mt" id="sig-clear">Effacer</button>
    </div>
    <input type="hidden" name="signature" id="sig-data">

    <div class="field mt">
        <label style="font-weight:400"><input type="checkbox" name="approve" value="1" required style="width:auto">
            <strong>Lu et approuvé.</strong> J'ai lu le contrat de location et ses annexes, et je le signe électroniquement.</label>
    </div>
    <p class="small muted">Signature électronique (art. 1366 et 1367 du Code civil). Sont enregistrés comme preuve : la date et l'heure,
        l'adresse IP, le navigateur utilisé et l'empreinte numérique du contrat signé. Toute modification ultérieure du bail
        rend la signature caduque.</p>
    <button class="btn btn-primary">Signer le bail</button>
</form>
<script>
(function () {
    var canvas = document.getElementById('sig-canvas'), ctx = canvas.getContext('2d');
    var drawing = false, empty = true, last = null;
    function resize() {
        var ratio = Math.min(window.devicePixelRatio || 1, 2);
        canvas.width = canvas.clientWidth * ratio; canvas.height = canvas.clientHeight * ratio;
        ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
        ctx.lineWidth = 2.2; ctx.lineCap = 'round'; ctx.lineJoin = 'round'; ctx.strokeStyle = '#0b1f4d'; empty = true;
    }
    function pos(e) { var r = canvas.getBoundingClientRect(); return { x: e.clientX - r.left, y: e.clientY - r.top }; }
    canvas.addEventListener('pointerdown', function (e) {
        drawing = true; last = pos(e); canvas.setPointerCapture(e.pointerId);
        ctx.beginPath(); ctx.arc(last.x, last.y, 1.1, 0, Math.PI * 2); ctx.fillStyle = ctx.strokeStyle; ctx.fill(); empty = false;
    });
    canvas.addEventListener('pointermove', function (e) {
        if (!drawing) return; var p = pos(e);
        ctx.beginPath(); ctx.moveTo(last.x, last.y); ctx.lineTo(p.x, p.y); ctx.stroke(); last = p;
    });
    ['pointerup', 'pointercancel', 'pointerleave'].forEach(function (ev) { canvas.addEventListener(ev, function () { drawing = false; }); });
    document.getElementById('sig-clear').addEventListener('click', function () { ctx.clearRect(0, 0, canvas.width, canvas.height); empty = true; });

    var zone = document.getElementById('draw-zone');
    function usingSaved() { var r = document.querySelector('input[name="use_saved"]:checked'); return r && r.value === '1'; }
    document.querySelectorAll('input[name="use_saved"]').forEach(function (r) {
        r.addEventListener('change', function () { zone.style.display = usingSaved() ? 'none' : ''; if (!usingSaved()) resize(); });
    });
    document.getElementById('sign-form').addEventListener('submit', function (e) {
        if (usingSaved()) return;
        if (empty) { e.preventDefault(); alert('Dessinez votre signature avant de signer.'); return; }
        document.getElementById('sig-data').value = canvas.toDataURL('image/png');
    });
    if (zone.style.display !== 'none') resize();
})();
</script>
<?php endif; ?>

<?php if ($public): ?>
</main>
</body>
</html>
<?php endif; ?>
