<?php /** @var array $lease */ /** @var array $settings */ /** @var bool $forHash */ /** @var string $caption */ /** @var bool $proof */
// Cases de signature bailleur / locataire. Affiche les signatures électroniques
// valables pour cette version du contrat (avec l'adresse IP du signataire) ;
// sinon, ligne à signer à la main. $proof : ajoute le certificat de signature.
$sigs = empty($forHash) ? LeaseSignature::valid($lease, $settings) : [];
?>
<div class="doc-sign">
<?php foreach (LeaseSignature::ROLES as $role => $label): $sg = $sigs[$role] ?? null; ?>
    <div class="sign-box">
        <p><?= $label ?></p>
        <?php if ($sg): ?>
            <img class="signature-img" src="<?= e($sg['image']) ?>" alt="Signature">
            <div class="line small">Lu et approuvé — signé électroniquement par <?= e($sg['signer_name']) ?>
                le <?= e(date('d/m/Y à H:i', strtotime($sg['signed_at']))) ?><br>
                Adresse IP : <?= e($sg['ip'] ?: 'non disponible') ?></div>
        <?php else: ?>
            <div class="line"><?= e($caption) ?></div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>
<?php if (!empty($proof) && $sigs): ?>
<div class="keep sign-proof">
    <h3>Certificat de signature électronique</h3>
    <p class="small">Signature électronique simple (art. 1366 et 1367 du Code civil). Éléments de preuve enregistrés au moment de chaque signature :</p>
    <table class="doc-amounts small">
        <thead><tr><th>Signataire</th><th>Date et heure</th><th>Adresse IP</th><th>Navigateur / appareil</th></tr></thead>
        <tbody>
        <?php foreach (LeaseSignature::ROLES as $role => $label): $sg = $sigs[$role] ?? null; if (!$sg) continue; ?>
            <tr>
                <td><?= e($sg['signer_name']) ?><br><span class="small"><?= $label ?></span></td>
                <td><?= e(date('d/m/Y à H:i:s', strtotime($sg['signed_at']))) ?></td>
                <td><?= e($sg['ip'] ?: '—') ?></td>
                <td><?= e(LeaseSignature::deviceLabel((string) $sg['user_agent'])) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p class="small">Empreinte numérique (SHA-256) du contrat signé :<br><span style="font-family:monospace;word-break:break-all"><?= e(reset($sigs)['doc_hash']) ?></span></p>
</div>
<?php endif; ?>
