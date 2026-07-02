<?php /** @var array $rows */ /** @var array $totals */ /** @var array $stats */ ?>
<div class="page-head">
    <h1>Tableau de bord</h1>
    <div class="actions">
        <a href="<?= url('/biens/new') ?>" class="btn btn-primary">+ Nouveau bien</a>
    </div>
</div>

<div class="grid grid-4 mb">
    <div class="stat stat-featured"><div class="label">Loyers / mois</div><div class="value"><?= euros($totals['rent_month']) ?></div></div>
    <div class="stat"><div class="label">Biens</div><div class="value"><?= count($rows) ?></div></div>
    <div class="stat"><div class="label">Baux actifs</div><div class="value"><?= (int) $nbLeases ?></div></div>
    <div class="stat"><div class="label">Investi (coût total)</div><div class="value"><?= euros($totals['cost']) ?></div></div>
</div>

<div class="grid grid-3 mb">
    <div class="stat">
        <div class="label">Cash-flow mensuel estimé</div>
        <div class="value <?= $totals['cashflow'] >= 0 ? 'pos' : 'neg' ?>"><?= euros($totals['cashflow']) ?></div>
    </div>
    <div class="stat">
        <div class="label">Encaissé <?= $year ?></div>
        <div class="value"><?= euros($stats['paid']) ?></div>
        <div class="small muted">sur <?= euros($stats['due']) ?> dus</div>
    </div>
    <div class="stat">
        <div class="label">Loyers réglés <?= $year ?></div>
        <div class="value"><?= (int)$stats['nb_paid'] ?>/<?= (int)$stats['nb_total'] ?></div>
    </div>
</div>

<?php $maxM = max(1, max($monthly)); ?>
<div class="chart-card">
    <div class="page-head" style="margin-bottom:.2rem">
        <h3 style="margin:0">Loyers encaissés — <?= $year ?></h3>
        <span class="muted small">Total : <?= euros(array_sum($monthly)) ?></span>
    </div>
    <div class="chart">
        <?php foreach ($monthly as $m => $val): ?>
            <div class="col" title="<?= e(ucfirst(moisFr((int)$m))) ?> : <?= euros($val) ?>">
                <div class="bar" style="height:<?= $val > 0 ? max(3, round($val / $maxM * 100)) : 0 ?>%"></div>
                <span class="m"><?= mb_substr(moisFr((int)$m), 0, 3) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<h2>Mes biens</h2>
<?php if (!$rows): ?>
    <div class="card empty">Aucun bien pour l'instant. <a href="<?= url('/biens/new') ?>">Ajoutez votre premier bien</a>.</div>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr>
        <th>Bien</th><th class="num">Coût total</th><th class="num">Loyer/mois</th>
        <th class="num">Rentab. brute</th><th class="num">Rentab. nette</th><th class="num">Cash-flow</th>
    </tr></thead>
    <tbody>
    <?php foreach ($rows as $r): $p=$r['p']; $i=$r['ind']; ?>
        <tr>
            <td><a href="<?= url('/biens/'.$p['id']) ?>"><strong><?= e($p['label']) ?></strong></a><br><span class="small muted"><?= e($p['city'] ?: '') ?></span></td>
            <td class="num"><?= euros($i['total_cost']) ?></td>
            <td class="num"><?= euros($i['monthly_rent']) ?></td>
            <td class="num"><?= number_format($i['gross_yield'],2,',',' ') ?> %</td>
            <td class="num"><?= number_format($i['net_yield'],2,',',' ') ?> %</td>
            <td class="num <?= $i['cashflow_monthly']>=0?'':'' ?>" style="color:<?= $i['cashflow_monthly']>=0?'var(--green)':'var(--red)' ?>"><?= euros($i['cashflow_monthly']) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
