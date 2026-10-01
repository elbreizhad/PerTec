<?php /** @var array $payment */ /** @var array $lease */ /** @var array $settings */
$s = $settings;
$mois = ucfirst(moisFr((int) $payment['period_month'])) . ' ' . $payment['period_year'];
$total = (float) $payment['amount_rent'] + (float) $payment['amount_charges'];
$signName = $s['landlord_name'] ?? '';
$subject = "Quittance de loyer — $mois";
$message = "Bonjour " . trim($lease['first_name'] . ' ' . $lease['last_name']) . ",\n\n"
    . "Veuillez trouver ci-joint votre quittance de loyer pour la période de $mois"
    . " (montant réglé : " . euros($total) . ").\n\n"
    . "Cordialement,\n" . $signName;
$field = 'width:100%;box-sizing:border-box;padding:.45rem .6rem;border:1px solid #d1d5db;border-radius:8px;font:inherit;font-weight:400';
?>
<div class="quittance-email no-print" id="email" style="font-family:sans-serif;margin-top:1.5rem;padding:1rem;border:1px dashed #9ca3af;border-radius:10px;background:#f9fafb">
    <strong>📧 Envoyer la quittance par email au locataire</strong>
    <?php if (!empty($payment['emailed_at'])): ?>
        <p style="margin:.3rem 0;font-size:.85rem;color:#047857">✔ Déjà envoyée le <?= e(date('d/m/Y à H:i', strtotime($payment['emailed_at']))) ?> à <?= e($payment['emailed_to']) ?>.</p>
    <?php endif; ?>
    <?php if ($payment['status'] !== 'paid'): ?>
        <p style="margin:.3rem 0;font-size:.85rem;color:#b91c1c">Ce loyer n'est pas encore marqué payé : la quittance ne peut pas être envoyée.</p>
    <?php else: ?>
    <form method="post" action="<?= url('/quittance/'.(int)$payment['id'].'/email') ?>" onsubmit="var b=this.querySelector('button');b.disabled=true;b.textContent='Envoi en cours…'" style="margin-top:.6rem;display:grid;gap:.5rem">
        <?= csrf_field() ?>
        <label style="font-size:.85rem;font-weight:600">Destinataire
            <input type="email" name="to" required value="<?= e($lease['email'] ?? '') ?>" placeholder="email du locataire" style="<?= $field ?>">
        </label>
        <?php if (empty($lease['email'])): ?>
            <span style="font-size:.8rem;color:#b45309">Aucun email enregistré pour ce locataire : saisissez-le ci-dessus (ou ajoutez-le dans sa fiche).</span>
        <?php endif; ?>
        <label style="font-size:.85rem;font-weight:600">Objet
            <input type="text" name="subject" required value="<?= e($subject) ?>" style="<?= $field ?>">
        </label>
        <label style="font-size:.85rem;font-weight:600">Message
            <textarea name="message" rows="7" style="<?= $field ?>"><?= e($message) ?></textarea>
        </label>
        <span style="font-size:.8rem;color:#4b5563">La quittance est jointe en PDF<?= !empty($s['landlord_signature']) ? ', avec votre signature' : '' ?>.</span>
        <div><button class="btn btn-primary">Envoyer la quittance</button></div>
    </form>
    <?php endif; ?>
</div>
