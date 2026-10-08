<?php /** @var int $year */ /** @var array $rows */ /** @var array $totals */
$cy = (int) date('Y'); $t = $totals;
$money = fn($v) => '<span class="' . ($v < 0 ? 'neg' : '') . '">' . euros($v) . '</span>';
?>
<div class="page-head">
    <div>
        <h1>Bilan annuel <?= $year ?></h1>
        <p class="muted">Encaissements, charges réelles, résultat et cash-flow de chaque bien.</p>
    </div>
    <form method="get" action="<?= url('/bilan') ?>" class="actions">
        <select name="year" onchange="this.form.submit()">
            <?php for ($y = $cy + 1; $y >= $cy - 6; $y--): ?><option <?= $y === $year ? 'selected' : '' ?>><?= $y ?></option><?php endfor; ?>
        </select>
        <button type="button" class="btn" onclick="window.print()">🖨️ Imprimer</button>
    </form>
</div>

<div class="grid grid-4 mb">
    <div class="stat"><div class="label">Encaissé (loyers + charges)</div><div class="value"><?= euros($t['encaissements']) ?></div></div>
    <div class="stat"><div class="label">Charges et impôts</div><div class="value"><?= euros($t['charges_total']) ?></div></div>
    <div class="stat"><div class="label">Résultat net</div><div class="value <?= $t['resultat'] >= 0 ? 'pos' : 'neg' ?>"><?= euros($t['resultat']) ?></div></div>
    <div class="stat"><div class="label">Cash-flow</div><div class="value <?= $t['cashflow'] >= 0 ? 'pos' : 'neg' ?>"><?= euros($t['cashflow']) ?></div></div>
</div>

<?php if (!$rows): ?>
    <div class="card empty">Aucun bien enregistré.</div>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Bien</th><th class="num">Encaissé</th><th class="num">Charges et impôts</th><th class="num">Résultat net</th>
        <th class="num">Capital remb.</th><th class="num">Invest.</th><th class="num">Cash-flow</th><th class="num">Régul. locataire</th></tr></thead>
    <tbody>
    <?php foreach ($rows as ['p' => $p, 'b' => $b]): ?>
        <tr>
            <td><a href="<?= url('/bilan/'.$p['id'].'?year='.$year) ?>"><?= e($p['label']) ?></a>
                <?= $b['warnings'] ? ' <span class="badge badge-pending" title="' . e(implode("\n", $b['warnings'])) . '">estimations</span>' : '' ?></td>
            <td class="num"><?= euros($b['encaissements']) ?></td>
            <td class="num"><?= euros($b['charges_total']) ?></td>
            <td class="num"><strong><?= $money($b['resultat']) ?></strong></td>
            <td class="num"><?= euros($b['capital']) ?></td>
            <td class="num"><?= euros($b['investissements']) ?></td>
            <td class="num"><strong><?= $money($b['cashflow']) ?></strong></td>
            <td class="num"><?php if (!$b['regul']['applicable']): ?><span class="muted small"><?= $b['regul']['forfait'] ? 'forfait' : '—' ?></span>
                <?php else: $sd = $b['regul']['solde']; ?><?= euros(abs($sd)) ?><br><span class="small muted"><?= $sd >= 0 ? 'à réclamer' : 'à rembourser' ?></span><?php endif; ?></td>
        </tr>
    <?php endforeach; ?>
        <tr class="total">
            <td><strong>Total</strong></td>
            <td class="num"><strong><?= euros($t['encaissements']) ?></strong></td>
            <td class="num"><strong><?= euros($t['charges_total']) ?></strong></td>
            <td class="num"><strong><?= $money($t['resultat']) ?></strong></td>
            <td class="num"><strong><?= euros($t['capital']) ?></strong></td>
            <td class="num"><strong><?= euros($t['investissements']) ?></strong></td>
            <td class="num"><strong><?= $money($t['cashflow']) ?></strong></td>
            <td class="num"></td>
        </tr>
    </tbody>
</table></div>
<p class="small muted">Résultat net = encaissements − charges, impôts, frais de gestion et intérêts (avant amortissements et impôt sur le revenu).
    Cash-flow = résultat net − capital d'emprunt remboursé − investissements de l'année.
    Pour le résultat fiscal, voir <a href="<?= url('/fiscalite?year='.$year) ?>">Fiscalité LMNP</a>.</p>
<?php endif; ?>
