<?php /** @var array $insp */ /** @var array $lease */ /** @var array $settings */ /** @var bool $forHash */
// État des lieux d'entrée ou de sortie (HTML imprimable et PDF).
// $forHash : rendu sans signatures, pour l'empreinte du document signé.
$d = $insp['data'];
$l = $lease;
$s = $settings;
$sortie = $insp['kind'] === 'sortie';
$meuble = $l['lease_type'] === 'meuble';
$adresse = trim(($l['address'] ?? '') . ', ' . ($l['postal_code'] ?? '') . ' ' . ($l['city'] ?? ''), ', ');
$dots = '<span style="display:inline-block;width:70%;border-bottom:1px dotted #777">&nbsp;</span>';
$val = fn(string $v) => $v !== '' ? nl2br(e($v)) : $dots;
$cb = fn(bool $on) => '<span class="cb">' . ($on ? 'X' : '&nbsp;') . '</span>';
$states = $sortie ? array_diff_key(Inspection::STATES, ['neuf' => 1]) : Inspection::STATES;
$stateCol = $sortie ? 'out_state' : 'in_state';
$notesCol = $sortie ? 'out_notes' : 'in_notes';
$sigs = empty($forHash) && !empty($insp['id']) ? Inspection::valid($insp, $lease, $settings) : [];
$n = 0;
?>
<h1><?= e(mb_strtoupper(Inspection::title($insp))) ?></h1>
<p class="doc-sub"><?= $meuble ? 'Location meublée' : 'Location vide' ?> à usage de résidence principale</p>

<p class="small">Établi contradictoirement entre les parties conformément à l'article 3-2 de la loi n° 89-462 du 6 juillet 1989
    et au décret n° 2016-382 du 30 mars 2016.
    <?= $sortie ? "À comparer à l'état des lieux d'entrée, auquel il est annexé. L'usure normale liée à l'usage et à l'ancienneté n'est pas imputable au locataire."
               : "Il est annexé au contrat de location et sera comparé à l'état des lieux de sortie." ?></p>

<h2><?= ++$n ?> · Désignation des parties</h2>
<table class="doc-amounts">
    <tr><th style="width:34%">Bailleur</th><td><?= e($s['landlord_name'] ?? '') ?></td></tr>
    <tr><th>Domicile du bailleur</th><td><?= e(trim(str_replace("\n", ', ', (string) ($s['landlord_address'] ?? '')) . ' ' . ($s['landlord_city'] ?? ''))) ?></td></tr>
    <tr><th><?= $sortie ? 'Locataire sortant' : 'Locataire' ?></th><td><?= e($l['first_name'] . ' ' . $l['last_name']) ?></td></tr>
    <tr><th>Date et heure de l'état des lieux</th><td><?= $insp['inspected_at'] ? e(date('d/m/Y à H\hi', strtotime($insp['inspected_at']))) : $dots ?></td></tr>
    <?php if ($sortie): ?>
        <tr><th>Nouvelle adresse du locataire<br><span class="small">(restitution du dépôt de garantie)</span></th><td><?= $val($d['tenant']['new_address']) ?></td></tr>
        <tr><th>IBAN pour la restitution du dépôt</th><td><?= $val($d['tenant']['iban']) ?></td></tr>
    <?php endif; ?>
</table>

<h2><?= ++$n ?> · Désignation du logement</h2>
<table class="doc-amounts">
    <tr><th style="width:34%">Adresse</th><td><?= e(implode(' — ', array_filter([$d['residence'], $adresse ?: $l['property_label']], 'strlen'))) ?></td></tr>
    <tr><th>Désignation</th><td><?= $val($d['designation']) ?></td></tr>
    <tr><th>Annexe privative</th><td><?= $d['annexe'] !== '' ? nl2br(e($d['annexe'])) : 'Néant' ?></td></tr>
    <tr><th>Chauffage / eau chaude</th><td><?= $val($d['heating']) ?></td></tr>
</table>

<h2><?= ++$n ?> · Relevé des compteurs</h2>
<?php $m = $d['meters'];
$mDate = $m['date'] !== '' ? fdate($m['date']) : ($insp['inspected_at'] ? date('d/m/Y', strtotime($insp['inspected_at'])) : ''); ?>
<table class="doc-amounts small">
    <thead><tr><th style="width:16%">Compteur</th><th>N° compteur / PDL</th><th class="center">Index HP</th><th class="center">Index HC</th><th class="center" style="width:14%">Date</th></tr></thead>
    <tr><td>Électricité</td><td><?= e($m['elec_number']) ?></td><td class="center"><?= e($m['elec_hp']) ?></td><td class="center"><?= e($m['elec_hc']) ?></td><td class="center"><?= e($mDate) ?></td></tr>
    <tr><td>Eau froide</td><td><?= e($m['water_number']) ?></td><td colspan="2">Index : <?= e($m['water']) ?></td><td class="center"><?= e($mDate) ?></td></tr>
</table>
<?php if ($sortie): ?>
<p class="small">Reporter l'index relevé à l'entrée (état des lieux d'entrée) pour calculer la consommation, si nécessaire<?php
    $inIdx = array_filter(['électricité HP' => $m['elec_hp_in'], 'HC' => $m['elec_hc_in'], 'eau' => $m['water_in']], 'strlen');
    echo $inIdx ? ' — index à l\'entrée : ' . e(implode(' · ', array_map(fn($k, $v) => "$k $v", array_keys($inIdx), $inIdx))) : '' ?>.</p>
<?php endif; ?>

<h2><?= ++$n ?> · Clés et accès <?= $sortie ? 'restitués' : 'remis' ?></h2>
<table class="doc-amounts small">
    <thead><tr><th>Élément</th><th class="center">Remis à l'entrée</th><?php if ($sortie): ?><th class="center">Restitué</th><?php endif; ?><th>Observations</th></tr></thead>
    <?php foreach ($d['keys'] as $k): ?>
        <tr><td><?= e($k['label']) ?></td><td class="center"><?= e($k['in']) ?></td><?php if ($sortie): ?><td class="center"><?= e($k['out']) ?></td><?php endif; ?><td><?= e($k['notes']) ?></td></tr>
    <?php endforeach; ?>
    <?php if (!array_filter($d['keys'], fn($k) => str_starts_with($k['label'], 'Autre'))): ?>
        <tr><td>Autre (à préciser)</td><td></td><?php if ($sortie): ?><td></td><?php endif; ?><td></td></tr>
    <?php endif; ?>
</table>

<h2><?= ++$n ?> · État détaillé par pièce</h2>
<p class="small"><?= $sortie ? "Cocher l'état constaté à la sortie et décrire toute différence par rapport à l'entrée (trace, rayure, tache, casse, manque, mauvais fonctionnement)."
                          : "Cocher l'état constaté à l'entrée et décrire tout défaut (trace, rayure, tache, casse, manque, mauvais fonctionnement)." ?></p>
<?php foreach ($d['rooms'] as $r):
    $showIn = $sortie && array_filter(array_column($r['items'], 'in_state')); ?>
    <table class="doc-amounts small inventory">
        <thead><tr><th style="width:38%"><?= e($r['name']) ?></th>
            <?php if ($showIn): ?><th class="col-present" style="width:9%">Entrée</th><?php endif; ?>
            <?php foreach ($states as $lbl): ?><th class="center" style="width:7%"><?= e($lbl) ?></th><?php endforeach; ?>
            <th>Observations</th></tr></thead>
        <?php foreach ($r['items'] as $it): ?>
            <tr><td><?= e($it['label']) ?></td>
                <?php if ($showIn): ?><td class="col-present"><?= e(Inspection::stateLabel($it['in_state'])) ?></td><?php endif; ?>
                <?php foreach ($states as $k => $lbl): ?><td class="center"><?= $cb($it[$stateCol] === $k) ?></td><?php endforeach; ?>
                <td><?= e($it[$notesCol]) ?></td></tr>
        <?php endforeach; ?>
    </table>
<?php endforeach; ?>
<p class="small">Propreté générale <?= $sortie ? 'à la sortie' : "à l'entrée" ?> :
    <?= $cb($d['cleanliness'] === 'propre') ?> Propre &nbsp; <?= $cb($d['cleanliness'] === 'a_nettoyer') ?> À nettoyer
    <?= $d['cleanliness_notes'] !== '' ? ' — ' . e($d['cleanliness_notes']) : '' ?></p>

<?php if ($meuble && $d['inventory']): ?>
<h2><?= ++$n ?> · Inventaire du mobilier et des équipements</h2>
<p class="small">Établi conformément au décret n° 2015-981 du 31 juillet 2015.<?= $sortie ? ' Les colonnes « Entrée » reprennent l\'état des lieux d\'entrée ; renseigner les colonnes « Sortie ».' : '' ?></p>
<?php foreach ($d['inventory'] as $g): ?>
    <table class="doc-amounts small inventory">
        <thead><tr><th style="width:38%"><?= e($g['name']) ?></th><th class="center" style="width:8%">Qté<br>entrée</th><th class="center" style="width:10%">État<br>entrée</th>
            <?php if ($sortie): ?><th class="center" style="width:8%">Qté<br>sortie</th><th class="center" style="width:10%">État<br>sortie</th><?php endif; ?>
            <th>Observations</th></tr></thead>
        <?php foreach ($g['items'] as $it): ?>
            <tr><td><?= e($it['label']) ?></td><td class="center"><?= e($it['in_qty']) ?></td><td class="center"><?= e(Inspection::stateLabel($it['in_state'])) ?></td>
                <?php if ($sortie): ?><td class="center"><?= e($it['out_qty']) ?></td><td class="center"><?= e(Inspection::stateLabel($it['out_state'])) ?></td><?php endif; ?>
                <td><?= e($it['notes']) ?></td></tr>
        <?php endforeach; ?>
    </table>
<?php endforeach; ?>
<?php endif; ?>

<h2><?= ++$n ?> · Observations générales<?= $sortie ? ' et différences constatées' : '' ?></h2>
<p class="article"><?= $d['observations'] !== '' ? nl2br(e($d['observations'])) : 'Néant.' ?></p>
<?php if ($d['tenant']['observations'] !== ''): ?>
    <p class="article"><strong>Observations du locataire :</strong><br><?= nl2br(e($d['tenant']['observations'])) ?></p>
<?php endif; ?>

<?php if ($sortie): $refund = Inspection::refund($d); ?>
<h2><?= ++$n ?> · Dépôt de garantie</h2>
<table class="doc-amounts">
    <tr><th style="width:34%">Montant du dépôt versé</th><td><?= $d['deposit']['amount'] !== '' ? euros(num($d['deposit']['amount'])) : $dots ?></td></tr>
    <tr><th>Retenues proposées (motif et montant)</th><td><?= $d['deposit']['deductions'] !== '' ? nl2br(e($d['deposit']['deductions'])) : ($d['deposit']['deducted'] !== '' && num($d['deposit']['deducted']) == 0 ? 'Aucune' : $dots) ?>
        <?= $d['deposit']['deducted'] !== '' && num($d['deposit']['deducted']) > 0 ? '<br><strong>Total : ' . euros(num($d['deposit']['deducted'])) . '</strong>' : '' ?></td></tr>
    <tr><th>Montant à restituer</th><td><?= $refund !== null && $d['deposit']['deducted'] !== '' ? '<strong>' . euros($refund) . '</strong>' : $dots ?></td></tr>
</table>
<p class="small">Le dépôt de garantie est restitué dans un délai d'un mois à compter de la remise des clés si l'état des lieux de sortie
    est conforme à l'état des lieux d'entrée, et de deux mois dans le cas contraire, déduction faite des sommes justifiées dues au bailleur
    (art. 22 de la loi n° 89-462).</p>
<?php endif; ?>

<div class="keep">
<div class="doc-sign">
<?php foreach (Inspection::ROLES as $role => $label): $sg = $sigs[$role] ?? null; ?>
    <div class="sign-box">
        <p><strong><?= $role === 'bailleur' ? 'Le Bailleur' : 'Le Locataire' ?></strong></p>
        <p class="small">Fait à <?= e($s['signature_city'] ?? '') ?: '………………………' ?> le <?= $sg ? e(date('d/m/Y', strtotime($sg['signed_at']))) : '……………' ?></p>
        <?php if ($sg): ?>
            <img class="signature-img" src="<?= e($sg['image']) ?>" alt="Signature">
            <div class="line small">Lu et approuvé — signé électroniquement par <?= e($sg['signer_name']) ?>
                le <?= e(date('d/m/Y à H:i', strtotime($sg['signed_at']))) ?><br>Adresse IP : <?= e($sg['ip'] ?: 'non disponible') ?></div>
        <?php else: ?>
            <div class="line small">Signature précédée de « Lu et approuvé »</div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>
<p class="mt small">Établi en deux exemplaires originaux, un pour chaque partie.</p>
</div>

<?php if ($sigs): ?>
<div class="keep sign-proof">
    <h3>Certificat de signature électronique</h3>
    <p class="small">Signature électronique simple (art. 1366 et 1367 du Code civil). Éléments de preuve enregistrés au moment de chaque signature :</p>
    <table class="doc-amounts small">
        <thead><tr><th>Signataire</th><th>Date et heure</th><th>Adresse IP</th><th>Navigateur / appareil</th></tr></thead>
        <?php foreach (Inspection::ROLES as $role => $label): $sg = $sigs[$role] ?? null; if (!$sg) continue; ?>
            <tr><td><?= e($sg['signer_name']) ?><br><span class="small"><?= $label ?></span></td>
                <td><?= e(date('d/m/Y à H:i:s', strtotime($sg['signed_at']))) ?></td>
                <td><?= e($sg['ip'] ?: '—') ?></td>
                <td><?= e(LeaseSignature::deviceLabel((string) $sg['user_agent'])) ?></td></tr>
        <?php endforeach; ?>
    </table>
    <p class="small">Empreinte numérique (SHA-256) du document signé par chaque partie<?= count($sigs) > 1 ? ' (celle du bailleur porte sur le document hors informations et observations saisies par le locataire)' : '' ?> :<br>
        <?php foreach ($sigs as $role => $sg): ?><?= e(Inspection::ROLES[$role]) ?> : <span style="font-family:monospace;word-break:break-all"><?= e($sg['doc_hash']) ?></span><br><?php endforeach; ?></p>
</div>
<?php endif; ?>
