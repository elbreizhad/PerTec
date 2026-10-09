<?php /** @var array $tenants */ /** @var array $leases */ /** @var array $settings */ /** @var int $preview */ /** @var callable $link */
$s = $settings;
$today = date('Y-m-d');
$t0 = $tenants[0] ?? ['first_name' => '', 'last_name' => ''];
?>
<?php if ($preview): ?>
    <div class="flash" style="background:#eff6ff;color:#1e3a8a;border:1px solid #bfdbfe">
        👁 <strong>Aperçu administrateur</strong> — vous voyez l'espace de <?= e($t0['first_name'] . ' ' . $t0['last_name']) ?> tel qu'il le voit.
        <a href="<?= url('/locataires') ?>">← Retour à l'administration</a></div>
<?php endif; ?>

<h1>Bonjour <?= e($t0['first_name']) ?></h1>

<?php if (!$leases): ?>
    <div class="card empty">Aucun bail n'est associé à votre compte pour le moment.</div>
<?php endif; ?>

<?php foreach ($leases as $l):
    $payments = Payment::forLease((int) $l['id']);
    $docs = PropertyDocument::current((int) $l['property_id']);
    $signed = count(LeaseSignature::valid($l, $s)) === count(LeaseSignature::ROLES);
    $adresse = trim(($l['address'] ?? '') . ', ' . ($l['postal_code'] ?? '') . ' ' . ($l['city'] ?? ''), ', ');
    $late = array_filter($payments, fn($p) => $p['status'] !== 'paid' && $p['due_date'] && $p['due_date'] < $today);
    $active = $l['status'] === 'active';
?>
<section class="card portal-lease">
    <div class="page-head" style="margin-bottom:.6rem">
        <div>
            <h2 style="margin:0"><?= e($adresse ?: $l['property_label']) ?></h2>
            <p class="muted small" style="margin:.2rem 0 0">
                <?= $l['lease_type'] === 'meuble' ? 'Location meublée' : 'Location vide' ?>
                · <?= $l['start_date'] > $today ? 'à partir du' : 'depuis le' ?> <?= fdate($l['start_date']) ?>
                <?php $te = Lease::currentTermEnd($l); if (!empty($l['auto_renew']) && $active): ?>· renouvelable automatiquement<?= $te ? ' (' . ($l['start_date'] > $today ? 'première période' : 'période en cours') . ' jusqu\'au ' . fdate($te) . ')' : '' ?>
                <?php elseif ($l['end_date']): ?>· jusqu'au <?= fdate($l['end_date']) ?><?php endif; ?>
                <?php $ph = Lease::phase($l); ?>· <span class="badge badge-<?= ['en_cours' => 'paid', 'a_venir' => 'pending', 'termine' => 'pending'][$ph['key']] ?>"><?= e(['en_cours' => 'Bail en cours', 'a_venir' => 'Bail à venir — débute le ' . fdate($l['start_date']), 'termine' => 'Bail terminé'][$ph['key']]) ?></span></p>
        </div>
        <a class="btn btn-primary" href="<?= $link('/locataire/bail/' . $l['id']) ?>" target="_blank">📄 <?= $signed ? 'Mon bail signé' : 'Mon bail' ?> (PDF)</a>
    </div>

    <div class="grid grid-4 mb">
        <div class="stat"><div class="label">Loyer hors charges</div><div class="value"><?= euros($l['rent_amount']) ?></div></div>
        <div class="stat"><div class="label">Charges<?= ($l['charge_type'] ?? '') === 'forfait' ? ' (forfait)' : ' (provisions)' ?></div><div class="value"><?= euros($l['charges_amount']) ?></div></div>
        <div class="stat"><div class="label">Total mensuel</div><div class="value"><?= euros((float) $l['rent_amount'] + (float) $l['charges_amount']) ?></div></div>
        <div class="stat"><div class="label">Situation</div><div class="value <?= $late ? 'neg' : 'pos' ?>" style="font-size:1.05rem"><?= $late ? count($late) . ' échéance(s) en retard' : 'À jour' ?></div></div>
    </div>

    <?php if ((float) $l['deposit_amount'] > 0): $dd = Lease::depositDue($l); ?>
        <p><strong>Dépôt de garantie : <?= euros($l['deposit_amount']) ?></strong> —
            <?php if (!empty($l['deposit_paid_date'])): ?><span class="badge badge-paid">Reçu le <?= fdate($l['deposit_paid_date']) ?></span>
            <?php else: ?>à verser au plus tard le <?= fdate($dd) ?>
                (jour du début du bail) <?= $dd && $dd < $today ? '<span class="badge badge-late">En attente</span>' : '<span class="badge badge-pending">À verser</span>' ?><?php endif; ?></p>
    <?php endif; ?>

    <h3>Mes loyers et quittances</h3>
    <?php if (!$payments): ?>
        <p class="muted">Aucune échéance pour le moment.</p>
    <?php else: ?>
    <div class="table-wrap"><table class="portal-pay">
        <thead><tr><th>Période</th><th class="num">Montant</th><th>Échéance</th><th>Statut</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($payments as $p):
            $paid = $p['status'] === 'paid';
            $isLate = !$paid && $p['due_date'] && $p['due_date'] < $today; ?>
            <tr>
                <td class="pp-period"><?= ucfirst(moisFr((int) $p['period_month'])) . ' ' . $p['period_year'] ?></td>
                <td class="num pp-amount"><?= euros((float) $p['amount_rent'] + (float) $p['amount_charges']) ?></td>
                <td class="pp-due"><span class="pp-lbl">Échéance : </span><?= fdate($p['due_date']) ?></td>
                <td class="pp-status"><?php if ($paid): ?><span class="badge badge-paid">Payé le <?= fdate($p['paid_date']) ?></span>
                    <?php elseif ($isLate): ?><span class="badge badge-late">En retard</span>
                    <?php else: ?><span class="badge badge-pending">En attente de paiement</span><?php endif; ?></td>
                <td class="right pp-action"><?php if ($paid): ?><a class="btn btn-sm" href="<?= $link('/locataire/quittance/' . $p['id']) ?>" target="_blank">🧾 Quittance</a><?php endif; ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
    <?php endif; ?>

    <h3 class="mt">Documents du logement</h3>
    <?php if (!$docs): ?>
        <p class="muted">Aucun document en ligne pour le moment.</p>
    <?php else: ?>
        <ul class="portal-docs">
        <?php foreach ($docs as $d): ?>
            <li><a href="<?= $link('/locataire/document/' . $d['id']) ?>" target="_blank">📎 <?= e(PropertyDocument::label($d)) ?></a>
                <?= $d['doc_date'] ? '<span class="muted small"> — du ' . fdate($d['doc_date']) . '</span>' : '' ?></li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
<?php endforeach; ?>

<section class="card">
    <h3>Votre bailleur</h3>
    <p><?= e($s['landlord_name'] ?? '') ?><br>
        <?= nl2br(e($s['landlord_address'] ?? '')) ?><?= !empty($s['landlord_city']) ? '<br>' . e($s['landlord_city']) : '' ?>
        <?php if (!empty($s['landlord_email'])): ?><br>✉️ <a href="mailto:<?= e($s['landlord_email']) ?>"><?= e($s['landlord_email']) ?></a><?php endif; ?>
        <?php if (!empty($s['landlord_phone'])): ?><br>📞 <a href="tel:<?= e(preg_replace('/\s+/', '', $s['landlord_phone'])) ?>"><?= e($s['landlord_phone']) ?></a><?php endif; ?></p>
</section>
