<?php /** @var array $lease */ /** @var array $settings */
$s = $settings; $l = $lease;
$bienAdresse = trim(($l['address'] ?: '') . ', ' . ($l['postal_code'] ?: '') . ' ' . ($l['city'] ?: ''), ', ');
$total = (float)$l['rent_amount'] + (float)$l['charges_amount'];
$ville = $s['signature_city'] ?? ($s['landlord_city'] ?? '');
$meuble = $l['lease_type'] === 'meuble';
?>
<h1>CONTRAT DE LOCATION</h1>
<p class="doc-sub"><?= $meuble ? 'Location meublée à usage de résidence principale' : 'Location vide à usage de résidence principale' ?><br>
    <span class="small">(soumis à la loi n° 89-462 du 6 juillet 1989)</span></p>

<h2>Entre les soussignés</h2>
<div class="doc-parties">
    <div class="party">
        <h3>Le bailleur</h3>
        <p><?= e($s['landlord_name'] ?? 'Nom du bailleur') ?><br>
            <?= nl2br(e($s['landlord_address'] ?? '')) ?><br>
            <?= e($s['landlord_city'] ?? '') ?>
            <?php if (!empty($s['landlord_siret'])): ?><br>SIRET : <?= e($s['landlord_siret']) ?><?php endif; ?>
        </p>
    </div>
    <div class="party">
        <h3>Le locataire</h3>
        <p><?= e($l['first_name'].' '.$l['last_name']) ?>
            <?php if (!empty($l['email'])): ?><br><?= e($l['email']) ?><?php endif; ?>
            <?php if (!empty($l['phone'])): ?><br><?= e($l['phone']) ?><?php endif; ?>
        </p>
    </div>
</div>

<h2>Article 1 — Objet du contrat</h2>
<p class="article">Le bailleur donne en location au locataire, qui accepte, le logement désigné ci-après :</p>
<table class="doc-amounts">
    <tr><td>Adresse du logement</td><td><?= e($bienAdresse ?: $l['property_label']) ?></td></tr>
    <tr><td>Type de bien</td><td><?= e(ucfirst($l['property_type'] ?? 'logement')) ?></td></tr>
    <tr><td>Surface habitable</td><td><?= $l['surface_m2'] ? e($l['surface_m2']).' m²' : 'à préciser' ?></td></tr>
    <tr><td>Nombre de pièces principales</td><td><?= $l['rooms'] ?: 'à préciser' ?></td></tr>
    <tr><td>Nature de la location</td><td><?= $meuble ? 'Meublée' : 'Non meublée (vide)' ?></td></tr>
</table>

<h2>Article 2 — Durée du bail</h2>
<p class="article">Le présent bail est consenti pour une durée de <strong><?= $meuble ? 'un (1) an' : 'trois (3) ans' ?></strong>,
à compter du <strong><?= fdate($l['start_date']) ?></strong>
<?php if ($l['end_date']): ?>, soit jusqu'au <strong><?= fdate($l['end_date']) ?></strong><?php endif; ?>.
Il se renouvelle ensuite par tacite reconduction dans les conditions prévues par la loi.</p>

<h2>Article 3 — Loyer et charges</h2>
<table class="doc-amounts">
    <tr><td>Loyer mensuel hors charges</td><td class="num"><?= euros($l['rent_amount']) ?></td></tr>
    <tr><td>Provision mensuelle pour charges</td><td class="num"><?= euros($l['charges_amount']) ?></td></tr>
    <tr class="total"><td>Loyer mensuel charges comprises</td><td class="num"><?= euros($total) ?></td></tr>
</table>
<p class="article">Le loyer est payable d'avance, le <strong><?= (int)$l['payment_day'] ?></strong> de chaque mois,
au domicile du bailleur ou par tout moyen convenu entre les parties.</p>

<h2>Article 4 — Dépôt de garantie</h2>
<p class="article">À la signature des présentes, le locataire verse au bailleur la somme de
<strong><?= euros($l['deposit_amount']) ?></strong> à titre de dépôt de garantie, destinée à couvrir les
éventuels manquements du locataire à ses obligations. Ce dépôt sera restitué dans les conditions et délais légaux.</p>

<h2>Article 5 — Obligations des parties</h2>
<p class="article">Le locataire s'engage à user paisiblement des lieux loués, à les entretenir, à souscrire une assurance
habitation et à en justifier chaque année. Le bailleur s'engage à délivrer un logement décent et à en assurer la
jouissance paisible, conformément à la loi du 6 juillet 1989.</p>

<?php if (!empty($l['notes'])): ?>
<h2>Article 6 — Conditions particulières</h2>
<p class="article"><?= nl2br(e($l['notes'])) ?></p>
<?php endif; ?>

<div class="doc-sign">
    <div class="sign-box"><p>Le bailleur</p><div class="line">Signature (précédée de « Lu et approuvé »)</div></div>
    <div class="sign-box"><p>Le locataire</p><div class="line">Signature (précédée de « Lu et approuvé »)</div></div>
</div>
<p class="article mt">Fait à <?= e($ville ?: '____________') ?>, le <?= fdate(date('Y-m-d')) ?>, en deux exemplaires originaux.</p>
