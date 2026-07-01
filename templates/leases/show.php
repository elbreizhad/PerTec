<?php /** @var array $lease */ /** @var array $payments */
$l = $lease; ?>
<div class="page-head">
    <div>
        <h1>Bail — <?= e($l['first_name'].' '.$l['last_name']) ?></h1>
        <p class="muted"><a href="<?= url('/biens/'.$l['property_id']) ?>"><?= e($l['property_label']) ?></a>
            · <span class="badge badge-<?= e($l['status']) ?>"><?= $l['status']==='active'?'Actif':'Terminé' ?></span></p>
    </div>
    <div class="actions">
        <a href="<?= url('/contrat/'.$l['id']) ?>" class="btn btn-secondary" target="_blank">📄 <?= $l['lease_type']==='meuble' ? 'Contrat de bail meublé (LMNP)' : 'Contrat de bail' ?></a>
        <a href="<?= url('/baux/'.$l['id'].'/edit') ?>" class="btn">Modifier</a>
    </div>
</div>

<div class="grid grid-4 mb">
    <div class="stat"><div class="label">Loyer HC</div><div class="value"><?= euros($l['rent_amount']) ?></div></div>
    <div class="stat"><div class="label">Charges</div><div class="value"><?= euros($l['charges_amount']) ?></div></div>
    <div class="stat"><div class="label">Loyer CC</div><div class="value"><?= euros((float)$l['rent_amount']+(float)$l['charges_amount']) ?></div></div>
    <div class="stat"><div class="label">Dépôt garantie</div><div class="value"><?= euros($l['deposit_amount']) ?></div></div>
</div>

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
                    <form class="inline-form" method="post" action="<?= url('/loyers/'.$pay['id'].'/annuler') ?>"><?= csrf_field() ?><button class="btn btn-sm">Annuler</button></form>
                <?php else: ?>
                    <form class="inline-form" method="post" action="<?= url('/loyers/'.$pay['id'].'/paye') ?>"><?= csrf_field() ?><button class="btn btn-sm btn-primary">Marquer payé</button></form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>

<form method="post" action="<?= url('/baux/'.$l['id'].'/delete') ?>" onsubmit="return confirm('Supprimer ce bail et tout son historique ?')" class="mt">
    <?= csrf_field() ?><button class="btn btn-danger btn-sm">Supprimer le bail</button>
</form>
