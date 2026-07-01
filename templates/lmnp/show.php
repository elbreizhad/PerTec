<?php /** @var array $property */ /** @var array $lmnp */ /** @var int $year */
$p = $property; $f = $lmnp;
$impotReel = max(0.0, $f['result_reel']);
?>
<div class="page-head">
    <div>
        <h1>Fiscalité LMNP — <?= e($p['label']) ?></h1>
        <p class="muted"><a href="<?= url('/biens/'.$p['id']) ?>">← Retour au bien</a></p>
    </div>
    <form method="get" action="<?= url('/biens/'.$p['id'].'/fiscalite') ?>" class="actions">
        <select name="year" onchange="this.form.submit()">
            <?php $cy=(int)date('Y'); for ($y=$cy+1;$y>=$cy-6;$y--): ?><option <?= $y===$year?'selected':'' ?>><?= $y ?></option><?php endfor; ?>
        </select>
    </form>
</div>

<div class="grid grid-4 mb">
    <div class="stat"><div class="label">Recettes <?= $year ?></div><div class="value"><?= euros($f['recettes']) ?></div></div>
    <div class="stat"><div class="label">Charges déductibles</div><div class="value"><?= euros($f['charges_total']) ?></div></div>
    <div class="stat"><div class="label">Amortissements</div><div class="value"><?= euros($f['amort_used']) ?></div></div>
    <div class="stat"><div class="label">Résultat imposable (réel)</div><div class="value <?= $f['result_reel']<=0?'pos':'' ?>"><?= euros($f['result_reel']) ?></div></div>
</div>

<div class="card">
    <h3>Comparaison des régimes — année <?= $year ?></h3>
    <div class="table-wrap"><table>
        <thead><tr><th>Régime</th><th class="num">Base imposable</th><th></th></tr></thead>
        <tbody>
            <tr>
                <td><strong>Réel</strong><br><span class="small muted">Recettes − charges − amortissements</span></td>
                <td class="num"><?= euros(max(0.0,$f['result_reel'])) ?><?php if ($f['result_reel']<0): ?><br><span class="small" style="color:var(--green)">déficit reportable <?= euros(-$f['result_reel']) ?></span><?php endif; ?></td>
                <td><?= $f['best']==='reel'?'<span class="badge badge-paid">Le plus avantageux</span>':'' ?></td>
            </tr>
            <tr>
                <td><strong>Micro-BIC</strong><br><span class="small muted">Abattement forfaitaire 50 %</span></td>
                <td class="num"><?= euros($f['micro_base']) ?><?php if (!$f['micro_eligible']): ?><br><span class="small" style="color:var(--red)">recettes &gt; <?= euros(Lmnp::MICRO_BIC_CEILING) ?></span><?php endif; ?></td>
                <td><?= $f['best']==='micro' && $f['micro_eligible']?'<span class="badge badge-paid">Le plus avantageux</span>':'' ?></td>
            </tr>
        </tbody>
    </table></div>
    <p class="hint">Le régime configuré pour ce bien est : <strong><?= ($p['tax_regime']??'reel')==='micro'?'Micro-BIC':'Réel' ?></strong>
        (modifiable dans <a href="<?= url('/biens/'.$p['id'].'/edit') ?>">la fiche du bien</a>).</p>
</div>

<div class="grid grid-2">
    <div class="card">
        <h3>Détail des charges déductibles</h3>
        <table class="mini"><tbody>
            <tr><td>Taxe foncière</td><td class="num"><?= euros($f['charges']['taxe_fonciere']) ?></td></tr>
            <tr><td>Assurance PNO</td><td class="num"><?= euros($f['charges']['assurance']) ?></td></tr>
            <tr><td>Charges de copropriété</td><td class="num"><?= euros($f['charges']['charges_copro']) ?></td></tr>
            <tr><td>Frais de gestion</td><td class="num"><?= euros($f['charges']['gestion']) ?></td></tr>
            <tr><td>Intérêts d'emprunt</td><td class="num"><?= euros($f['charges']['interets']) ?></td></tr>
            <tr><td>Frais de comptable</td><td class="num"><?= euros($f['charges']['comptable']) ?></td></tr>
            <tr><td>Autres charges déductibles</td><td class="num"><?= euros($f['charges']['autres']) ?></td></tr>
            <tr class="total"><td><strong>Total</strong></td><td class="num"><strong><?= euros($f['charges_total']) ?></strong></td></tr>
        </tbody></table>
    </div>
    <div class="card">
        <h3>Amortissements annuels</h3>
        <table class="mini"><tbody>
            <tr><td>Bâti (base <?= euros($f['amort_bases']['building']) ?> / <?= (int)$p['amort_years_building'] ?> ans)</td><td class="num"><?= euros($f['amort']['building']) ?></td></tr>
            <tr><td>Mobilier (base <?= euros($f['amort_bases']['furniture']) ?> / <?= (int)$p['amort_years_furniture'] ?> ans)</td><td class="num"><?= euros($f['amort']['furniture']) ?></td></tr>
            <tr><td>Travaux (base <?= euros($f['amort_bases']['works']) ?> / <?= (int)$p['amort_years_works'] ?> ans)</td><td class="num"><?= euros($f['amort']['works']) ?></td></tr>
            <tr class="total"><td><strong>Amortissement théorique</strong></td><td class="num"><strong><?= euros($f['amort_total']) ?></strong></td></tr>
            <tr><td>Amortissement utilisé (plafonné)</td><td class="num"><?= euros($f['amort_used']) ?></td></tr>
            <?php if ($f['amort_carry']>0.01): ?><tr><td class="small muted">Report d'amortissement</td><td class="num small muted"><?= euros($f['amort_carry']) ?></td></tr><?php endif; ?>
        </tbody></table>
    </div>
</div>

<div class="card">
    <p class="small muted">ℹ️ <strong>Note importante.</strong> Ce module est une aide au calcul basée sur des règles simplifiées
    (base de caisse pour les recettes, amortissement linéaire, amortissement ne pouvant créer de déficit, abattement micro-BIC de 50 %).
    Il ne remplace pas l'établissement de la liasse fiscale (formulaires 2031/2033) par un expert-comptable.
    Les intérêts d'emprunt sont estimés à partir d'un tableau d'amortissement calculé sur les paramètres saisis.</p>
</div>
