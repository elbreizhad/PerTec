<?php /** @var array $property */ /** @var array $ind */ /** @var array $leases */
$p = $property;
$expenses = Expense::forProperty((int) $p['id']);
$expensesTotal = Expense::totalForProperty((int) $p['id']); ?>
<div class="page-head">
    <div>
        <h1><?= e($p['label']) ?></h1>
        <p class="muted"><?= e(trim(($p['address'] ?: '').' '.($p['postal_code'] ?: '').' '.($p['city'] ?: ''))) ?: e(ucfirst($p['type'])) ?></p>
    </div>
    <div class="actions">
        <a href="<?= url('/biens/'.$p['id'].'/fiscalite') ?>" class="btn btn-secondary">📊 Fiscalité LMNP</a>
        <a href="<?= url('/biens/'.$p['id'].'/edit') ?>" class="btn">Modifier</a>
        <a href="<?= url('/baux/new') ?>" class="btn btn-primary">+ Nouveau bail</a>
    </div>
</div>

<div class="grid grid-4 mb">
    <div class="stat"><div class="label">Coût total</div><div class="value"><?= euros($ind['total_cost']) ?></div></div>
    <div class="stat"><div class="label">Rentab. brute</div><div class="value"><?= number_format($ind['gross_yield'],2,',',' ') ?> %</div></div>
    <div class="stat"><div class="label">Rentab. nette</div><div class="value"><?= number_format($ind['net_yield'],2,',',' ') ?> %</div></div>
    <div class="stat"><div class="label">Cash-flow / mois</div><div class="value <?= $ind['cashflow_monthly']>=0?'pos':'neg' ?>"><?= euros($ind['cashflow_monthly']) ?></div></div>
</div>

<div class="grid grid-2col">
    <div class="card">
        <h3>Caractéristiques</h3>
        <dl class="kv">
            <dt>Type</dt><dd><?= e(ucfirst($p['type'])) ?></dd>
            <dt>Surface</dt><dd><?= $p['surface_m2'] ? e($p['surface_m2']).' m²' : '—' ?></dd>
            <dt>Pièces</dt><dd><?= $p['rooms'] ?: '—' ?></dd>
            <dt>Date d'achat</dt><dd><?= fdate($p['purchase_date']) ?></dd>
        </dl>
    </div>
    <div class="card">
        <h3>Détail de l'investissement</h3>
        <dl class="kv">
            <dt>Prix d'achat</dt><dd><?= euros($p['purchase_price']) ?></dd>
            <dt>Frais de notaire</dt><dd><?= euros($p['notary_fees']) ?></dd>
            <dt>Frais d'agence</dt><dd><?= euros($p['agency_fees']) ?></dd>
            <dt>Travaux</dt><dd><?= euros($p['works_cost']) ?></dd>
            <dt>Autres frais</dt><dd><?= euros($p['other_costs']) ?></dd>
            <dt><strong>Total</strong></dt><dd><strong><?= euros($ind['total_cost']) ?></strong></dd>
        </dl>
    </div>
    <div class="card">
        <h3>Financement & charges</h3>
        <dl class="kv">
            <dt>Emprunt</dt><dd><?= euros($p['loan_amount']) ?> à <?= number_format((float)$p['loan_rate'],2,',',' ') ?> %</dd>
            <dt>Mensualité</dt><dd><?= euros($p['loan_monthly']) ?></dd>
            <dt>Taxe foncière</dt><dd><?= euros($p['property_tax']) ?>/an</dd>
            <dt>Assurance PNO</dt><dd><?= euros($p['insurance_year']) ?>/an</dd>
            <dt>Charges non récup.</dt><dd><?= euros($p['charges_year']) ?>/an</dd>
            <dt>Charges totales</dt><dd><?= euros($ind['annual_charges']) ?>/an</dd>
        </dl>
    </div>
    <div class="card">
        <h3>Rendement</h3>
        <dl class="kv">
            <dt>Loyer mensuel</dt><dd><?= euros($ind['monthly_rent']) ?></dd>
            <dt>Loyers annuels</dt><dd><?= euros($ind['annual_rent']) ?></dd>
            <dt>Cash-flow annuel</dt><dd><?= euros($ind['cashflow_annual']) ?></dd>
        </dl>
        <?php if ($p['notes']): ?><p class="small muted mt"><?= nl2br(e($p['notes'])) ?></p><?php endif; ?>
    </div>
</div>

<h2>Dépenses &amp; travaux</h2>
<p class="muted small">Achat, travaux, aménagement, mobilier… Ces montants s'ajoutent au coût total d'acquisition
et alimentent les amortissements LMNP.</p>

<div class="card">
    <form method="post" action="<?= url('/biens/'.$p['id'].'/depenses') ?>" class="expense-form">
        <?= csrf_field() ?>
        <div class="form-grid">
            <div class="field">
                <label>Catégorie</label>
                <select name="category">
                    <?php foreach (Expense::CATEGORIES as $val => $lbl): ?>
                        <option value="<?= e($val) ?>"><?= e($lbl) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="field"><label>Libellé</label><input name="label" placeholder="Ex : Réfection salle de bain" required></div>
            <div class="field"><label>Montant (€)</label><input name="amount" required></div>
            <div class="field"><label>Date</label><input type="date" name="expense_date"></div>
        </div>
        <button class="btn btn-primary">+ Ajouter la dépense</button>
    </form>
</div>

<?php if ($expenses): ?>
<div class="table-wrap"><table>
    <thead><tr><th>Date</th><th>Catégorie</th><th>Libellé</th><th class="num">Montant</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($expenses as $ex): ?>
        <tr>
            <td><?= fdate($ex['expense_date']) ?></td>
            <td><?= e(Expense::CATEGORIES[$ex['category']] ?? $ex['category']) ?></td>
            <td><?= e($ex['label']) ?></td>
            <td class="num"><?= euros($ex['amount']) ?></td>
            <td class="right">
                <form class="inline-form" method="post" action="<?= url('/depenses/'.$ex['id'].'/delete') ?>" onsubmit="return confirm('Supprimer cette dépense ?')"><?= csrf_field() ?><button class="btn btn-sm btn-danger">×</button></form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot><tr><th colspan="3">Total des dépenses</th><th class="num"><?= euros($expensesTotal) ?></th><th></th></tr></tfoot>
</table></div>
<?php else: ?>
    <div class="card empty">Aucune dépense enregistrée pour ce bien.</div>
<?php endif; ?>

<?php
$cy = (int) date('Y');
$costYear = (int) ($_GET['annee'] ?? $cy);
$costs = PropertyCost::forProperty((int) $p['id'], $costYear);
$costTot = array_sum(array_column($costs, 'amount'));
$costRec = array_sum(array_column($costs, 'recoverable'));
?>
<div class="page-head" id="charges" style="margin-top:1.5rem">
    <h2 style="margin:0">Charges et impôts réels</h2>
    <form method="get" action="<?= url('/biens/'.$p['id']) ?>#charges" class="actions">
        <select name="annee" onchange="this.form.submit()">
            <?php for ($y = $cy + 1; $y >= $cy - 6; $y--): ?><option <?= $y === $costYear ? 'selected' : '' ?>><?= $y ?></option><?php endfor; ?>
        </select>
        <a class="btn btn-secondary" href="<?= url('/bilan/'.$p['id'].'?year='.$costYear) ?>">📒 Bilan <?= $costYear ?></a>
    </form>
</div>
<p class="muted small">Saisissez les appels de charges du syndic, la régularisation annuelle et l'avis de taxe foncière.
    Ces montants réels remplacent les estimations dans le bilan annuel et la fiscalité LMNP, et servent à la régularisation des charges du locataire.</p>
<?php if ($costs): ?>
<div class="table-wrap"><table>
    <thead><tr><th>Date</th><th>Nature</th><th class="num">Montant</th><th class="num">dont récupérable</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($costs as $c): ?>
        <tr>
            <td><?= $c['cost_date'] ? fdate($c['cost_date']) : '—' ?></td>
            <td><?= e(PropertyCost::label($c)) ?><?= $c['notes'] ? '<br><span class="small muted">' . e($c['notes']) . '</span>' : '' ?></td>
            <td class="num"><?= euros($c['amount']) ?></td>
            <td class="num"><?= (float) $c['recoverable'] != 0.0 ? euros($c['recoverable']) : '—' ?></td>
            <td class="right"><form class="inline-form" method="post" action="<?= url('/biens/'.$p['id'].'/couts/'.$c['id'].'/delete') ?>" onsubmit="return confirm('Supprimer cette ligne ?')">
                <?= csrf_field() ?><button class="btn btn-sm btn-danger">×</button></form></td>
        </tr>
    <?php endforeach; ?>
        <tr class="total"><td colspan="2"><strong>Total <?= $costYear ?></strong></td><td class="num"><strong><?= euros($costTot) ?></strong></td><td class="num"><strong><?= euros($costRec) ?></strong></td><td></td></tr>
    </tbody>
</table></div>
<?php else: ?>
    <div class="card empty">Aucune charge réelle saisie pour <?= $costYear ?> : le bilan utilise les estimations de la fiche du bien.</div>
<?php endif; ?>
<form method="post" action="<?= url('/biens/'.$p['id'].'/couts') ?>" class="card">
    <?= csrf_field() ?>
    <h3>Ajouter une charge ou un impôt</h3>
    <div class="form-grid">
        <div class="field"><label>Nature</label>
            <select name="kind" id="cost-kind">
                <?php foreach (PropertyCost::KINDS as $k => [$label, $help]): ?>
                    <option value="<?= $k ?>" data-help="<?= e($help) ?>"><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="field"><label>Date (appel, avis…)</label><input type="date" name="cost_date" id="cost-date"></div>
        <div class="field"><label>Année concernée</label><input type="number" name="year" id="cost-year" value="<?= $costYear ?>" min="2000" max="2100"></div>
        <div class="field"><label>Montant (€)</label><input name="amount" inputmode="decimal" required placeholder="ex. 312,50"></div>
        <div class="field"><label id="rec-label">dont récupérable sur le locataire (€)</label><input name="recoverable" id="cost-rec" inputmode="decimal" placeholder="0"></div>
        <div class="field"><label>Libellé (optionnel)</label><input name="label" placeholder="ex. Appel T1 2026"></div>
    </div>
    <p class="hint" id="cost-help"></p>
    <div class="field"><label>Note (optionnel)</label><input name="notes" maxlength="255"></div>
    <button class="btn btn-primary">Ajouter</button>
</form>
<script>
(function () {
    var k = document.getElementById('cost-kind'), help = document.getElementById('cost-help'), rec = document.getElementById('cost-rec'),
        lab = document.getElementById('rec-label'), d = document.getElementById('cost-date'), y = document.getElementById('cost-year');
    function upd() {
        help.textContent = k.options[k.selectedIndex].dataset.help || '';
        rec.disabled = k.value === 'assurance';
        lab.textContent = k.value === 'taxe_fonciere' ? 'dont TEOM — récupérable (€)' : 'dont récupérable sur le locataire (€)';
    }
    k.addEventListener('change', upd); upd();
    d.addEventListener('change', function () { if (d.value) y.value = d.value.slice(0, 4); });
})();
</script>

<?php
$pid = (int) $property['id'];
$docTypes = PropertyDocument::types();
$allDocs = PropertyDocument::forProperty($pid);
$docIssues = PropertyDocument::issues($pid);
$currentIds = array_column(PropertyDocument::current($pid), 'id');
?>
<h2 id="documents">Documents légaux du logement</h2>
<p class="muted small">Diagnostics et documents à remettre au locataire avec le bail. Ils sont ajoutés automatiquement au
    dossier du locataire (fiche du bail → « Dossier du locataire »). Formats acceptés : PDF, JPG, PNG — 10 Mo maximum.</p>
<?php if ($docIssues): ?>
    <div class="flash flash-error small"><?= implode('<br>', array_map('e', $docIssues)) ?></div>
<?php endif; ?>
<?php if ($allDocs): ?>
<div class="table-wrap"><table>
    <thead><tr><th>Document</th><th>Date</th><th>Valable jusqu'au</th><th>Fichier</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($allDocs as $d): $exp = PropertyDocument::expiry($d); $old = !in_array($d['id'], $currentIds, false); ?>
        <tr<?= $old ? ' class="muted"' : '' ?>>
            <td><?= e(PropertyDocument::label($d)) ?><?= $old ? ' <span class="small">(remplacé)</span>' : '' ?></td>
            <td><?= $d['doc_date'] ? fdate($d['doc_date']) : '—' ?></td>
            <td><?php if ($exp): ?><span class="badge badge-<?= $exp < date('Y-m-d') ? 'late' : 'paid' ?>"><?= fdate($exp) ?></span><?php else: ?>—<?php endif; ?></td>
            <td><a href="<?= url('/biens/'.$pid.'/documents/'.$d['id']) ?>" target="_blank"><?= e($d['filename']) ?></a>
                <span class="small muted">(<?= number_format($d['size'] / 1024, 0, ',', ' ') ?> Ko)</span></td>
            <td class="right"><form class="inline-form" method="post" action="<?= url('/biens/'.$pid.'/documents/'.$d['id'].'/delete') ?>" onsubmit="return confirm('Supprimer ce document ?')">
                <?= csrf_field() ?><button class="btn btn-sm btn-danger">×</button></form></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
<form method="post" action="<?= url('/biens/'.$pid.'/documents') ?>" enctype="multipart/form-data" class="card">
    <?= csrf_field() ?>
    <h3>Ajouter un document</h3>
    <div class="form-grid">
        <div class="field"><label>Type de document</label>
            <select name="doc_type" id="doc-type">
                <?php foreach ($docTypes as $k => [$label, $help, $req]): ?>
                    <option value="<?= $k ?>" data-help="<?= e($help) ?>"><?= e($label) ?><?= $req ? ' *' : '' ?></option>
                <?php endforeach; ?>
            </select>
            <span class="hint" id="doc-help"></span>
        </div>
        <div class="field"><label>Date du document / du diagnostic</label><input type="date" name="doc_date"></div>
        <div class="field" id="doc-title" style="display:none"><label>Intitulé</label><input name="title" placeholder="ex. Attestation d'entretien chaudière"></div>
        <div class="field"><label>Fichier</label><input type="file" name="file" accept="application/pdf,image/jpeg,image/png" required></div>
    </div>
    <p class="hint">* document obligatoire pour toute location. Ajouter un document du même type remplace le précédent dans le dossier du locataire.</p>
    <button class="btn btn-primary">Ajouter</button>
</form>
<script>
(function () {
    var sel = document.getElementById('doc-type'), help = document.getElementById('doc-help'), title = document.getElementById('doc-title');
    function upd() { help.textContent = sel.options[sel.selectedIndex].dataset.help || ''; title.style.display = sel.value === 'autre' ? '' : 'none'; }
    sel.addEventListener('change', upd); upd();
})();
</script>

<h2>Baux liés à ce bien</h2>
<?php if (!$leases): ?>
    <div class="card empty">Aucun bail. <a href="<?= url('/baux/new') ?>">Créer un bail</a>.</div>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Locataire</th><th>Début</th><th class="num">Loyer</th><th>Statut</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($leases as $l): ?>
        <tr>
            <td><?= e($l['first_name'].' '.$l['last_name']) ?></td>
            <td><?= fdate($l['start_date']) ?></td>
            <td class="num"><?= euros((float)$l['rent_amount'] + (float)$l['charges_amount']) ?></td>
            <td><span class="badge badge-<?= e($l['status']) ?>"><?= $l['status']==='active'?'Actif':'Terminé' ?></span></td>
            <td class="right"><a href="<?= url('/baux/'.$l['id']) ?>" class="btn btn-sm">Voir</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
