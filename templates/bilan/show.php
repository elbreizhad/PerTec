<?php /** @var array $property */ /** @var int $year */ /** @var array $b */ /** @var array $costs */
$p = $property; $cy = (int) date('Y'); $r = $b['regul'];
$money = fn($v) => '<span class="' . ($v < 0 ? 'neg' : '') . '">' . euros($v) . '</span>';
?>
<div class="page-head">
    <div>
        <h1>Bilan <?= $year ?> — <?= e($p['label']) ?></h1>
        <p class="muted"><a href="<?= url('/bilan?year='.$year) ?>">← Tous les biens</a> · <a href="<?= url('/biens/'.$p['id'].'?annee='.$year.'#charges') ?>">Saisir les charges réelles</a></p>
    </div>
    <form method="get" action="<?= url('/bilan/'.$p['id']) ?>" class="actions">
        <select name="year" onchange="this.form.submit()">
            <?php for ($y = $cy + 1; $y >= $cy - 6; $y--): ?><option <?= $y === $year ? 'selected' : '' ?>><?= $y ?></option><?php endfor; ?>
        </select>
        <button type="button" class="btn" onclick="window.print()">🖨️ Imprimer</button>
    </form>
</div>

<?php $per = $b['period']; if ($per['from']): ?>
    <p class="muted">Période : du <?= fdate($per['from']) ?> au <?= fdate($per['to']) ?>
        <?= $per['to_date'] ? '— <strong>année en cours, montants à date</strong> (les loyers et échéances à venir ne sont pas comptés)' : '' ?></p>
<?php else: ?>
    <div class="card empty">Ce bien n'était pas encore détenu en <?= $year ?>.</div>
<?php endif; ?>
<?php if ($b['warnings']): ?>
    <div class="flash flash-error small"><?= implode('<br>', array_map('e', $b['warnings'])) ?></div>
<?php endif; ?>

<div class="grid grid-4 mb">
    <div class="stat"><div class="label">Encaissé</div><div class="value"><?= euros($b['encaissements']) ?></div></div>
    <div class="stat"><div class="label">Charges et impôts</div><div class="value"><?= euros($b['charges_total']) ?></div></div>
    <div class="stat"><div class="label">Résultat net</div><div class="value <?= $b['resultat'] >= 0 ? 'pos' : 'neg' ?>"><?= euros($b['resultat']) ?></div></div>
    <div class="stat"><div class="label">Cash-flow</div><div class="value <?= $b['cashflow'] >= 0 ? 'pos' : 'neg' ?>"><?= euros($b['cashflow']) ?></div></div>
</div>

<div class="grid grid-2col">
<div class="card">
    <h3>Compte de l'année</h3>
    <div class="table-wrap"><table>
        <tbody>
            <tr><td>Loyers hors charges encaissés</td><td class="num"><?= euros($b['loyers']) ?></td></tr>
            <tr><td>Provisions pour charges encaissées</td><td class="num"><?= euros($b['provisions_cash']) ?></td></tr>
            <tr class="total"><td><strong>Total encaissé</strong></td><td class="num"><strong><?= euros($b['encaissements']) ?></strong></td></tr>
            <?php foreach ($b['charges'] as $c): if ((float) $c['amount'] == 0.0) continue; ?>
                <tr><td>− <?= e($c['label']) ?><?= $c['estimated'] ? ' <span class="badge badge-pending">estimé</span>' : '' ?></td><td class="num"><?= euros($c['amount']) ?></td></tr>
            <?php endforeach; ?>
            <tr class="total"><td><strong>Résultat net</strong> <span class="small muted">(avant amortissements et impôt)</span></td><td class="num"><strong><?= $money($b['resultat']) ?></strong></td></tr>
            <tr><td>− Capital d'emprunt remboursé</td><td class="num"><?= euros($b['capital']) ?></td></tr>
            <tr><td>− Investissements (travaux, mobilier, équipement)</td><td class="num"><?= euros($b['investissements']) ?></td></tr>
            <tr class="total"><td><strong>Cash-flow</strong></td><td class="num"><strong><?= $money($b['cashflow']) ?></strong></td></tr>
        </tbody>
    </table></div>
    <p class="small muted">Mensualités d'emprunt payées sur l'année : <?= euros($b['mensualites']) ?> (dont intérêts <?= euros($b['charges']['interets']['amount']) ?>).</p>
</div>

<div class="card">
    <h3>Régularisation des charges du locataire</h3>
    <?php if ($r['forfait']): ?>
        <p class="muted">Charges au forfait : pas de régularisation.</p>
    <?php elseif (!$r['applicable']): ?>
        <p class="muted">Aucun loyer encaissé pour <?= $year ?> : pas de régularisation.</p>
    <?php else: ?>
        <div class="table-wrap"><table><tbody>
            <tr><td>Charges récupérables réelles <span class="small muted">(copropriété, TEOM, autres)</span></td><td class="num"><?= euros($r['recuperable']) ?></td></tr>
            <tr><td>− Provisions versées par le locataire pour <?= $year ?></td><td class="num"><?= euros($r['provisions']) ?></td></tr>
            <tr class="total"><td><strong><?= $r['solde'] >= 0 ? 'Complément à réclamer au locataire' : 'Trop-perçu à rembourser au locataire' ?></strong></td>
                <td class="num"><strong><?= euros(abs($r['solde'])) ?></strong></td></tr>
        </tbody></table></div>
        <p class="small muted">La régularisation se fait une fois par an, avec le décompte par nature de charges, un mois avant
            (art. 23 de la loi du 6 juillet 1989). Les justificatifs sont tenus à disposition du locataire pendant six mois.</p>
    <?php endif; ?>
</div>
</div>

<h2>Détail des charges réelles saisies — <?= $year ?></h2>
<?php if (!$costs): ?>
    <div class="card empty">Aucune charge réelle saisie. <a href="<?= url('/biens/'.$p['id'].'?annee='.$year.'#charges') ?>">Saisir les appels de charges et la taxe foncière</a>.</div>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Date</th><th>Nature</th><th class="num">Montant</th><th class="num">dont récupérable</th></tr></thead>
    <tbody>
    <?php foreach ($costs as $c): ?>
        <tr><td><?= $c['cost_date'] ? fdate($c['cost_date']) : '—' ?></td><td><?= e(PropertyCost::label($c)) ?></td>
            <td class="num"><?= euros($c['amount']) ?></td><td class="num"><?= (float) $c['recoverable'] != 0.0 ? euros($c['recoverable']) : '—' ?></td></tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
