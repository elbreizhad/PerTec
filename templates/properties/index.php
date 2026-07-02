<?php /** @var array $properties */ ?>
<div class="page-head">
    <h1>Biens immobiliers</h1>
    <a href="<?= url('/biens/new') ?>" class="btn btn-primary">+ Nouveau bien</a>
</div>

<?php if (!$properties): ?>
    <div class="card empty">Aucun bien enregistré.</div>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Bien</th><th>Type</th><th>Ville</th><th class="num">Coût total</th><th class="num">Loyer/mois</th><th class="num">Rentab. nette</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($properties as $p): $i=$p['_ind']; ?>
        <tr>
            <td><a href="<?= url('/biens/'.$p['id']) ?>"><strong><?= e($p['label']) ?></strong></a></td>
            <td><?= e($p['type']) ?></td>
            <td><?= e($p['city'] ?: '—') ?></td>
            <td class="num"><?= euros($i['total_cost']) ?></td>
            <td class="num"><?= euros($i['monthly_rent']) ?></td>
            <td class="num"><?= number_format($i['net_yield'],2,',',' ') ?> %</td>
            <td class="right"><a href="<?= url('/biens/'.$p['id'].'/edit') ?>" class="btn btn-sm">Modifier</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
