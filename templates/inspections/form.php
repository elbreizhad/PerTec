<?php /** @var array $insp */ /** @var array $lease */ /** @var string $action */ /** @var bool $locked */ /** @var bool $tenantMode */
// Formulaire de saisie de l'état des lieux : bailleur (tout) ou locataire depuis son espace ($tenantMode :
// pas de désignation, pas de dépôt, colonnes « entrée » figées pour la sortie, ses propres observations).
$l = $lease;
$d = $insp['data'];
$sortie = $insp['kind'] === 'sortie';
$meuble = $l['lease_type'] === 'meuble';
$states = $sortie ? array_diff_key(Inspection::STATES, ['neuf' => 1]) : Inspection::STATES;
$col = $sortie ? 'out' : 'in';
$ro = $locked ? 'disabled' : '';
$inRo = $tenantMode && $sortie ? 'disabled' : '';   // colonnes « entrée » non modifiables par le locataire à la sortie

/** Boutons d'état (Neuf / Bon / Usagé / Dégradé). */
$seg = function (string $name, string $current) use ($states, $ro): string {
    $h = '<div class="seg">';
    foreach ($states as $k => $lbl) {
        $h .= '<label class="s-' . $k . '"><input type="radio" name="' . e($name) . '" value="' . $k . '"' . ($current === $k ? ' checked' : '') . " $ro><span>" . e($lbl) . '</span></label>';
    }
    return $h . '<label><input type="radio" name="' . e($name) . '" value=""' . ($current === '' ? ' checked' : '') . " $ro><span>—</span></label></div>";
};
$stateSelect = function (string $name, string $current, string $attr = '') use ($ro): string {
    $h = '<select name="' . e($name) . "\" $ro $attr><option value=\"\">—</option>";
    foreach (Inspection::STATES as $k => $lbl) $h .= '<option value="' . $k . '"' . ($current === $k ? ' selected' : '') . '>' . e($lbl) . '</option>';
    return $h . '</select>';
};
?>
<form method="post" action="<?= e($action) ?>">
    <?= csrf_field() ?>
    <?php if (!$tenantMode || $sortie): ?>
    <fieldset <?= $ro ?>>
        <legend><?= $tenantMode ? 'Restitution du dépôt de garantie' : 'Généralités' ?></legend>
        <?php if (!$tenantMode): ?>
        <div class="form-grid">
            <div class="field"><label>Date et heure de l'état des lieux</label>
                <input type="datetime-local" name="inspected_at" value="<?= $insp['inspected_at'] ? e(date('Y-m-d\TH:i', strtotime($insp['inspected_at']))) : '' ?>">
                <span class="hint">Vide = date de la première signature.</span></div>
            <div class="field"><label>Résidence / bâtiment</label><input name="residence" value="<?= e($d['residence']) ?>" placeholder="Résidence Les Airelles"></div>
            <div class="field"><label>Désignation</label><input name="designation" value="<?= e($d['designation']) ?>" placeholder="Appartement meublé — 1er étage — 23 m²"></div>
            <div class="field"><label>Annexe privative</label><input name="annexe" value="<?= e($d['annexe']) ?>" placeholder="Cave, parking n°…"></div>
            <div class="field"><label>Chauffage / eau chaude</label><input name="heating" value="<?= e($d['heating']) ?>" placeholder="Individuels électriques"></div>
        </div>
        <?php endif; ?>
        <?php if ($sortie): ?>
        <div class="form-grid">
            <div class="field"><label>Nouvelle adresse du locataire</label><textarea name="tenant[new_address]" rows="2"><?= e($d['tenant']['new_address']) ?></textarea>
                <?php if (!$tenantMode): ?><span class="hint">Le locataire peut aussi la saisir depuis son espace.</span><?php endif; ?></div>
            <div class="field"><label>IBAN pour la restitution du dépôt</label><input name="tenant[iban]" value="<?= e($d['tenant']['iban']) ?>"></div>
        </div>
        <?php endif; ?>
    </fieldset>
    <?php endif; ?>

    <fieldset <?= $ro ?>>
        <legend>Relevé des compteurs</legend>
        <?php $m = $d['meters']; ?>
        <div class="form-grid">
            <div class="field"><label>⚡ PDL / n° compteur électrique</label><input name="meters[elec_number]" value="<?= e($m['elec_number']) ?>" <?= $tenantMode ? 'disabled' : '' ?>></div>
            <div class="field"><label>Index HP (ou base)</label><input name="meters[elec_hp]" value="<?= e($m['elec_hp']) ?>" inputmode="numeric"></div>
            <div class="field"><label>Index HC</label><input name="meters[elec_hc]" value="<?= e($m['elec_hc']) ?>" inputmode="numeric"></div>
        </div>
        <div class="form-grid">
            <div class="field"><label>💧 N° compteur d'eau</label><input name="meters[water_number]" value="<?= e($m['water_number']) ?>" <?= $tenantMode ? 'disabled' : '' ?>></div>
            <div class="field"><label>Index eau (m³)</label><input name="meters[water]" value="<?= e($m['water']) ?>" inputmode="decimal"></div>
            <div class="field"><label>Date du relevé</label><input type="date" name="meters[date]" value="<?= e($m['date']) ?>">
                <span class="hint">Vide = date de l'état des lieux.</span></div>
        </div>
        <?php if ($sortie): ?>
            <p class="hint">Index relevés à l'entrée (pour calculer la consommation) :</p>
            <div class="form-grid">
                <div class="field"><label>Entrée — HP</label><input name="meters[elec_hp_in]" value="<?= e($m['elec_hp_in']) ?>" <?= $inRo ?> inputmode="numeric"></div>
                <div class="field"><label>Entrée — HC</label><input name="meters[elec_hc_in]" value="<?= e($m['elec_hc_in']) ?>" <?= $inRo ?> inputmode="numeric"></div>
                <div class="field"><label>Entrée — eau</label><input name="meters[water_in]" value="<?= e($m['water_in']) ?>" <?= $inRo ?> inputmode="decimal"></div>
            </div>
        <?php endif; ?>
    </fieldset>

    <fieldset <?= $ro ?>>
        <legend>Clés et accès</legend>
        <div class="table-wrap"><table class="edl-inv">
            <thead><tr><th>Élément</th><th>Remis à l'entrée</th><?php if ($sortie): ?><th>Restitué</th><?php endif; ?><th>Observations</th></tr></thead>
            <tbody>
            <?php foreach ($d['keys'] as $i => $k): ?>
                <tr><td><?= e($k['label']) ?></td>
                    <td><input class="qty" name="keys[<?= $i ?>][in]" value="<?= e($k['in']) ?>" inputmode="numeric" <?= $inRo ?>></td>
                    <?php if ($sortie): ?><td><input class="qty" name="keys[<?= $i ?>][out]" value="<?= e($k['out']) ?>" inputmode="numeric"></td><?php endif; ?>
                    <td><input name="keys[<?= $i ?>][notes]" value="<?= e($k['notes']) ?>"></td></tr>
            <?php endforeach; ?>
            <?php for ($j = 0; $j < Inspection::FREE_ROWS; $j++): ?>
                <tr><td><input name="keys[new][<?= $j ?>][label]" placeholder="+ Autre (à préciser)"></td>
                    <td><input class="qty" name="keys[new][<?= $j ?>][in]" inputmode="numeric"></td>
                    <?php if ($sortie): ?><td><input class="qty" name="keys[new][<?= $j ?>][out]" inputmode="numeric"></td><?php endif; ?>
                    <td><input name="keys[new][<?= $j ?>][notes]"></td></tr>
            <?php endfor; ?>
            </tbody>
        </table></div>
    </fieldset>

    <fieldset <?= $ro ?>>
        <legend>État détaillé par pièce</legend>
        <p class="hint"><?= $sortie ? "Indiquez l'état constaté à la sortie et décrivez toute différence avec l'entrée (trace, rayure, tache, casse, manque…)."
                                    : "Indiquez l'état de chaque élément à l'entrée et précisez tout défaut en observation." ?></p>
        <?php foreach ($d['rooms'] as $ri => $r): ?>
            <div class="edl-room">
                <h3><?= e($r['name']) ?></h3>
                <?php foreach ($r['items'] as $ii => $it): $nm = "rooms[$ri][items][$ii]"; ?>
                    <div class="edl-item">
                        <div class="edl-label"><?= e($it['label']) ?>
                            <?php if ($sortie && ($it['in_state'] !== '' || $it['in_notes'] !== '')): ?>
                                <span class="muted">Entrée : <?= e(implode(' — ', array_filter([Inspection::stateLabel($it['in_state']), $it['in_notes']], 'strlen'))) ?></span>
                            <?php endif; ?></div>
                        <?= $seg($nm . '[state]', $it[$col . '_state']) ?>
                        <input type="text" name="<?= $nm ?>[notes]" value="<?= e($it[$col . '_notes']) ?>" placeholder="Observations">
                    </div>
                <?php endforeach; ?>
                <?php for ($j = 0; $j < Inspection::FREE_ROWS; $j++): $nm = "rooms[$ri][items][new][$j]"; ?>
                    <div class="edl-item">
                        <input type="text" name="<?= $nm ?>[label]" placeholder="+ Autre élément">
                        <?= $seg($nm . '[state]', '') ?>
                        <input type="text" name="<?= $nm ?>[notes]" placeholder="Observations">
                    </div>
                <?php endfor; ?>
            </div>
        <?php endforeach; ?>
        <?php if (!$locked && !$tenantMode): ?>
        <details>
            <summary class="small" style="cursor:pointer;font-weight:600">+ Ajouter une pièce</summary>
            <div class="form-grid mt">
                <div class="field"><label>Nom de la pièce</label><input name="new_room[name]" placeholder="Chambre 2"></div>
                <div class="field"><label>Éléments (un par ligne)</label><textarea name="new_room[items]" rows="4" placeholder="Sol&#10;Murs / peinture&#10;Plafond"></textarea></div>
            </div>
        </details>
        <?php endif; ?>
        <div class="field mt">
            <label>Propreté générale <?= $sortie ? 'à la sortie' : "à l'entrée" ?></label>
            <div class="seg">
                <?php foreach (['propre' => 'Propre', 'a_nettoyer' => 'À nettoyer', '' => '—'] as $k => $lbl): ?>
                    <label class="<?= $k === 'propre' ? 's-bon' : ($k ? 's-degrade' : '') ?>"><input type="radio" name="cleanliness" value="<?= $k ?>" <?= $d['cleanliness'] === $k ? 'checked' : '' ?> <?= $ro ?>><span><?= $lbl ?></span></label>
                <?php endforeach; ?>
            </div>
            <input type="text" name="cleanliness_notes" value="<?= e($d['cleanliness_notes']) ?>" placeholder="Préciser (si à nettoyer)" class="mt">
        </div>
    </fieldset>

    <?php if ($meuble): ?>
    <fieldset <?= $ro ?>>
        <legend>Inventaire du mobilier et des équipements</legend>
        <?php if (!$d['inventory']): ?>
            <p class="muted small"><?= $tenantMode ? 'Aucun inventaire.' : "Aucun inventaire : renseignez-le dans le modèle d'état des lieux du logement (fiche du bien) avant de créer l'état des lieux." ?></p>
        <?php endif; ?>
        <?php foreach ($d['inventory'] as $gi => $g): ?>
            <div class="table-wrap mb"><table class="edl-inv">
                <thead><tr><th><?= e($g['name']) ?></th><th>Qté<?= $sortie ? ' entrée' : '' ?></th><th>État<?= $sortie ? ' entrée' : '' ?></th>
                    <?php if ($sortie): ?><th>Qté sortie</th><th>État sortie</th><?php endif; ?><th>Observations</th></tr></thead>
                <tbody>
                <?php foreach ($g['items'] as $ii => $it): $nm = "inventory[$gi][items][$ii]"; ?>
                    <tr><td><?= e($it['label']) ?></td>
                        <td><input class="qty" name="<?= $nm ?>[in_qty]" value="<?= e($it['in_qty']) ?>" <?= $inRo ?>></td>
                        <td><?= $stateSelect($nm . '[in_state]', $it['in_state'], $inRo) ?></td>
                        <?php if ($sortie): ?>
                            <td><input class="qty" name="<?= $nm ?>[out_qty]" value="<?= e($it['out_qty']) ?>"></td>
                            <td><?= $stateSelect($nm . '[out_state]', $it['out_state']) ?></td>
                        <?php endif; ?>
                        <td><input name="<?= $nm ?>[notes]" value="<?= e($it['notes']) ?>"></td></tr>
                <?php endforeach; ?>
                <?php for ($j = 0; $j < Inspection::FREE_ROWS; $j++): $nm = "inventory[$gi][items][new][$j]"; ?>
                    <tr><td><input name="<?= $nm ?>[label]" placeholder="+ Autre élément"></td>
                        <td><input class="qty" name="<?= $nm ?>[in_qty]"></td>
                        <td><?= $stateSelect($nm . '[in_state]', '') ?></td>
                        <?php if ($sortie): ?><td><input class="qty" name="<?= $nm ?>[out_qty]"></td><td><?= $stateSelect($nm . '[out_state]', '') ?></td><?php endif; ?>
                        <td><input name="<?= $nm ?>[notes]"></td></tr>
                <?php endfor; ?>
                </tbody>
            </table></div>
        <?php endforeach; ?>
        <?php if ($sortie && $d['inventory'] && !$locked): ?>
            <button type="button" class="btn btn-sm" onclick="this.form.querySelectorAll('input[name$=&quot;[out_qty]&quot;]').forEach(function(o){var i=o.closest('tr').querySelector('input[name$=&quot;[in_qty]&quot;]');if(!o.value&&i)o.value=i.value});this.form.querySelectorAll('select[name$=&quot;[out_state]&quot;]').forEach(function(o){var i=o.closest('tr').querySelector('select[name$=&quot;[in_state]&quot;]');if(!o.value&&i)o.value=i.value})">
                Reporter les quantités et états d'entrée dans les colonnes « sortie » vides</button>
        <?php endif; ?>
    </fieldset>
    <?php endif; ?>

    <fieldset <?= $ro ?>>
        <legend><?= $tenantMode ? 'Vos observations' : 'Observations générales' . ($sortie ? ' et différences constatées' : '') ?></legend>
        <?php if ($tenantMode): ?>
            <?php if ($d['observations'] !== ''): ?><p class="small"><strong>Observations du bailleur :</strong><br><?= nl2br(e($d['observations'])) ?></p><?php endif; ?>
            <div class="field"><textarea name="tenant[observations]" rows="4" placeholder="Défauts constatés, désaccords, précisions…"><?= e($d['tenant']['observations']) ?></textarea></div>
        <?php else: ?>
            <div class="field"><textarea name="observations" rows="4"><?= e($d['observations']) ?></textarea></div>
            <?php if ($d['tenant']['observations'] !== ''): ?>
                <p class="small"><strong>Observations du locataire</strong> (saisies dans son espace) :<br><?= nl2br(e($d['tenant']['observations'])) ?></p>
            <?php endif; ?>
        <?php endif; ?>
    </fieldset>

    <?php if ($sortie && !$tenantMode): ?>
    <fieldset <?= $ro ?>>
        <legend>Dépôt de garantie</legend>
        <div class="form-grid">
            <div class="field"><label>Montant du dépôt versé (€)</label><input name="deposit[amount]" value="<?= e($d['deposit']['amount']) ?>" inputmode="decimal"></div>
            <div class="field"><label>Total des retenues (€)</label><input name="deposit[deducted]" value="<?= e($d['deposit']['deducted']) ?>" inputmode="decimal" placeholder="0 si aucune"></div>
            <div class="field"><label>Montant à restituer</label><input value="<?= ($refund = Inspection::refund($d)) !== null && $d['deposit']['deducted'] !== '' ? e(euros($refund)) : '' ?>" disabled></div>
        </div>
        <div class="field"><label>Retenues proposées (motif et montant)</label><textarea name="deposit[deductions]" rows="3" placeholder="Ex. : remplacement d'une chaise cassée — 35 € (facture jointe)"><?= e($d['deposit']['deductions']) ?></textarea></div>
        <p class="hint">Restitution sous un mois si l'état de sortie est conforme à l'entrée, deux mois sinon (art. 22 de la loi n° 89-462).</p>
    </fieldset>
    <?php endif; ?>

    <?php if (!$locked): ?>
    <div class="edl-save actions">
        <button class="btn btn-primary">Enregistrer</button>
        <?php if (!$tenantMode): ?><button class="btn" name="then" value="document">Enregistrer et voir le PDF</button><?php endif; ?>
    </div>
    <?php endif; ?>
</form>
