<?php /** @var array $lease */ /** @var array $settings */
/*
 * Bail de location meublée (résidence principale — LMNP).
 * Fondements juridiques :
 *   - Loi n° 89-462 du 6 juillet 1989 (art. 3 mentions obligatoires, art. 8-1
 *     surface, art. 17-1 révision IRL, art. 22 dépôt de garantie,
 *     art. 22-1 cautionnement, art. 23 charges, art. 25-3 et s. bail meublé) ;
 *   - Loi ALUR n° 2014-366 du 24 mars 2014 (mentions, surface habitable) ;
 *   - Décret n° 2015-981 du 31 juillet 2015 (liste du mobilier obligatoire).
 *
 * Règle de génération : aucun champ juridiquement requis ne doit sortir en
 * blanc. La validation Lease::contractIssues() bloque la génération en amont si
 * une donnée obligatoire manque ; l'IRL de référence est calculé
 * automatiquement à partir de la date de signature.
 */
$s = $settings; $l = $lease;
$bienAdresse = trim(($l['address'] ?? '') . ', ' . ($l['postal_code'] ?? '') . ' ' . ($l['city'] ?? ''), ', ');
$loyerCC = (float)$l['rent_amount'] + (float)$l['charges_amount'];
$ville = $s['signature_city'] ?? ($s['landlord_city'] ?? '');
$sigDate = $l['signature_date'] ?: date('Y-m-d');
$furnitureExtra = array_filter(array_map('trim', explode("\n", (string) ($l['furniture_extra'] ?? ''))));

// Mode de charges retenu (une seule formulation, sans ambiguïté). Art. 23 (provisions) / art. 25-9 (forfait meublé).
$forfait = ($l['charge_type'] ?? 'provisions') === 'forfait';

// IRL de référence : override manuel (Paramètres) sinon calcul auto selon la date de signature (art. 17-1).
$irlAuto = irlReference($sigDate);
$irlQuarter = !empty($s['irl_quarter']) ? (int) $s['irl_quarter'] : $irlAuto['quarter'];
$irlYear    = !empty($s['irl_year'])    ? (int) $s['irl_year']    : $irlAuto['year'];

// Garants déclarés (0, 1 ou 2) — pour la clause de cautionnement (art. 22-1).
$guarants = Lease::guarantors($l);
$guarantNames = array_map(fn($g) => $g['name'], $guarants);

// La date de fin saisie fait foi ; la durée en est déduite pour garantir la cohérence.
$finBail = $l['end_date'] ?: ($l['start_date'] ? date('Y-m-d', strtotime($l['start_date'] . ' +1 year -1 day')) : null);
$dureeBail = dureeBail($l['start_date'], $finBail);

// Numérotation d'articles automatique (des articles peuvent être conditionnels).
$artNum = 1;

// Inventaire du mobilier obligatoire (décret n° 2015-981 du 31 juillet 2015)
$mobilierObligatoire = [
    'Literie comprenant couette ou couverture',
    'Dispositif d\'occultation des fenêtres dans les chambres (volets ou rideaux)',
    'Plaques de cuisson',
    'Four ou four à micro-ondes',
    'Réfrigérateur et congélateur (ou compartiment à -6 °C)',
    'Vaisselle nécessaire à la prise des repas',
    'Ustensiles de cuisine',
    'Table et sièges',
    'Étagères de rangement',
    'Luminaires',
    'Matériel d\'entretien ménager adapté au logement',
];
?>
<h1>CONTRAT DE LOCATION MEUBLÉE</h1>
<p class="doc-sub">À usage de résidence principale — Location meublée (LMNP)<br>
    <span class="small">Soumis à la loi n° 89-462 du 6 juillet 1989 (art. 25-3 et suivants)
    et au décret n° 2015-981 du 31 juillet 2015</span></p>

<h2>Entre les soussignés</h2>
<div class="doc-parties">
    <div class="party">
        <h3>Le bailleur</h3>
        <p><?= e($s['landlord_name'] ?? 'Nom du bailleur') ?><br>
            <?= nl2br(e($s['landlord_address'] ?? '')) ?><br>
            <?= e($s['landlord_city'] ?? '') ?>
            <?php if (!empty($s['landlord_phone'])): ?><br>Tél. : <?= e($s['landlord_phone']) ?><?php endif; ?>
            <?php if (!empty($s['landlord_email'])): ?><br><?= e($s['landlord_email']) ?><?php endif; ?>
            <?php if (!empty($s['landlord_siret'])): ?><br>SIRET : <?= e($s['landlord_siret']) ?><?php endif; ?>
        </p>
        <p class="small">Ci-après « le bailleur »,</p>
    </div>
    <div class="party">
        <h3>Le locataire</h3>
        <p><?= e($l['first_name'].' '.$l['last_name']) ?>
            <?php if (!empty($l['email'])): ?><br><?= e($l['email']) ?><?php endif; ?>
            <?php if (!empty($l['phone'])): ?><br>Tél. : <?= e($l['phone']) ?><?php endif; ?>
            <?php if (!empty($l['tenant_current_address'])): ?><br>Domicile actuel : <?= nl2br(e($l['tenant_current_address'])) ?><?php endif; ?>
        </p>
        <p class="small">Ci-après « le locataire »,</p>
    </div>
</div>
<p class="article">Il a été convenu ce qui suit :</p>

<h2>Article <?= $artNum++ ?> — Objet du contrat et désignation du logement</h2>
<p class="article">Le bailleur loue au locataire, qui accepte, le logement <strong>meublé</strong> désigné ci-après,
loué avec l'ensemble des équipements et du mobilier figurant à l'inventaire annexé (Annexe 1) :</p>
<table class="doc-amounts">
    <tr><td>Adresse du logement</td><td><?= e($bienAdresse ?: $l['property_label']) ?></td></tr>
    <tr><td>Type de bien</td><td><?= e(ucfirst($l['property_type'] ?? 'logement')) ?></td></tr>
    <tr><td>Surface habitable</td><td><?= $l['surface_m2'] ? e($l['surface_m2']).' m²' : 'à préciser' ?></td></tr>
    <tr><td>Nombre de pièces principales</td><td><?= $l['rooms'] ?: 'à préciser' ?></td></tr>
    <tr><td>Nature de la location</td><td>Location meublée</td></tr>
</table>

<h2>Article <?= $artNum++ ?> — Destination des lieux</h2>
<p class="article">Le logement est loué à usage exclusif d'<strong>habitation principale</strong> du locataire.
Toute activité professionnelle ou commerciale y est interdite, sauf accord écrit préalable du bailleur.</p>

<h2>Article <?= $artNum++ ?> — Durée du contrat et prise d'effet</h2>
<p class="article">
<?php if ($dureeBail): ?>
Le présent bail est conclu pour une durée de <strong><?= e($dureeBail) ?></strong> à compter du
<strong><?= fdate($l['start_date']) ?></strong><?php if ($finBail): ?>, soit jusqu'au <strong><?= fdate($finBail) ?></strong> inclus<?php endif; ?>.
<?php else: ?>
Le présent bail prend effet le <strong><?= fdate($l['start_date']) ?></strong><?php if ($finBail): ?> et s'achève le <strong><?= fdate($finBail) ?></strong> inclus<?php endif; ?>.
<?php endif; ?>
Il est reconductible tacitement, sauf congé donné dans les conditions de l'article « Congé ».
<br><span class="small">(La durée minimale d'un bail meublé de résidence principale est d'un an, ou de neuf mois pour un étudiant, sans tacite reconduction.)</span></p>

<h2>Article <?= $artNum++ ?> — Loyer et charges</h2>
<table class="doc-amounts">
    <tr><td>Loyer mensuel hors charges</td><td class="num"><?= euros($l['rent_amount']) ?></td></tr>
    <tr><td><?= $forfait ? 'Forfait mensuel de charges' : 'Provision mensuelle pour charges' ?></td><td class="num"><?= euros($l['charges_amount']) ?></td></tr>
    <tr class="total"><td>Loyer mensuel charges comprises</td><td class="num"><?= euros($loyerCC) ?></td></tr>
</table>
<p class="article">Le loyer est payable d'avance, le <strong><?= (int)$l['payment_day'] ?></strong> de chaque mois.
<?php if ($forfait): ?>
Les charges récupérables sont réglées sous forme d'un <strong>forfait mensuel</strong> ajouté au loyer, non soumis à
régularisation ni à complément, conformément à l'article 25-9 de la loi du 6 juillet 1989 applicable à la location meublée.
<?php else: ?>
Les charges récupérables sont réglées par <strong>provisions mensuelles</strong> donnant lieu à une
<strong>régularisation annuelle</strong> sur justificatifs, conformément à l'article 23 de la loi du 6 juillet 1989.
<?php endif; ?></p>

<h2>Article <?= $artNum++ ?> — Révision du loyer</h2>
<p class="article">Le loyer peut être révisé chaque année à la date anniversaire du contrat, en fonction de la variation de
l'<strong>Indice de Référence des Loyers (IRL)</strong> publié par l'INSEE (art. 17-1 de la loi du 6 juillet 1989).
Indice de référence retenu : <strong>trimestre <?= (int) $irlQuarter ?> de l'année <?= (int) $irlYear ?></strong>.</p>

<h2>Article <?= $artNum++ ?> — Dépôt de garantie</h2>
<p class="article">À la signature du bail, le locataire verse au bailleur un dépôt de garantie de
<strong><?= euros($l['deposit_amount']) ?></strong>
(en location meublée, il ne peut excéder <strong>deux mois</strong> de loyer hors charges).
Il est restitué dans un délai maximal de deux mois après la remise des clés, déduction faite des sommes dues.</p>

<?php if ($guarants): // Clause de cautionnement — affichée uniquement si un ou des garants sont déclarés (art. 22-1) ?>
<h2>Article <?= $artNum++ ?> — Cautionnement solidaire</h2>
<p class="article">
<?php if (count($guarants) === 1): ?>
Le présent bail est garanti par un <strong>acte de cautionnement solidaire</strong> signé par
<strong><?= e($guarantNames[0]) ?></strong>, document distinct annexé aux présentes.
<?php else: ?>
Le présent bail est garanti par deux <strong>actes de cautionnement solidaire</strong> signés respectivement par
<strong><?= e($guarantNames[0]) ?></strong> et <strong><?= e($guarantNames[1]) ?></strong>, documents distincts annexés aux présentes.
<?php endif; ?>
Chaque caution répond solidairement des obligations du locataire dans les conditions et limites fixées à son acte.</p>
<?php endif; ?>

<h2>Article <?= $artNum++ ?> — Obligations du bailleur</h2>
<p class="article">Le bailleur est tenu de délivrer un logement décent et en bon état d'usage, d'assurer la jouissance paisible
des lieux, d'entretenir les locaux et d'y effectuer les réparations autres que locatives, conformément à la loi
du 6 juillet 1989.</p>

<h2>Article <?= $artNum++ ?> — Obligations du locataire</h2>
<p class="article">Le locataire s'oblige à : payer le loyer et les charges aux termes convenus ; user paisiblement des lieux
loués suivant leur destination ; répondre des dégradations survenues pendant la location ; prendre à sa charge
l'entretien courant et les réparations locatives ; s'<strong>assurer contre les risques locatifs</strong> et en
justifier chaque année ; ne pas céder ni sous-louer sans l'accord écrit du bailleur.</p>

<h2>Article <?= $artNum++ ?> — Congé</h2>
<p class="article">Le <strong>locataire</strong> peut donner congé à tout moment moyennant un préavis d'<strong>un (1) mois</strong>.
Le <strong>bailleur</strong> peut donner congé pour l'échéance du bail, moyennant un préavis de <strong>trois (3) mois</strong>,
et pour un motif légitime et sérieux, pour reprise ou pour vente. Le congé est notifié par lettre recommandée avec accusé
de réception, acte d'huissier ou remise en main propre contre récépissé.</p>

<h2>Article <?= $artNum++ ?> — Clause résolutoire</h2>
<p class="article">À défaut de paiement du loyer ou des charges aux échéances convenues, de versement du dépôt de garantie,
ou de défaut d'assurance, le bail sera résilié de plein droit deux mois après un commandement de payer ou de
s'exécuter demeuré infructueux.</p>

<h2>Article <?= $artNum++ ?> — État des lieux et inventaire</h2>
<p class="article">Un état des lieux contradictoire ainsi qu'un inventaire détaillé du mobilier (Annexe 1) sont établis lors
de la remise et de la restitution des clés, et annexés au présent contrat.</p>

<h2>Article <?= $artNum++ ?> — Documents remis au locataire</h2>
<p class="article">Sont remis au locataire, en complément du présent contrat et en annexe séparée :
le <strong>diagnostic de performance énergétique (DPE)</strong>, l'<strong>état des risques et pollutions (ERP)</strong>,
et la <strong>notice d'information</strong> relative aux droits et obligations des locataires et des bailleurs
(art. 3-3 de la loi du 6 juillet 1989 ; loi ALUR n° 2014-366).</p>

<?php if (!empty($l['notes'])): ?>
<h2>Article <?= $artNum++ ?> — Conditions particulières</h2>
<p class="article"><?= nl2br(e($l['notes'])) ?></p>
<?php endif; ?>

<div class="doc-sign">
    <div class="sign-box"><p>Le bailleur</p><div class="line">Signature (précédée de « Lu et approuvé »)</div></div>
    <div class="sign-box"><p>Le locataire</p><div class="line">Signature (précédée de « Lu et approuvé »)</div></div>
</div>
<p class="article mt">Fait à <?= e($ville ?: '____________') ?>, le <?= fdate($sigDate) ?>, en deux exemplaires originaux.</p>

<div style="page-break-before:always"></div>
<h1>ANNEXE 1 — INVENTAIRE DU MOBILIER</h1>
<p class="doc-sub">Éléments d'ameublement obligatoires (décret n° 2015-981 du 31 juillet 2015)</p>
<table class="doc-amounts">
    <thead><tr><th style="text-align:left">Élément</th><th>Présent</th><th>État / observations</th></tr></thead>
    <tbody>
    <?php foreach ($mobilierObligatoire as $item): ?>
        <tr>
            <td><?= e($item) ?></td>
            <td class="center">☐ Oui ☐ Non</td>
            <td>______________________</td>
        </tr>
    <?php endforeach; ?>
    <?php foreach ($furnitureExtra as $item): ?>
        <tr>
            <td><?= e($item) ?></td>
            <td class="center">☐ Oui ☐ Non</td>
            <td>______________________</td>
        </tr>
    <?php endforeach; ?>
        <tr><td>Autre (à préciser) : ____________________</td><td class="center">☐ Oui ☐ Non</td><td>______________________</td></tr>
        <tr><td>Autre (à préciser) : ____________________</td><td class="center">☐ Oui ☐ Non</td><td>______________________</td></tr>
    </tbody>
</table>
<p class="mention">Le logement meublé doit comporter au minimum l'ensemble des éléments ci-dessus, en nombre suffisant
et en bon état de fonctionnement, pour permettre au locataire d'y vivre normalement avec ses seuls effets personnels.
<br><span class="small">Les cases « Présent » et la colonne « État / observations » sont renseignées à la main lors de l'état des lieux d'entrée.</span></p>

<div class="doc-sign">
    <div class="sign-box"><p>Le bailleur</p><div class="line">Signature</div></div>
    <div class="sign-box"><p>Le locataire</p><div class="line">Signature</div></div>
</div>
