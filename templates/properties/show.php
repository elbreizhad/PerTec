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

<div class="grid grid-2">
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
