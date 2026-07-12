<?php /** @var array $payment */ /** @var array $lease */ /** @var array $settings */
$s = $settings;
$total = (float)$payment['amount_rent'] + (float)$payment['amount_charges'];
$mois = moisFr((int)$payment['period_month']) . ' ' . $payment['period_year'];
$bienAdresse = trim(($lease['address'] ?: '') . ', ' . ($lease['postal_code'] ?: '') . ' ' . ($lease['city'] ?: ''), ', ');
$ville = $s['signature_city'] ?? ($s['landlord_city'] ?? '');
?>
<h1>QUITTANCE DE LOYER</h1>
<p class="doc-sub">Période : <strong><?= e(ucfirst($mois)) ?></strong>
    <?php if ($payment['receipt_number']): ?> — Quittance n° <?= e($payment['receipt_number']) ?><?php endif; ?></p>

<div class="doc-parties">
    <div class="party">
        <h3>Bailleur</h3>
        <p>
            <?= e($s['landlord_name'] ?? 'Nom du bailleur') ?><br>
            <?= nl2br(e($s['landlord_address'] ?? '')) ?><br>
            <?= e($s['landlord_city'] ?? '') ?><br>
            <?php if (!empty($s['landlord_phone'])): ?><?= e($s['landlord_phone']) ?><br><?php endif; ?>
            <?php if (!empty($s['landlord_email'])): ?><?= e($s['landlord_email']) ?><?php endif; ?>
        </p>
    </div>
    <div class="party">
        <h3>Locataire</h3>
        <p>
            <?= e($lease['first_name'].' '.$lease['last_name']) ?><br>
            Logement situé :<br>
            <?= e($bienAdresse ?: $lease['property_label']) ?>
        </p>
    </div>
</div>

<p class="article">
    Je soussigné(e) <strong><?= e($s['landlord_name'] ?? '—') ?></strong>, bailleur du logement désigné ci-dessus,
    reconnais avoir reçu de <strong><?= e($lease['first_name'].' '.$lease['last_name']) ?></strong>
    la somme de <strong><?= euros($total) ?></strong>
    au titre du loyer et des charges pour la période de <strong><?= e(ucfirst($mois)) ?></strong>,
    et lui en donne quittance, sous réserve de tous mes droits.
</p>

<table class="doc-amounts">
    <tr><td>Loyer hors charges</td><td class="num"><?= euros($payment['amount_rent']) ?></td></tr>
    <tr><td>Provision pour charges</td><td class="num"><?= euros($payment['amount_charges']) ?></td></tr>
    <tr class="total"><td>Total réglé</td><td class="num"><?= euros($total) ?></td></tr>
</table>
<?php if (!empty($payment['notes'])): ?>
    <p class="small"><em><?= e($payment['notes']) ?> — loyer calculé au prorata des jours d'occupation.</em></p>
<?php endif; ?>

<p class="article">Date du paiement : <strong><?= fdate($payment['paid_date']) ?></strong>
    <?php if ($payment['payment_method']): ?> — Mode de règlement : <?= e($payment['payment_method']) ?><?php endif; ?></p>

<div class="doc-sign">
    <div class="sign-box">
        <p>Fait à <?= e($ville ?: '____________') ?>, le <?= fdate($payment['paid_date'] ?: date('Y-m-d')) ?></p>
        <div class="line">Signature du bailleur</div>
    </div>
</div>

<p class="mention">Cette quittance annule tous les reçus qui auraient pu être établis précédemment en cas de paiement partiel du terme.
Elle atteste du paiement intégral du loyer et des charges pour la période indiquée.</p>
