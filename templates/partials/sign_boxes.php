<?php /** @var array $lease */ /** @var array $settings */ /** @var bool $forHash */ /** @var string $caption */
// Cases de signature bailleur / locataire. Affiche les signatures électroniques
// valables pour cette version du contrat ; sinon, ligne à signer à la main.
$sigs = empty($forHash) ? LeaseSignature::valid($lease, $settings) : [];
?>
<div class="doc-sign">
<?php foreach (LeaseSignature::ROLES as $role => $label): $sg = $sigs[$role] ?? null; ?>
    <div class="sign-box">
        <p><?= $label ?></p>
        <?php if ($sg): ?>
            <img class="signature-img" src="<?= e($sg['image']) ?>" alt="Signature">
            <div class="line small">Lu et approuvé — signé électroniquement par <?= e($sg['signer_name']) ?>
                le <?= e(date('d/m/Y à H:i', strtotime($sg['signed_at']))) ?></div>
        <?php else: ?>
            <div class="line"><?= e($caption) ?></div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>
