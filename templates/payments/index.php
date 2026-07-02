<?php /** @var array $payments */ /** @var int $year */ /** @var array $stats */ ?>
<div class="page-head">
    <h1>Loyers <?= $year ?></h1>
    <div class="actions">
        <form method="get" action="<?= url('/loyers') ?>" class="inline-form">
            <select name="year" onchange="this.form.submit()">
                <?php $cy=(int)date('Y'); for ($y=$cy+1;$y>=$cy-4;$y--): ?><option <?= $y===$year?'selected':'' ?>><?= $y ?></option><?php endfor; ?>
            </select>
        </form>
        <form method="post" action="<?= url('/loyers/generer') ?>" class="inline-form">
            <?= csrf_field() ?><button class="btn btn-primary">⚙️ Générer les loyers dus</button>
        </form>
    </div>
</div>

<div class="grid grid-3 mb">
    <div class="stat"><div class="label">Total dû <?= $year ?></div><div class="value"><?= euros($stats['due']) ?></div></div>
    <div class="stat"><div class="label">Encaissé</div><div class="value pos"><?= euros($stats['paid']) ?></div></div>
    <div class="stat"><div class="label">Réglés</div><div class="value"><?= (int)$stats['nb_paid'] ?>/<?= (int)$stats['nb_total'] ?></div></div>
</div>

<?php if (!$payments): ?>
    <div class="card empty">Aucune échéance pour <?= $year ?>. Cliquez sur « Générer les loyers dus ».</div>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Période</th><th>Bien</th><th>Locataire</th><th class="num">Montant</th><th>Échéance</th><th>Statut</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($payments as $p): $total=(float)$p['amount_rent']+(float)$p['amount_charges'];
        $late = $p['status']!=='paid' && $p['due_date'] && strtotime($p['due_date']) < strtotime('today');
        $badge = $p['status']==='paid'?'paid':($late?'late':'pending');
        $lbl = $p['status']==='paid'?'Payé':($late?'En retard':'En attente'); ?>
        <tr>
            <td><?= ucfirst(moisFr((int)$p['period_month'])).' '.$p['period_year'] ?></td>
            <td><?= e($p['property_label']) ?></td>
            <td><?= e($p['first_name'].' '.$p['last_name']) ?></td>
            <td class="num"><?= euros($total) ?></td>
            <td><?= fdate($p['due_date']) ?></td>
            <td><span class="badge badge-<?= $badge ?>"><?= $lbl ?></span></td>
            <td class="right actions" style="justify-content:flex-end">
                <?php if ($p['status']==='paid'): ?>
                    <a href="<?= url('/quittance/'.$p['id']) ?>" class="btn btn-sm btn-primary" target="_blank">🧾</a>
                    <form class="inline-form" method="post" action="<?= url('/loyers/'.$p['id'].'/annuler') ?>"><?= csrf_field() ?><button class="btn btn-sm">↩︎</button></form>
                <?php else: ?>
                    <form class="inline-form" method="post" action="<?= url('/loyers/'.$p['id'].'/paye') ?>"><?= csrf_field() ?><button class="btn btn-sm btn-primary">Payé</button></form>
                <?php endif; ?>
                <form class="inline-form" method="post" action="<?= url('/loyers/'.$p['id'].'/delete') ?>" onsubmit="return confirm('Supprimer cette échéance ?')"><?= csrf_field() ?><button class="btn btn-sm btn-danger">×</button></form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
