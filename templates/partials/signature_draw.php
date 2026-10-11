<?php /** @var string $formId  formulaire auquel la signature est jointe */ /** @var ?string $savedSignature  signature enregistrée du bailleur */
// Zone de signature manuscrite (souris ou doigt) : remplit le champ « signature » du formulaire à l'envoi.
$savedSignature = $savedSignature ?? null;
?>
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
    document.getElementById(<?= json_encode($formId) ?>).addEventListener('submit', function (e) {
        if (usingSaved() || (e.submitter && e.submitter.name === 'save_only')) return;
        if (empty) { e.preventDefault(); alert('Dessinez votre signature avant de signer.'); return; }
        document.getElementById('sig-data').value = canvas.toDataURL('image/png');
    });
    if (zone.style.display !== 'none') resize();
})();
</script>
