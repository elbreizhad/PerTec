<?php /** @var array $property */ /** @var int $year */ /** @var string $asOf */ /** @var array $b */ /** @var array $costs */
$p = $property; $cy = (int) date('Y'); $r = $b['regul'];
$money = fn($v) => '<span class="' . ($v < 0 ? 'neg' : '') . '">' . euros($v) . '</span>';
?>
<div class="page-head">
    <div>
        <h1>Bilan au <?= fdate($asOf) ?> — <?= e($p['label']) ?></h1>
        <p class="muted"><a href="<?= url('/bilan?au='.$asOf) ?>">← Tous les biens</a> · <a href="<?= url('/biens/'.$p['id'].'?annee='.$year.'#charges') ?>">Saisir les charges réelles</a></p>
    </div>
    <form method="get" action="<?= url('/bilan/'.$p['id']) ?>" class="no-print" style="display:flex;flex-wrap:wrap;gap:.5rem;align-items:center;justify-content:flex-end">
        <label class="small" style="font-weight:600;display:flex;align-items:center;gap:.4rem">Arrêté au
            <input type="date" name="au" value="<?= e($asOf) ?>" onchange="this.form.submit()" style="width:auto"></label>
        <select style="width:auto" onchange="location.href='<?= url('/bilan/'.$p['id']) ?>?year='+this.value" title="Fin d'année">
            <?php for ($y = $cy + 1; $y >= $cy - 6; $y--): ?><option value="<?= $y ?>" <?= $y === $year ? 'selected' : '' ?>><?= $y ?></option><?php endfor; ?>
        </select>
        <button type="button" class="btn" onclick="window.print()">🖨️ Imprimer</button>
    </form>
</div>

<?php $per = $b['period']; if ($per['from']): ?>
    <p class="muted">Période : du <?= fdate($per['from']) ?> au <?= fdate($per['to']) ?>
        — seuls les loyers encaissés, les échéances de prêt passées et les charges datées jusqu'au <?= fdate($per['to']) ?> sont comptés.</p>
<?php else: ?>
    <div class="card empty">Ce bien n'était pas encore détenu au <?= fdate($asOf) ?>.</div>
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
    <h3>Régularisation des charges <?= $year ?></h3>
    <?php if ($r['forfait']): ?>
        <p class="muted">Charges au forfait : pas de régularisation.</p>
    <?php elseif (!$r['applicable']): ?>
        <p class="muted">Aucun bail sur <?= $year ?> : pas de régularisation.</p>
    <?php else: ?>
        <?php if ($r['provisoire']): ?>
            <p class="small" style="color:#92400e">Estimation provisoire : la régularisation se calcule sur l'exercice complet
                (charges récupérables de toute l'année <?= $year ?>, provisions de tous les mois). Elle sera définitive une fois
                toutes les charges de l'année saisies (dernier appel, décompte du syndic, TEOM).</p>
        <?php endif; ?>
        <p class="small muted">Charges récupérables saisies pour <?= $year ?> : <strong><?= euros($r['recuperable']) ?></strong>
            pour <?= $r['own_days'] ?> jours de détention (du <?= fdate($r['own_from']) ?> au 31/12/<?= $year ?>).
            Chaque locataire n'en supporte que la part correspondant à ses jours d'occupation.</p>
        <div class="table-wrap"><table>
            <thead><tr><th>Locataire</th><th class="num">Part des charges</th><th class="num">Provisions</th><th class="num">Solde</th></tr></thead>
            <tbody>
            <?php foreach ($r['rows'] as $t): ?>
                <tr>
                    <td><?= e($t['tenant']) ?><br><span class="small muted">du <?= fdate($t['from']) ?> au <?= fdate($t['to']) ?> — <?= $t['days'] ?> jours</span></td>
                    <td class="num"><?= euros($t['share']) ?><br><span class="small muted"><?= euros($r['recuperable']) ?> × <?= $t['days'] ?>/<?= $r['own_days'] ?></span></td>
                    <td class="num"><?= euros($t['provisions']) ?><br><span class="small muted"><?= $t['months'] ?> mois<?= $t['paid'] < $t['months'] ? ', dont ' . ($t['months'] - $t['paid']) . ' non encore payé(s)' : '' ?></span></td>
                    <td class="num"><strong><?= euros(abs($t['solde'])) ?></strong><br><span class="small muted"><?= $t['solde'] >= 0 ? 'à réclamer' : 'à rembourser' ?></span></td>
                </tr>
            <?php endforeach; ?>
            <?php if ($r['vacant_days'] > 0): ?>
                <tr class="muted">
                    <td>Logement vacant<br><span class="small"><?= $r['vacant_days'] ?> jours sans locataire</span></td>
                    <td class="num"><?= euros($r['vacant_share']) ?><br><span class="small">à votre charge</span></td>
                    <td class="num">—</td><td class="num">—</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table></div>
        <p class="small muted">Locataire parti en cours d'année : sa régularisation peut être faite dès que les charges de sa
            période sont connues (vous pouvez conserver jusqu'à 20 % du dépôt de garantie dans l'attente de l'arrêté des
            comptes de la copropriété — art. 22 de la loi du 6 juillet 1989).</p>
        <p class="small muted">La régularisation se fait une fois par an, avec le décompte par nature de charges, un mois avant
            (art. 23 de la loi du 6 juillet 1989). Les justificatifs sont tenus à disposition du locataire pendant six mois.</p>
    <?php endif; ?>
</div>
</div>

<h2>Détail des charges réelles saisies — <?= $year ?></h2>
<p class="small muted">Les lignes datées après le <?= fdate($asOf) ?> apparaissent ici mais ne sont pas comptées dans le bilan.</p>
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
