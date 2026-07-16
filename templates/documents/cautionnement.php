<?php /** @var array $lease */ /** @var array $settings */
$s = $settings; $l = $lease;
$bienAdresse = trim(($l['address'] ?: '') . ', ' . ($l['postal_code'] ?: '') . ' ' . ($l['city'] ?: ''), ', ');
$loyer   = (float) $l['rent_amount'];
$charges = (float) $l['charges_amount'];
$loyerCC = $loyer + $charges;
$finBail = $l['end_date'] ?: ($l['start_date'] ? date('Y-m-d', strtotime($l['start_date'] . ' +1 year -1 day')) : null);
$duree   = dureeBail($l['start_date'], $finBail);
$indet   = ($l['guarantor_duration'] ?? 'indeterminee') !== 'determinee';
$max     = $l['guarantor_max_amount'] !== null && $l['guarantor_max_amount'] !== '' ? (float) $l['guarantor_max_amount'] : null;
$ville   = $s['signature_city'] ?? ($s['landlord_city'] ?? '');
$irl     = (!empty($s['irl_quarter']) && !empty($s['irl_year']))
    ? ('trimestre ' . $s['irl_quarter'] . ' de l\'année ' . $s['irl_year'])
    : 'indice de référence des loyers (IRL) publié par l\'INSEE';
?>
<h1>ACTE DE CAUTIONNEMENT SOLIDAIRE</h1>
<p class="doc-sub">Engagement de caution — article 22-1 de la loi n° 89-462 du 6 juillet 1989<br>
    <span class="small">Document distinct du contrat de bail, à faire signer par le garant.</span></p>

<h2>Entre les soussignés</h2>
<div class="doc-parties">
    <div class="party">
        <h3>La caution (le garant)</h3>
        <p><strong><?= e($l['guarantor_name'] ?? 'Nom du garant') ?></strong><br>
            <?= nl2br(e($l['guarantor_address'] ?? '')) ?>
            <?php if (!empty($l['guarantor_birth_date'])): ?><br>Né(e) le <?= fdate($l['guarantor_birth_date']) ?><?php if (!empty($l['guarantor_birth_place'])): ?> à <?= e($l['guarantor_birth_place']) ?><?php endif; ?><?php endif; ?>
            <?php if (!empty($l['guarantor_phone'])): ?><br>Tél. : <?= e($l['guarantor_phone']) ?><?php endif; ?>
            <?php if (!empty($l['guarantor_email'])): ?><br><?= e($l['guarantor_email']) ?><?php endif; ?>
        </p>
        <p class="small">Ci-après « la caution »,</p>
    </div>
    <div class="party">
        <h3>Le bailleur</h3>
        <p><strong><?= e($s['landlord_name'] ?? 'Nom du bailleur') ?></strong><br>
            <?= nl2br(e($s['landlord_address'] ?? '')) ?><br>
            <?= e($s['landlord_city'] ?? '') ?>
            <?php if (!empty($s['landlord_email'])): ?><br><?= e($s['landlord_email']) ?><?php endif; ?>
        </p>
        <p class="small">Ci-après « le bailleur »,</p>
    </div>
</div>

<p class="article">La caution déclare se porter caution <strong>solidaire</strong> de
<strong><?= e($l['first_name'] . ' ' . $l['last_name']) ?></strong> (ci-après « le locataire »),
au titre du bail portant sur le logement situé <strong><?= e($bienAdresse ?: $l['property_label']) ?></strong>,
conclu à compter du <strong><?= fdate($l['start_date']) ?></strong><?php if ($duree): ?> pour une durée de <strong><?= e($duree) ?></strong><?php endif; ?>.</p>

<h2>Article 1 — Montant du loyer et conditions de révision</h2>
<p class="article">Conformément au contrat de location, le loyer est fixé à
<strong><?= euros($loyer) ?></strong> hors charges par mois, outre une provision (ou forfait) pour charges de
<strong><?= euros($charges) ?></strong>, soit un montant mensuel de <strong><?= euros($loyerCC) ?></strong>
charges comprises. Le loyer est révisable chaque année selon l'<?= e($irl) ?>.</p>

<h2>Article 2 — Étendue de l'engagement</h2>
<p class="article">La caution s'engage à payer au bailleur, en cas de défaillance du locataire, toutes les sommes
dues au titre du bail : <strong>loyers, charges, taxes locatives, réparations locatives, indemnités
d'occupation, intérêts et, le cas échéant, frais de procédure</strong>, dans la limite des dispositions légales.</p>
<?php if ($max !== null): ?>
<p class="article">L'engagement de la caution est limité à un montant maximal de
<strong><?= euros($max) ?></strong> (<strong><?= e(eurosLettres($max)) ?></strong>), toutes sommes confondues.</p>
<?php endif; ?>

<h2>Article 3 — Caution solidaire</h2>
<p class="article">La caution renonce expressément aux <strong>bénéfices de discussion et de division</strong>
prévus aux articles 2305 et 2306 du code civil. En conséquence, le bailleur peut lui réclamer le paiement
de l'intégralité des sommes dues sans être tenu de poursuivre préalablement le locataire.</p>

<h2>Article 4 — Durée de l'engagement</h2>
<?php if ($indet): ?>
<p class="article">Le présent cautionnement est conclu pour une <strong>durée indéterminée</strong>.
La caution peut le résilier unilatéralement dans les conditions rappelées ci-dessous.</p>
<?php else: ?>
<p class="article">Le présent cautionnement est conclu pour la <strong>durée du bail initial</strong>
(<?= fdate($l['start_date']) ?><?php if ($finBail): ?> au <?= fdate($finBail) ?><?php endif; ?>)
<strong>ainsi que ses reconductions et renouvellements</strong>, dans la limite de trois (3) renouvellements successifs.</p>
<?php endif; ?>

<h2>Article 5 — Information de la caution</h2>
<p class="article">La caution reconnaît, par la présente mention explicite et non équivoque, avoir
<strong>parfaitement connaissance de la nature et de l'étendue de son engagement</strong>.
Le bailleur l'informera de toute défaillance du locataire dans les conditions de l'article 24 de la loi du 6 juillet 1989.</p>

<h2>Reproduction de l'avant-dernier alinéa de l'article 22-1 de la loi du 6 juillet 1989</h2>
<p class="article" style="font-style:italic">« Lorsque le cautionnement d'obligations résultant d'un contrat de location conclu en application
du présent titre ne comporte aucune indication de durée ou lorsque la durée du cautionnement est stipulée
indéterminée, la caution peut le résilier unilatéralement. La résiliation prend effet au terme du contrat
de location, qu'il s'agisse du contrat initial ou d'un contrat reconduit ou renouvelé, au cours duquel le
bailleur reçoit notification de la résiliation. »</p>

<div class="doc-sign">
    <div class="sign-box">
        <p>La caution</p>
        <div class="line">Signature précédée de la mention manuscrite :<br>
            « Bon pour caution solidaire<?php if ($max !== null): ?> à hauteur de <?= e(eurosLettres($max)) ?><?php endif; ?>. Lu et approuvé. »</div>
    </div>
    <div class="sign-box"><p>Le bailleur</p><div class="line">Signature</div></div>
</div>
<p class="article mt">Fait à <?= e($ville ?: '____________') ?>, le <?= fdate(date('Y-m-d')) ?>, en deux exemplaires originaux,
dont un remis à la caution.</p>
