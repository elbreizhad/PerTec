<?php /** @var array $lease */ /** @var bool $doc  true dans un contrat (texte juridique) */
// Détail des charges par nature. Rien n'est affiché si le détail n'est pas renseigné
// (le texte des baux existants reste identique : leurs signatures restent valables).
$rows = Lease::chargesDetail($lease);
if (!$rows) return;
$forfaitCh = ($lease['charge_type'] ?? 'provisions') === 'forfait';
?>
<?php if (!empty($doc)): ?>
<p class="article"><?= $forfaitCh ? 'Le forfait de charges' : 'La provision pour charges' ?> se décompose comme suit (montants mensuels) :</p>
<?php endif; ?>
<table class="doc-amounts charges-detail">
    <thead><tr><th>Nature de la charge</th><th class="num">Montant mensuel</th></tr></thead>
    <tbody>
    <?php foreach ($rows as $r): ?>
        <tr><td><?= e(Lease::chargeLabel($r)) ?></td><td class="num"><?= euros($r['amount']) ?></td></tr>
    <?php endforeach; ?>
        <tr class="total"><td><?= $forfaitCh ? 'Forfait mensuel de charges' : 'Provision mensuelle pour charges' ?></td><td class="num"><?= euros(array_sum(array_column($rows, 'amount'))) ?></td></tr>
    </tbody>
</table>
