<?php /** @var array $lease */ /** @var array $payments */ /** @var array $settings */
$l = $lease;
$guarants = Lease::guarantors($l);
$issues = $l['lease_type'] === 'meuble' ? Lease::contractIssues($l, $settings ?? []) : ['blocking' => [], 'warnings' => []];
?>
<div class="page-head">
    <div>
        <h1>Bail — <?= e($l['first_name'].' '.$l['last_name']) ?></h1>
        <p class="muted"><a href="<?= url('/biens/'.$l['property_id']) ?>"><?= e($l['property_label']) ?></a>
            · <?php $ph = Lease::phase($l); ?><span class="badge badge-<?= $ph['badge'] ?>"><?= e($ph['label']) ?></span></p>
    </div>
    <div class="actions">
        <a href="<?= url('/contrat/'.$l['id']) ?>" class="btn btn-secondary" target="_blank">📄 <?= $l['lease_type']==='meuble' ? 'Contrat de bail meublé (LMNP)' : 'Contrat de bail' ?></a>
        <?php foreach ($guarants as $i => $g): ?>
            <a href="<?= url('/caution/'.$l['id'].($i===1?'?g=2':'')) ?>" class="btn btn-secondary" target="_blank">🖋️ Acte de cautionnement<?= count($guarants) > 1 ? ' — '.e($g['name']) : '' ?></a>
        <?php endforeach; ?>
        <a href="<?= url('/locataires/'.$l['tenant_id'].'/espace') ?>" class="btn" target="_blank">👁 Espace locataire</a>
        <a href="<?= url('/baux/'.$l['id'].'/edit') ?>" class="btn">Modifier</a>
    </div>
</div>

<?php
$applicable = Checklist::applicableItems($l['lease_type']);
$checked    = Checklist::checkedKeys((int) $l['id']);
[$done, $total] = Checklist::progress((int) $l['id'], $l['lease_type']);
$pct = $total > 0 ? round($done / $total * 100) : 0;
?>
<?php
$tabSigned = count(LeaseSignature::valid($l, $settings)) === count(LeaseSignature::ROLES);
$tabDocIssues = count(PropertyDocument::issues((int) $l['property_id'], $l['signature_date'] ?: $l['start_date']));
$tabInv = $l['lease_type'] === 'meuble' ? Inventory::progress(Inventory::rows($l)) : null;
?>
<div data-tabs>
<nav class="tabs">
    <a href="#apercu">Vue d'ensemble<?php if ($l['lease_type'] === 'meuble' && $issues['blocking']): ?> <span class="tab-count tab-alert">⚠</span><?php endif; ?></a>
    <a href="#loyers">Loyers <span class="tab-count"><?= count($payments) ?></span></a>
    <a href="#signature">Signature <span class="tab-count<?= $tabSigned ? '' : ' tab-alert' ?>"><?= $tabSigned ? '✔' : 'à faire' ?></span></a>
    <a href="#liasse">Dossier locataire<?php if ($tabDocIssues): ?> <span class="tab-count tab-alert"><?= $tabDocIssues ?> ⚠</span><?php endif; ?></a>
    <?php if ($tabInv): ?><a href="#inventaire">Inventaire <span class="tab-count"><?= $tabInv[0] ?>/<?= $tabInv[1] ?></span></a><?php endif; ?>
    <a href="#checklist">Checklist <span class="tab-count"><?= $done ?>/<?= $total ?></span></a>
</nav>

<section class="tab-panel" id="apercu">
<?php if ($l['lease_type'] === 'meuble' && ($issues['blocking'] || $issues['warnings'])): ?>
<div class="card">
    <h3>Contrôle avant génération du bail</h3>
    <?php if ($issues['blocking']): ?>
        <p class="small" style="color:#b91c1c;font-weight:600">⛔ À corriger — la génération du bail est bloquée tant que ces points ne sont pas réglés :</p>
        <ul class="small">
            <?php foreach ($issues['blocking'] as $b): ?><li style="color:#b91c1c"><?= e($b) ?></li><?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="small" style="color:#15803d;font-weight:600">✓ Toutes les données obligatoires sont présentes.</p>
    <?php endif; ?>
    <?php if ($issues['warnings']): ?>
        <p class="small" style="font-weight:600">⚠️ À vérifier :</p>
        <ul class="small muted">
            <?php foreach ($issues['warnings'] as $w): ?><li><?= e($w) ?></li><?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>
<?php endif; ?>

<div class="grid grid-4 mb">
    <div class="stat"><div class="label">Loyer HC</div><div class="value"><?= euros($l['rent_amount']) ?></div></div>
    <div class="stat"><div class="label">Charges</div><div class="value"><?= euros($l['charges_amount']) ?></div></div>
    <div class="stat"><div class="label">Loyer CC</div><div class="value"><?= euros((float)$l['rent_amount']+(float)$l['charges_amount']) ?></div></div>
    <div class="stat"><div class="label">Dépôt garantie</div><div class="value"><?= euros($l['deposit_amount']) ?></div></div>
</div>

<?php $depDue = Lease::depositDue($l); $termEnd = Lease::currentTermEnd($l); ?>
<div class="grid grid-2col mb">
    <div class="card">
        <h3>Durée du bail</h3>
        <p>Du <strong><?= fdate($l['start_date']) ?></strong><?= $l['end_date'] ? ' au <strong>' . fdate($l['end_date']) . '</strong> (période initiale)' : '' ?><br>
        <?php if (!empty($l['auto_renew']) && $l['status'] === 'active'): ?>
            <span class="badge badge-paid">Renouvelable automatiquement</span>
            <?php if ($termEnd && $termEnd !== $l['end_date']): ?><span class="small muted"> · période en cours jusqu'au <?= fdate($termEnd) ?></span><?php endif; ?>
        <?php elseif ($l['status'] === 'active'): ?>
            <span class="badge badge-pending">Sans tacite reconduction</span>
        <?php else: ?>
            <span class="badge badge-pending">Bail terminé</span>
        <?php endif; ?></p>
    </div>
    <div class="card" id="depot">
        <h3>Dépôt de garantie — <?= euros($l['deposit_amount']) ?></h3>
        <?php if ((float) $l['deposit_amount'] <= 0): ?>
            <p class="muted">Aucun dépôt de garantie prévu.</p>
        <?php elseif (!empty($l['deposit_paid_date'])): ?>
            <p><span class="badge badge-paid">✔ Encaissé le <?= fdate($l['deposit_paid_date']) ?></span></p>
            <form method="post" action="<?= url('/baux/'.$l['id'].'/depot') ?>" class="inline-form" onsubmit="return confirm('Annuler l\'encaissement du dépôt ?')">
                <?= csrf_field() ?><input type="hidden" name="action" value="cancel"><button class="btn btn-sm">Annuler</button></form>
        <?php else: ?>
            <p>À verser au plus tard le <strong><?= fdate($depDue) ?></strong> (jour du début du bail)
                <?= $depDue && $depDue < date('Y-m-d') ? ' <span class="badge badge-late">En retard</span>' : ' <span class="badge badge-pending">À recevoir</span>' ?></p>
            <form method="post" action="<?= url('/baux/'.$l['id'].'/depot') ?>" class="actions">
                <?= csrf_field() ?>
                <input type="date" name="paid_date" value="<?= date('Y-m-d') ?>" style="width:auto" required>
                <button class="btn btn-primary">✔ Marquer comme encaissé</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php $gType = $l['guarantee_type'] ?? ($guarants ? 'garant' : 'aucune'); ?>
<div class="card">
    <h3>Garantie</h3>
    <?php if ($gType === 'visale'): ?>
        <p><strong>Visale (Action Logement)</strong>
            — visa n° <?= e($l['visale_visa_number'] ?: '— à renseigner') ?>
            <?php if (!empty($l['visale_visa_expiry'])): ?> · valable jusqu'au <?= fdate($l['visale_visa_expiry']) ?><?php endif; ?>
            <?php if ($l['visale_max_rent'] !== null && $l['visale_max_rent'] !== ''): ?> · loyer max. couvert <?= euros($l['visale_max_rent']) ?><?php endif; ?>
            <br>Contrat de cautionnement n° <?= e($l['visale_contract_number'] ?: '— à signer sur visale.fr') ?></p>
    <?php elseif ($guarants): ?>
        <p><strong>Caution solidaire</strong> — <?= e(implode(' et ', array_map(fn($g) => $g['name'], $guarants))) ?></p>
    <?php else: ?>
        <p class="muted">Aucune garantie. <a href="<?= url('/baux/'.$l['id'].'/edit') ?>">Ajouter un garant ou Visale</a></p>
    <?php endif; ?>
</div>
</section>

<section class="tab-panel" id="loyers">
<div class="card">
    <h3>Ajouter une échéance de loyer</h3>
    <form method="post" action="<?= url('/baux/'.$l['id'].'/echeance') ?>" class="actions">
        <?= csrf_field() ?>
        <select name="month">
            <?php for ($m=1;$m<=12;$m++): ?><option value="<?= $m ?>" <?= $m==(int)date('n')?'selected':'' ?>><?= ucfirst(moisFr($m)) ?></option><?php endfor; ?>
        </select>
        <select name="year">
            <?php $cy=(int)date('Y'); for ($y=$cy-2;$y<=$cy+1;$y++): ?><option <?= $y==$cy?'selected':'' ?>><?= $y ?></option><?php endfor; ?>
        </select>
        <button class="btn btn-primary">Ajouter</button>
        <span class="hint">Astuce : utilisez « Générer les loyers dus » depuis l'onglet Loyers pour créer automatiquement toutes les échéances.</span>
    </form>
</div>

<h2>Historique des loyers</h2>
<?php if (!$payments): ?>
    <div class="card empty">Aucune échéance enregistrée pour ce bail.</div>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Période</th><th class="num">Montant</th><th>Statut</th><th>Payé le</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($payments as $pay): $total=(float)$pay['amount_rent']+(float)$pay['amount_charges']; ?>
        <tr>
            <td><?= ucfirst(moisFr((int)$pay['period_month'])).' '.$pay['period_year'] ?></td>
            <td class="num"><?= euros($total) ?></td>
            <td><span class="badge badge-<?= $pay['status']==='paid'?'paid':'pending' ?>"><?= $pay['status']==='paid'?'Payé':'En attente' ?></span></td>
            <td><?= fdate($pay['paid_date']) ?></td>
            <td class="right actions" style="justify-content:flex-end">
                <?php if ($pay['status']==='paid'): ?>
                    <a href="<?= url('/quittance/'.$pay['id']) ?>" class="btn btn-sm btn-primary" target="_blank">🧾 Quittance</a>
                    <a href="<?= url('/quittance/'.$pay['id']).'#email' ?>" class="btn btn-sm" target="_blank" title="Envoyer la quittance par email"><?= !empty($pay['emailed_at']) ? '✔ ' : '' ?>📧 Email</a>
                    <form class="inline-form" method="post" action="<?= url('/loyers/'.$pay['id'].'/annuler') ?>"><?= csrf_field() ?><button class="btn btn-sm">Annuler</button></form>
                <?php else: ?>
                    <form class="inline-form" method="post" action="<?= url('/loyers/'.$pay['id'].'/paye') ?>"><?= csrf_field() ?><button class="btn btn-sm btn-primary">Marquer payé</button></form>
                <?php endif; ?>
                <a href="<?= url('/loyers/'.$pay['id'].'/modifier') ?>" class="btn btn-sm">✏️ Modifier</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
</section>

<section class="tab-panel" id="signature">
<?php
$sigAll    = LeaseSignature::forLease((int) $l['id']);
$sigValid  = $sigAll ? LeaseSignature::valid($l, $settings) : [];
$sigDone   = count($sigValid) === count(LeaseSignature::ROLES);
$signedPdf = LeaseSignature::signedPdf((int) $l['id']);
$sigLink   = $_SESSION['sign_link_' . (int) $l['id']] ?? null;
$blocked   = $l['lease_type'] === 'meuble' && $issues['blocking'];
?>
<h2>Signature du bail</h2>
<div class="card">
    <?php if ($sigAll && count($sigValid) < count($sigAll)): ?>
        <div class="flash flash-error">Le bail a été modifié après signature : les signatures ne correspondent plus à la version actuelle.
            <?= $signedPdf ? 'Le contrat signé précédent reste archivé.' : '' ?> Faites signer à nouveau.</div>
    <?php endif; ?>
    <?php if ($blocked): ?>
        <p class="small" style="color:#b91c1c">Corrigez d'abord les points bloquants du contrôle ci-dessus pour pouvoir faire signer le bail.</p>
    <?php endif; ?>
    <div class="table-wrap"><table>
        <thead><tr><th>Partie</th><th>État</th><th></th></tr></thead>
        <tbody>
        <?php foreach (LeaseSignature::ROLES as $role => $label): $sg = $sigValid[$role] ?? null; ?>
            <tr>
                <td><?= $label ?></td>
                <td><?php if ($sg): ?>
                        <span class="badge badge-paid">Signé</span> par <?= e($sg['signer_name']) ?> le <?= e(date('d/m/Y à H:i', strtotime($sg['signed_at']))) ?>
                    <?php else: ?><span class="badge badge-pending">À signer</span><?php endif; ?></td>
                <td class="right">
                    <?php if (!$sg && !$blocked): ?>
                        <a class="btn btn-sm btn-primary" href="<?= url('/baux/'.$l['id'].'/signer/'.$role) ?>">
                            <?= $role === 'bailleur' ? '✍️ Signer' : '✍️ Faire signer sur cet appareil' ?></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>

    <?php if (!$sigDone && !isset($sigValid['locataire']) && !$blocked): ?>
        <form method="post" action="<?= url('/baux/'.$l['id'].'/lien-signature') ?>" class="mt">
            <?= csrf_field() ?>
            <p><strong>Faire signer le locataire à distance</strong> <span class="muted small">— il reçoit un lien personnel, lit le bail et signe sur son téléphone ou son ordinateur.</span></p>
            <div class="actions" style="flex-wrap:wrap">
                <input type="email" name="to" value="<?= e($l['email'] ?? '') ?>" placeholder="email du locataire" style="max-width:280px">
                <label class="small" style="font-weight:400"><input type="checkbox" name="copy" value="1" checked style="width:auto"> m'envoyer une copie</label>
                <button class="btn btn-primary" name="send" value="1">📧 Envoyer le lien par email</button>
                <button class="btn" name="send" value="">🔗 Créer le lien seulement</button>
            </div>
        </form>
        <?php if ($sigLink): ?>
            <p class="small mt">Lien de signature (valable <?= LeaseSignature::TOKEN_DAYS ?> jours) :
                <input readonly value="<?= e($sigLink) ?>" onclick="this.select()" style="width:100%"></p>
        <?php endif; ?>
    <?php endif; ?>

    <div class="actions mt">
        <?php if ($signedPdf): ?>
            <a class="btn btn-primary" href="<?= url('/baux/'.$l['id'].'/bail-signe') ?>" target="_blank">📄 Bail signé (PDF archivé le <?= e(date('d/m/Y', strtotime($signedPdf['created_at']))) ?>)</a>
        <?php endif; ?>
        <?php if ($sigAll): ?>
            <form method="post" action="<?= url('/baux/'.$l['id'].'/signatures/reset') ?>" class="inline-form" onsubmit="return confirm('Annuler les signatures pour faire signer à nouveau ?')">
                <?= csrf_field() ?><button class="btn btn-sm">Annuler les signatures</button></form>
        <?php endif; ?>
    </div>
    <p class="small muted mt">Signature électronique simple (art. 1366-1367 du Code civil) : date, heure, adresse IP, navigateur et
        empreinte du contrat sont enregistrés comme preuve. Le PDF signé est archivé dès que les deux parties ont signé.</p>
</div>
</section>

<section class="tab-panel" id="liasse">
<?php
$docIssues = PropertyDocument::issues((int) $l['property_id'], $l['signature_date'] ?: $l['start_date']);
$docs = PropertyDocument::current((int) $l['property_id']);
$guarantsList = Lease::guarantors($l);
?>
<h2>Dossier du locataire (liasse)</h2>
<div class="card">
    <p class="small muted">Tous les documents à remettre au locataire, en un seul envoi :</p>
    <ol class="small">
        <li><?= $l['lease_type'] === 'meuble' ? 'Contrat de location meublée et inventaire du mobilier' : 'Contrat de location' ?>
            — <?= $sigDone ? '<strong>version signée</strong>' : '<em>non signé</em>' ?></li>
        <?php foreach ($guarantsList as $g): ?><li>Acte de cautionnement — <?= e($g['name']) ?></li><?php endforeach; ?>
        <?php foreach ($docs as $d): ?><li><?= e(PropertyDocument::label($d)) ?><?= $d['doc_date'] ? ' (du ' . fdate($d['doc_date']) . ')' : '' ?></li><?php endforeach; ?>
        <li>Bordereau de remise des documents (à faire signer par le locataire)</li>
    </ol>
    <?php if ($docIssues): ?>
        <div class="flash flash-error small"><strong>Documents du logement à compléter :</strong><br><?= implode('<br>', array_map('e', $docIssues)) ?>
            <br><a href="<?= url('/biens/'.$l['property_id'].'#documents') ?>">Ajouter les documents sur la fiche du logement</a></div>
    <?php endif; ?>
    <div class="actions">
        <a class="btn btn-primary" href="<?= url('/baux/'.$l['id'].'/liasse') ?>">⬇️ Télécharger le dossier (ZIP)</a>
    </div>
    <form method="post" action="<?= url('/baux/'.$l['id'].'/liasse/email') ?>" class="mt" onsubmit="var b=this.querySelector('button');b.disabled=true;b.textContent='Envoi en cours…'">
        <?= csrf_field() ?>
        <p><strong>Envoyer le dossier par email</strong></p>
        <div class="form-grid">
            <div class="field"><label>Destinataire</label><input type="email" name="to" required value="<?= e($l['email'] ?? '') ?>"></div>
            <div class="field"><label>Objet</label><input name="subject" required value="Votre dossier de location — <?= e(trim(($l['address'] ?? '') . ' ' . ($l['city'] ?? '')) ?: $l['property_label']) ?>"></div>
        </div>
        <div class="field"><label>Message</label><textarea name="message" rows="5">Bonjour <?= e($l['first_name']) ?>,

Veuillez trouver ci-joint votre dossier de location : le bail et les documents obligatoires relatifs au logement.

Cordialement,
<?= e(trim((string) ($settings['mail_from_name'] ?? '')) ?: ($settings['landlord_name'] ?? '')) ?></textarea></div>
        <label class="small" style="font-weight:400"><input type="checkbox" name="copy" value="1" checked style="width:auto"> M'envoyer une copie</label>
        <div class="mt"><button class="btn">📧 Envoyer le dossier</button></div>
    </form>
</div>
</section>

<?php if ($l['lease_type'] === 'meuble'): ?>
<section class="tab-panel" id="inventaire">
<?php if ($l['lease_type'] === 'meuble'):
    $invRows = Inventory::rows($l);
    [$invDone, $invTotal] = Inventory::progress($invRows); ?>
<h2>Inventaire du mobilier (annexe 1)</h2>
<p class="muted small">Cochez la présence de chaque élément et notez son état (à faire lors de l'état des lieux d'entrée).
    L'annexe 1 du contrat est imprimée avec vos réponses ; les lignes non renseignées restent à cocher à la main.
    — <?= $invDone ?>/<?= $invTotal ?> renseigné(s)</p>
<form method="post" action="<?= url('/baux/'.$l['id'].'/inventaire') ?>" class="card">
    <?= csrf_field() ?>
    <div class="table-wrap"><table>
        <thead><tr><th>Élément</th><th style="white-space:nowrap">Présent</th><th>État / observations</th></tr></thead>
        <tbody>
        <?php foreach ($invRows as $r): $k = e($r['key']); ?>
            <tr>
                <td><?php if ($r['free']): ?>
                        <input name="inv_label[<?= $k ?>]" value="<?= e($r['label']) ?>" placeholder="Autre élément (à préciser)">
                    <?php else: ?><?= e($r['label']) ?><?php endif; ?></td>
                <td style="white-space:nowrap">
                    <?php foreach (['oui' => 'Oui', 'non' => 'Non'] as $v => $lbl): ?>
                        <label style="display:inline-flex;align-items:center;gap:.25rem;margin-right:.6rem;font-weight:400">
                            <input type="radio" name="present[<?= $k ?>]" value="<?= $v ?>" <?= $r['present'] === $v ? 'checked' : '' ?> style="width:auto"> <?= $lbl ?>
                        </label>
                    <?php endforeach; ?>
                    <?php if ($r['present'] !== null): ?>
                        <label style="display:inline-flex;align-items:center;gap:.25rem;font-weight:400" class="muted small">
                            <input type="radio" name="present[<?= $k ?>]" value="" style="width:auto"> effacer
                        </label>
                    <?php endif; ?>
                </td>
                <td><input name="inv_notes[<?= $k ?>]" value="<?= e($r['notes']) ?>" placeholder="ex. bon état, neuf, rayure…"></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <div class="actions mt">
        <button class="btn btn-primary">Enregistrer l'inventaire</button>
        <button type="button" class="btn" onclick="this.form.querySelectorAll('input[type=radio][value=oui]').forEach(function(r){r.checked=true})">Tout cocher « Oui »</button>
    </div>
</form>
<?php endif; ?>
</section>

<?php endif; ?>

<section class="tab-panel" id="checklist">
<h2>Checklist de conformité</h2>
<p class="muted small">Vérifiez que tout est en règle côté bailleur et que le locataire a bien fourni toutes les pièces.</p>

<form method="post" action="<?= url('/baux/'.$l['id'].'/checklist') ?>" class="card">
    <?= csrf_field() ?>
    <div class="checklist-head">
        <div class="progress-bar" title="<?= $done ?>/<?= $total ?>">
            <span style="width:<?= $pct ?>%"></span>
        </div>
        <strong><?= $done ?>/<?= $total ?></strong>
        <?php if ($total > 0 && $done === $total): ?><span class="badge badge-paid">Complet ✓</span><?php endif; ?>
    </div>

    <?php foreach (Checklist::GROUPS as $gkey => $glabel):
        $items = array_filter($applicable, fn($i) => $i['group'] === $gkey);
        if (!$items) continue; ?>
        <h3><?= e($glabel) ?></h3>
        <div class="checklist">
            <?php foreach ($items as $item): ?>
                <label class="check-item">
                    <input type="checkbox" name="items[]" value="<?= e($item['key']) ?>"
                        <?= in_array($item['key'], $checked, true) ? 'checked' : '' ?>>
                    <span><?= e($item['label']) ?></span>
                </label>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <div class="mt"><button class="btn btn-primary">Enregistrer la checklist</button></div>
</form>

<form method="post" action="<?= url('/baux/'.$l['id'].'/delete') ?>" onsubmit="return confirm('Supprimer ce bail et tout son historique ?')" class="mt">
    <?= csrf_field() ?><button class="btn btn-danger btn-sm">Supprimer le bail</button>
</form>
</section>

</div>
