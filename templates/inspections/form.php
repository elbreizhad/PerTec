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
$canEdit = !$locked;                 // ajout de lignes
$canDelete = !$locked && !$tenantMode;  // suppression des lignes existantes (bailleur)
/** Bouton de suppression d'une ligne existante (marquée, annulable jusqu'à l'enregistrement). */
$delBtn = fn(string $name) => $canDelete
    ? '<input type="hidden" name="' . e($name) . '[_del]" value="" data-del-flag><button type="button" class="row-del" data-del title="Supprimer la ligne" aria-label="Supprimer la ligne">✕</button>'
    : '';
/** Bouton de retrait d'une ligne ajoutée (pas encore enregistrée). */
$rmBtn = '<button type="button" class="row-del" data-remove title="Retirer la ligne" aria-label="Retirer la ligne">✕</button>';
$stateSelect = function (string $name, string $current, string $attr = '') use ($ro): string {
    $h = '<select name="' . e($name) . "\" $ro $attr><option value=\"\">—</option>";
    foreach (Inspection::STATES as $k => $lbl) $h .= '<option value="' . $k . '"' . ($current === $k ? ' selected' : '') . '>' . e($lbl) . '</option>';
    return $h . '</select>';
};
?>
<form method="post" action="<?= e($action) ?>" class="edl-form" data-edl-form>
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
            <thead><tr><th>Élément</th><th>Remis à l'entrée</th><?php if ($sortie): ?><th>Restitué</th><?php endif; ?><th>Observations</th><th></th></tr></thead>
            <tbody data-rows>
            <?php foreach ($d['keys'] as $i => $k): ?>
                <tr data-row><td><?= e($k['label']) ?></td>
                    <td><input class="qty" name="keys[<?= $i ?>][in]" value="<?= e($k['in']) ?>" inputmode="numeric" <?= $inRo ?>></td>
                    <?php if ($sortie): ?><td><input class="qty" name="keys[<?= $i ?>][out]" value="<?= e($k['out']) ?>" inputmode="numeric"></td><?php endif; ?>
                    <td><input name="keys[<?= $i ?>][notes]" value="<?= e($k['notes']) ?>"></td>
                    <td class="row-act"><?= $delBtn("keys[$i]") ?></td></tr>
            <?php endforeach; ?>
            </tbody>
        </table></div>
        <?php if ($canEdit): ?>
            <template><tr data-row><td><input name="keys[new][__N__][label]" placeholder="Élément (ex. badge garage)" data-focus></td>
                <td><input class="qty" name="keys[new][__N__][in]" inputmode="numeric" <?= $inRo ?>></td>
                <?php if ($sortie): ?><td><input class="qty" name="keys[new][__N__][out]" inputmode="numeric"></td><?php endif; ?>
                <td><input name="keys[new][__N__][notes]"></td><td class="row-act"><?= $rmBtn ?></td></tr></template>
            <button type="button" class="btn btn-sm row-add" data-add>+ Ajouter une ligne</button>
        <?php endif; ?>
    </fieldset>

    <fieldset <?= $ro ?>>
        <legend>État détaillé par pièce</legend>
        <p class="hint"><?= $sortie ? "Indiquez l'état constaté à la sortie et décrivez toute différence avec l'entrée (trace, rayure, tache, casse, manque…)."
                                    : "Indiquez l'état de chaque élément à l'entrée et précisez tout défaut en observation." ?></p>
        <div data-rooms>
        <?php foreach ($d['rooms'] as $ri => $r): ?>
            <div class="edl-room" data-row>
                <h3><span><?= e($r['name']) ?></span><?php if ($canDelete): ?><span class="room-del"><?= $delBtn("rooms[$ri]") ?></span><?php endif; ?></h3>
                <div data-rows>
                <?php foreach ($r['items'] as $ii => $it): $nm = "rooms[$ri][items][$ii]"; ?>
                    <div class="edl-item" data-row>
                        <div class="edl-label"><?= e($it['label']) ?>
                            <?php if ($sortie && ($it['in_state'] !== '' || $it['in_notes'] !== '')): ?>
                                <span class="muted">Entrée : <?= e(implode(' — ', array_filter([Inspection::stateLabel($it['in_state']), $it['in_notes']], 'strlen'))) ?></span>
                            <?php endif; ?></div>
                        <?= $seg($nm . '[state]', $it[$col . '_state']) ?>
                        <div class="edl-notes"><input type="text" name="<?= $nm ?>[notes]" value="<?= e($it[$col . '_notes']) ?>" placeholder="Observations"><?= $delBtn($nm) ?></div>
                    </div>
                <?php endforeach; ?>
                </div>
                <?php if ($canEdit): $nm = "rooms[$ri][items][new][__N__]"; ?>
                    <template><div class="edl-item" data-row>
                        <input type="text" name="<?= $nm ?>[label]" placeholder="Élément (ex. placard)" data-focus>
                        <?= $seg($nm . '[state]', '') ?>
                        <div class="edl-notes"><input type="text" name="<?= $nm ?>[notes]" placeholder="Observations"><?= $rmBtn ?></div>
                    </div></template>
                    <div class="room-foot"><button type="button" class="btn btn-sm row-add" data-add>+ Ajouter une ligne</button></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>
        <?php if ($canDelete): $nm = 'new_rooms[__R__][items][__N__]'; ?>
            <template id="room-tpl"><div class="edl-room" data-row>
                <h3><input type="text" name="new_rooms[__R__][name]" placeholder="Nom de la pièce (ex. Chambre 2)" data-focus style="max-width:320px"><span class="room-del"><?= $rmBtn ?></span></h3>
                <div data-rows></div>
                <template><div class="edl-item" data-row>
                    <input type="text" name="<?= $nm ?>[label]" placeholder="Élément (ex. Sol)" data-focus>
                    <?= $seg($nm . '[state]', '') ?>
                    <div class="edl-notes"><input type="text" name="<?= $nm ?>[notes]" placeholder="Observations"><?= $rmBtn ?></div>
                </div></template>
                <div class="room-foot"><button type="button" class="btn btn-sm row-add" data-add>+ Ajouter une ligne</button></div>
            </div></template>
            <button type="button" class="btn btn-sm" data-add-room>+ Ajouter une pièce</button>
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
                    <?php if ($sortie): ?><th>Qté sortie</th><th>État sortie</th><?php endif; ?><th>Observations</th><th></th></tr></thead>
                <tbody data-rows>
                <?php foreach ($g['items'] as $ii => $it): $nm = "inventory[$gi][items][$ii]"; ?>
                    <tr data-row><td><?= e($it['label']) ?></td>
                        <td><input class="qty" name="<?= $nm ?>[in_qty]" value="<?= e($it['in_qty']) ?>" <?= $inRo ?>></td>
                        <td><?= $stateSelect($nm . '[in_state]', $it['in_state'], $inRo) ?></td>
                        <?php if ($sortie): ?>
                            <td><input class="qty" name="<?= $nm ?>[out_qty]" value="<?= e($it['out_qty']) ?>"></td>
                            <td><?= $stateSelect($nm . '[out_state]', $it['out_state']) ?></td>
                        <?php endif; ?>
                        <td><input name="<?= $nm ?>[notes]" value="<?= e($it['notes']) ?>"></td>
                        <td class="row-act"><?= $delBtn($nm) ?></td></tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($canEdit): $nm = "inventory[$gi][items][new][__N__]"; ?>
                <template><tr data-row><td><input name="<?= $nm ?>[label]" placeholder="Élément" data-focus></td>
                    <td><input class="qty" name="<?= $nm ?>[in_qty]"></td>
                    <td><?= $stateSelect($nm . '[in_state]', '') ?></td>
                    <?php if ($sortie): ?><td><input class="qty" name="<?= $nm ?>[out_qty]"></td><td><?= $stateSelect($nm . '[out_state]', '') ?></td><?php endif; ?>
                    <td><input name="<?= $nm ?>[notes]"></td><td class="row-act"><?= $rmBtn ?></td></tr></template>
                <button type="button" class="btn btn-sm row-add" data-add>+ Ajouter une ligne</button>
            <?php endif; ?>
            </div>
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
<script>
(function () {
    var form = document.currentScript.previousElementSibling;
    var seq = 0;
    // Ajouter une ligne : copie du modèle <template> du bloc, avec un numéro unique.
    function addRow(btn) {
        var box = btn.closest('.edl-room, .table-wrap, fieldset');
        var tpl = box.querySelector(':scope > template, :scope > .table-wrap > template') || box.querySelector('template');
        var rows = box.querySelector('[data-rows]');
        var html = tpl.innerHTML.replace(/__N__/g, 'n' + Date.now() + (seq++));
        var holder = document.createElement(rows.tagName === 'TBODY' ? 'tbody' : 'div');
        holder.innerHTML = html;
        var row = holder.firstElementChild;
        rows.appendChild(row);
        var f = row.querySelector('[data-focus]');
        if (f) f.focus();
    }
    form.addEventListener('click', function (e) {
        var b = e.target.closest('button');
        if (!b || !form.contains(b)) return;
        if (b.hasAttribute('data-add')) { addRow(b); return; }
        if (b.hasAttribute('data-add-room')) {
            var t = document.getElementById('room-tpl');
            var div = document.createElement('div');
            div.innerHTML = t.innerHTML.replace(/__R__/g, 'r' + Date.now() + (seq++));
            var room = div.firstElementChild;
            form.querySelector('[data-rooms]').appendChild(room);
            addRow(room.querySelector('[data-add]'));
            room.querySelector('h3 [data-focus]').focus();
            return;
        }
        if (b.hasAttribute('data-remove')) { b.closest('[data-row]').remove(); return; }
        if (b.hasAttribute('data-del')) {
            // Ligne existante : marquée « supprimée » jusqu'à l'enregistrement, annulable.
            var row = b.closest('[data-row]');
            var flag = b.parentNode.querySelector('[data-del-flag]');
            var del = !row.classList.contains('is-deleted');
            row.classList.toggle('is-deleted', del);
            flag.value = del ? '1' : '';
            b.textContent = del ? '↺' : '✕';
            b.title = del ? 'Rétablir' : 'Supprimer la ligne';
        }
    });
    // Après l'enregistrement : retour au même endroit de la page.
    var key = 'edl-scroll:' + location.pathname;
    form.addEventListener('submit', function () {
        try { sessionStorage.setItem(key, String(window.scrollY)); } catch (e) {}
    });
    try {
        var y = sessionStorage.getItem(key);
        if (y !== null) {
            sessionStorage.removeItem(key);
            window.scrollTo(0, parseInt(y, 10) || 0);
            var flash = document.querySelector('.flash');
            if (flash) {
                var toast = flash.cloneNode(true);
                toast.className += ' edl-toast';
                document.body.appendChild(toast);
                setTimeout(function () { toast.remove(); }, 3500);
            }
        }
    } catch (e) {}
})();
</script>
