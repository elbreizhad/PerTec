<?php /** @var string|null $signature  image PNG (data URI) déjà enregistrée */ /** @var string $back  page de retour */
$hasSig = !empty($signature);
?>
<div class="signature-pad no-print" style="font-family:sans-serif;margin-top:1.5rem;padding:1rem;border:1px dashed #9ca3af;border-radius:10px;background:#f9fafb">
    <strong>✍️ Signature du bailleur</strong>
    <p style="margin:.3rem 0 .6rem;font-size:.85rem;color:#4b5563">
        <?= $hasSig
            ? 'Votre signature est enregistrée et ajoutée automatiquement sur toutes les quittances. Dessinez ci-dessous pour la remplacer.'
            : 'Dessinez votre signature avec la souris (ou le doigt) : elle sera ajoutée automatiquement sur toutes les quittances.' ?>
    </p>
    <canvas id="sig-canvas" style="width:100%;max-width:480px;height:160px;background:#fff;border:1px solid #d1d5db;border-radius:8px;touch-action:none;cursor:crosshair;display:block"></canvas>
    <form method="post" action="<?= url('/signature') ?>" id="sig-form" style="margin-top:.6rem;display:flex;gap:.5rem;flex-wrap:wrap">
        <?= csrf_field() ?>
        <input type="hidden" name="back" value="<?= e($back) ?>">
        <input type="hidden" name="signature" id="sig-data">
        <button type="button" class="btn" id="sig-clear">Effacer</button>
        <button type="submit" class="btn btn-primary" name="action" value="save">Enregistrer la signature</button>
        <?php if ($hasSig): ?>
            <button type="submit" class="btn btn-danger" name="action" value="delete" onclick="return confirm('Supprimer la signature enregistrée ?')">Supprimer la signature</button>
        <?php endif; ?>
    </form>
</div>
<script>
(function () {
    var canvas = document.getElementById('sig-canvas'), ctx = canvas.getContext('2d');
    var drawing = false, empty = true, last = null;
    function resize() {
        var ratio = Math.min(window.devicePixelRatio || 1, 2), w = canvas.clientWidth, h = canvas.clientHeight;
        canvas.width = w * ratio; canvas.height = h * ratio;
        ctx.setTransform(ratio, 0, 0, ratio, 0, 0);
        ctx.lineWidth = 2.2; ctx.lineCap = 'round'; ctx.lineJoin = 'round'; ctx.strokeStyle = '#0b1f4d';
        empty = true;
    }
    function pos(e) { var r = canvas.getBoundingClientRect(); return { x: e.clientX - r.left, y: e.clientY - r.top }; }
    canvas.addEventListener('pointerdown', function (e) {
        drawing = true; last = pos(e); canvas.setPointerCapture(e.pointerId);
        ctx.beginPath(); ctx.arc(last.x, last.y, ctx.lineWidth / 2, 0, Math.PI * 2); ctx.fillStyle = ctx.strokeStyle; ctx.fill();
        empty = false;
    });
    canvas.addEventListener('pointermove', function (e) {
        if (!drawing) return;
        var p = pos(e);
        ctx.beginPath(); ctx.moveTo(last.x, last.y); ctx.lineTo(p.x, p.y); ctx.stroke();
        last = p;
    });
    ['pointerup', 'pointercancel', 'pointerleave'].forEach(function (ev) {
        canvas.addEventListener(ev, function () { drawing = false; });
    });
    document.getElementById('sig-clear').addEventListener('click', function () {
        ctx.clearRect(0, 0, canvas.width, canvas.height); empty = true;
    });
    document.getElementById('sig-form').addEventListener('submit', function (e) {
        var action = e.submitter ? e.submitter.value : 'save';
        if (action === 'delete') return;
        if (empty) { e.preventDefault(); alert('Dessinez votre signature avant de l\'enregistrer.'); return; }
        document.getElementById('sig-data').value = canvas.toDataURL('image/png');
    });
    resize();
})();
</script>
