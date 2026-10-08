<?php /** @var ?array $property */ /** @var array $h */ /** @var array $rows */ /** @var ?array $per */ /** @var ?array $r */
$p = $property;
$action = $p ? url('/projection/' . $p['id']) : url('/projection');
$money = fn($v) => '<span class="' . ($v < 0 ? 'neg' : '') . '">' . euros($v) . '</span>';
$last = $rows ? end($rows) : null;
// Synthèse (un bien : calculée ; tous les biens : à partir des sommes)
if ($r) {
    $cards = ['brute' => $r['brute'], 'nette' => $r['nette'], 'cf' => $r['cf_mensuel'], 'loan_end' => $r['loan_end'], 'patrimoine' => $r['patrimoine'], 'cumul' => $r['cumul']];
} else {
    $cost = 0.0; $rent = 0.0;
    foreach ($per as $x) { $cost += $x['r']['cost']; $rent += $x['r']['rent']; }
    $first = $rows[0] ?? ['loyers' => 0, 'charges' => 0, 'cashflow' => 0];
    $cards = ['brute' => $cost > 0 ? $rent * 12 / $cost * 100 : 0, 'nette' => $cost > 0 ? ($first['loyers'] - $first['charges']) / $cost * 100 : 0,
              'cf' => $first['cashflow'] / 12, 'loan_end' => null, 'patrimoine' => $last['patrimoine'] ?? 0, 'cumul' => $last['cumul'] ?? 0];
}
$pct = fn($v) => number_format($v, 2, ',', ' ') . ' %';
?>
<div class="page-head">
    <div>
        <h1>Projection<?= $p ? ' — ' . e($p['label']) : ' — tous les biens' ?></h1>
        <p class="muted"><?php if ($p): ?><a href="<?= url('/biens/'.$p['id']) ?>">← Fiche du bien</a> · <a href="<?= url('/projection') ?>">Tous les biens</a><?php else: ?>Somme de tous les biens<?php endif; ?>
            · simulation sur <?= $h['annees'] ?> ans à partir de <?= (int) date('Y') + 1 ?>, avant impôt sur le revenu</p>
    </div>
    <button type="button" class="btn no-print" onclick="window.print()">🖨️ Imprimer</button>
</div>

<form method="get" action="<?= $action ?>" class="card no-print">
    <h3>Hypothèses</h3>
    <div class="form-grid">
        <div class="field"><label>Durée (années)</label><input type="number" name="annees" min="1" max="40" value="<?= $h['annees'] ?>"></div>
        <div class="field"><label>Hausse des loyers / an (IRL, %)</label><input name="loyer" inputmode="decimal" value="<?= e((string) $h['loyer']) ?>"></div>
        <div class="field"><label>Hausse des charges / an (%)</label><input name="charges" inputmode="decimal" value="<?= e((string) $h['charges']) ?>"></div>
        <div class="field"><label>Vacance (mois sans locataire / an)</label><input name="vacance" inputmode="decimal" value="<?= e((string) $h['vacance']) ?>"></div>
        <div class="field"><label>Évolution de la valeur du bien / an (%)</label><input name="valeur" inputmode="decimal" value="<?= e((string) $h['valeur']) ?>"></div>
    </div>
    <div class="actions"><button class="btn btn-primary">Recalculer</button>
        <a class="btn" href="<?= $action ?>">Valeurs par défaut</a></div>
    <p class="hint">Bases : loyer hors charges du bail en cours, charges annuelles de la fiche du bien (taxe foncière, assurance PNO,
        charges de copropriété non récupérables, gestion, comptable) et tableau d'amortissement du prêt.
        Les charges récupérables sont payées par le locataire et n'entrent pas dans le calcul.</p>
</form>

<div class="grid grid-4 mb">
    <div class="stat"><div class="label">Rentabilité brute</div><div class="value"><?= $pct($cards['brute']) ?></div></div>
    <div class="stat"><div class="label">Rentabilité nette (année 1)</div><div class="value"><?= $pct($cards['nette']) ?></div></div>
    <div class="stat"><div class="label">Cash-flow / mois (année 1)</div><div class="value <?= $cards['cf'] >= 0 ? 'pos' : 'neg' ?>"><?= euros($cards['cf']) ?></div></div>
    <div class="stat"><div class="label">Patrimoine net en <?= $last['year'] ?? '' ?></div><div class="value pos"><?= euros($cards['patrimoine']) ?></div></div>
</div>
<p class="muted small mb">
    Cash-flow cumulé sur <?= $h['annees'] ?> ans : <strong><?= $money($cards['cumul']) ?></strong>
    <?php if ($r && $r['loan_end']): ?> · prêt remboursé en <strong><?= $r['loan_end'] ?></strong><?php endif; ?>
    <?php if ($r): ?> · apport (coût total − emprunt) : <?= euros($r['apport']) ?><?php endif; ?>
</p>

<?php if (!$rows): ?>
    <div class="card empty">Aucun bien à projeter.</div>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr>
        <th>Année</th><th class="num">Loyers</th><th class="num">Charges</th><th class="num">Intérêts + assur.</th><th class="num">Capital remb.</th>
        <th class="num">Cash-flow</th><th class="num">/ mois</th><th class="num">Cumul</th><th class="num">Capital restant dû</th>
        <th class="num">Valeur du bien</th><th class="num">Patrimoine net</th><?php if ($r): ?><th class="num">Renta nette</th><?php endif; ?>
    </tr></thead>
    <tbody>
    <?php foreach ($rows as $row): $end = $r && $r['loan_end'] === $row['year']; ?>
        <tr<?= $end ? ' style="background:#ecfdf5"' : '' ?>>
            <td><strong><?= $row['year'] ?></strong><?= $end ? '<br><span class="small">fin du prêt</span>' : '' ?></td>
            <td class="num"><?= euros($row['loyers']) ?></td>
            <td class="num"><?= euros($row['charges']) ?></td>
            <td class="num"><?= euros($row['interets']) ?></td>
            <td class="num"><?= euros($row['capital']) ?></td>
            <td class="num"><strong><?= $money($row['cashflow']) ?></strong></td>
            <td class="num"><?= $money($row['cashflow'] / 12) ?></td>
            <td class="num"><?= $money($row['cumul']) ?></td>
            <td class="num"><?= euros($row['crd']) ?></td>
            <td class="num"><?= euros($row['valeur']) ?></td>
            <td class="num"><strong><?= euros($row['patrimoine']) ?></strong></td>
            <?php if ($r): ?><td class="num"><?= $pct($row['renta_nette']) ?></td><?php endif; ?>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php if ($per): ?>
    <p class="small muted">Détail par bien :
        <?php foreach ($per as $x): ?><a href="<?= url('/projection/'.$x['p']['id']) ?>?<?= e(http_build_query($h)) ?>"><?= e($x['p']['label']) ?></a> · <?php endforeach; ?></p>
<?php endif; ?>
<p class="small muted">Cash-flow = loyers − charges − mensualités du prêt. Patrimoine net = valeur estimée du bien (prix d'achat revalorisé) − capital
    restant dû. Simulation indicative, avant impôt sur le revenu (voir Fiscalité LMNP pour l'imposition) : ne remplace pas un conseil financier.</p>
<?php endif; ?>
