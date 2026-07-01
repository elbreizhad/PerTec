<?php /** @var array $rows */ /** @var int $year */
$tot = ['recettes'=>0.0,'charges'=>0.0,'amort'=>0.0,'reel'=>0.0,'micro'=>0.0];
foreach ($rows as $r) {
    $tot['recettes'] += $r['lmnp']['recettes'];
    $tot['charges']  += $r['lmnp']['charges_total'];
    $tot['amort']    += $r['lmnp']['amort_used'];
    $tot['reel']     += max(0.0, $r['lmnp']['result_reel']);
    $tot['micro']    += $r['lmnp']['micro_base'];
}
?>
<div class="page-head">
    <h1>Fiscalité LMNP <?= $year ?></h1>
    <form method="get" action="<?= url('/fiscalite') ?>" class="actions">
        <select name="year" onchange="this.form.submit()">
            <?php $cy=(int)date('Y'); for ($y=$cy+1;$y>=$cy-6;$y--): ?><option <?= $y===$year?'selected':'' ?>><?= $y ?></option><?php endfor; ?>
        </select>
    </form>
</div>

<div class="grid grid-4 mb">
    <div class="stat"><div class="label">Recettes encaissées</div><div class="value"><?= euros($tot['recettes']) ?></div></div>
    <div class="stat"><div class="label">Charges déductibles</div><div class="value"><?= euros($tot['charges']) ?></div></div>
    <div class="stat"><div class="label">Base imposable (réel)</div><div class="value"><?= euros($tot['reel']) ?></div></div>
    <div class="stat"><div class="label">Base imposable (micro)</div><div class="value"><?= euros($tot['micro']) ?></div></div>
</div>

<?php if (!$rows): ?>
    <div class="card empty">Aucun bien. <a href="<?= url('/biens/new') ?>">Ajoutez un bien</a>.</div>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr>
        <th>Bien</th><th class="num">Recettes</th><th class="num">Charges</th><th class="num">Amort.</th>
        <th class="num">Réel</th><th class="num">Micro-BIC</th><th>Meilleur</th><th></th>
    </tr></thead>
    <tbody>
    <?php foreach ($rows as $r): $p=$r['p']; $f=$r['lmnp']; ?>
        <tr>
            <td><a href="<?= url('/biens/'.$p['id'].'/fiscalite?year='.$year) ?>"><strong><?= e($p['label']) ?></strong></a></td>
            <td class="num"><?= euros($f['recettes']) ?></td>
            <td class="num"><?= euros($f['charges_total']) ?></td>
            <td class="num"><?= euros($f['amort_used']) ?></td>
            <td class="num"><?= euros(max(0.0,$f['result_reel'])) ?></td>
            <td class="num"><?= euros($f['micro_base']) ?></td>
            <td><span class="badge badge-active"><?= $f['best']==='reel'?'Réel':'Micro' ?></span></td>
            <td class="right"><a href="<?= url('/biens/'.$p['id'].'/fiscalite?year='.$year) ?>" class="btn btn-sm">Détail</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>

<p class="small muted mt">Aide au calcul simplifiée (base de caisse, amortissement linéaire, abattement micro-BIC 50 %).
Ne remplace pas un expert-comptable.</p>
