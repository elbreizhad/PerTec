<?php /** @var array $payment */ /** @var array $lease */ /** @var array $prorata */
$p = $payment;
$y = (int) $p['period_year'];
$m = (int) $p['period_month'];
$daysInMonth = (int) date('t', mktime(0, 0, 0, $m, 1, $y));
$fullRent    = (float) $lease['rent_amount'];
$fullCharges = (float) $lease['charges_amount'];

// Nombre de jours proposé : celui de la note existante, sinon celui déduit des dates du bail.
$days = $daysInMonth;
foreach ([$p['notes'] ?? '', $prorata['note'] ?? ''] as $n) {
    if (preg_match('/(\d+)\s*\/\s*\d+\s*jours/', (string) $n, $mm)) { $days = (int) $mm[1]; break; }
}
// Échéance encore au plein tarif alors que les dates du bail donnent moins de jours : on propose le prorata.
$autoApply = $days < $daysInMonth
    && abs((float) $p['amount_rent'] - $fullRent) < 0.01
    && abs((float) $p['amount_charges'] - $fullCharges) < 0.01;
$fmt = fn($v) => number_format((float) $v, 2, '.', '');
?>
<div class="page-head">
    <h1>Modifier l'échéance — <?= ucfirst(moisFr($m)).' '.$y ?></h1>
    <a href="<?= url('/baux/'.(int)$p['lease_id']) ?>" class="btn">Annuler</a>
</div>

<div class="grid grid-3 mb">
    <div class="stat"><div class="label">Loyer HC du bail</div><div class="value"><?= euros($fullRent) ?></div></div>
    <div class="stat"><div class="label">Charges du bail</div><div class="value"><?= euros($fullCharges) ?></div></div>
    <div class="stat"><div class="label">Jours dans le mois</div><div class="value"><?= $daysInMonth ?></div></div>
</div>

<?php if ($p['status'] === 'paid'): ?>
    <div class="flash flash-error">Cette échéance est déjà marquée payée : le montant encaissé et la quittance seront mis à jour avec le nouveau total.</div>
<?php endif; ?>

<form method="post" action="<?= url('/loyers/'.(int)$p['id'].'/modifier') ?>" class="card">
    <?= csrf_field() ?>
    <fieldset>
        <legend>Prorata au nombre de jours</legend>
        <div class="form-grid">
            <div class="field"><label>Jours d'occupation (sur <?= $daysInMonth ?>)</label>
                <input type="number" id="days" min="0" max="<?= $daysInMonth ?>" value="<?= $days ?>">
                <span class="hint">Saisir le nombre de jours : loyer et charges sont recalculés automatiquement.</span>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend>Montants de l'échéance (modifiables)</legend>
        <div class="form-grid">
            <div class="field"><label>Loyer HC (€)</label>
                <input type="text" inputmode="decimal" name="amount_rent" id="amount_rent" value="<?= $fmt($p['amount_rent']) ?>" required>
            </div>
            <div class="field"><label>Charges (€)</label>
                <input type="text" inputmode="decimal" name="amount_charges" id="amount_charges" value="<?= $fmt($p['amount_charges']) ?>">
            </div>
            <div class="field"><label>Total mensuel (€)</label>
                <input type="text" id="total" readonly>
            </div>
        </div>
        <div class="field"><label>Note (apparaît sur la quittance)</label>
            <input type="text" name="notes" id="notes" value="<?= e($p['notes'] ?? '') ?>" placeholder="ex. Prorata : 12/31 jours (fin de bail)">
        </div>
    </fieldset>

    <button class="btn btn-primary">Enregistrer</button>
</form>

<script>
(function () {
    var dim = <?= $daysInMonth ?>, rent = <?= json_encode($fullRent) ?>, charges = <?= json_encode($fullCharges) ?>;
    var days = document.getElementById('days'),
        r = document.getElementById('amount_rent'),
        c = document.getElementById('amount_charges'),
        t = document.getElementById('total'),
        n = document.getElementById('notes');
    var num = function (v) { return parseFloat(String(v).replace(/\s/g, '').replace(',', '.')) || 0; };
    function total() { t.value = (num(r.value) + num(c.value)).toFixed(2); }
    function apply() {
        var d = Math.max(0, Math.min(dim, parseInt(days.value, 10) || 0));
        r.value = (Math.round(rent * d / dim * 100) / 100).toFixed(2);
        c.value = (Math.round(charges * d / dim * 100) / 100).toFixed(2);
        n.value = d < dim ? 'Prorata : ' + d + '/' + dim + ' jours' : '';
        total();
    }
    days.addEventListener('input', apply);
    r.addEventListener('input', total);
    c.addEventListener('input', total);
    <?= $autoApply ? 'apply();' : 'total();' ?>
})();
</script>
