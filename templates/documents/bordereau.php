<?php /** @var array $lease */ /** @var array $settings */ /** @var string[] $items */
$l = $lease; $s = $settings;
$adresse = trim(($l['address'] ?? '') . ', ' . ($l['postal_code'] ?? '') . ' ' . ($l['city'] ?? ''), ', ');
$ville = $s['signature_city'] ?? ($s['landlord_city'] ?? '');
?>
<h1>BORDEREAU DE REMISE DES DOCUMENTS</h1>
<p class="doc-sub">Location du logement situé <?= e($adresse ?: $l['property_label']) ?></p>

<div class="doc-parties">
    <div class="party"><h3>Bailleur</h3><p><?= e($s['landlord_name'] ?? '') ?><br><?= nl2br(e($s['landlord_address'] ?? '')) ?></p></div>
    <div class="party"><h3>Locataire</h3><p><?= e($l['first_name'] . ' ' . $l['last_name']) ?></p></div>
</div>

<p class="article">Le locataire reconnaît avoir reçu du bailleur les documents suivants :</p>
<table class="doc-amounts">
    <thead><tr><th style="width:30px">N°</th><th>Document</th></tr></thead>
    <tbody>
    <?php foreach ($items as $i => $label): ?>
        <tr><td><?= $i + 1 ?></td><td><?= e($label) ?></td></tr>
    <?php endforeach; ?>
    </tbody>
</table>
<p class="small">Documents remis en application de l'article 3-3 de la loi n° 89-462 du 6 juillet 1989
(dossier de diagnostic technique) et de l'arrêté du 29 mai 2015 (notice d'information).</p>

<div class="keep">
<p class="article mt">Fait à <?= e($ville ?: '____________') ?>, le ____ / ____ / ________</p>
<div class="doc-sign">
    <div class="sign-box"><p>Le bailleur</p><div class="line">Signature</div></div>
    <div class="sign-box"><p>Le locataire</p><div class="line">Signature (précédée de « Reçu le … »)</div></div>
</div>
</div>
