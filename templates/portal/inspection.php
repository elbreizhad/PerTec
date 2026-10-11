<?php /** @var array $insp */ /** @var array $lease */ /** @var array $settings */ /** @var int $preview */ /** @var callable $link */
$l = $lease;
$d = $insp['data'];
$id = (int) $insp['id'];
$sortie = $insp['kind'] === 'sortie';
$sigs = Inspection::valid($insp, $l, $settings);
$complete = count($sigs) === count(Inspection::ROLES);
$mine = $sigs['locataire'] ?? null;
$editable = !$mine && !$complete;
[$done, $total] = Inspection::progress($insp);
?>
<?php if ($preview): ?>
    <div class="flash" style="background:#eff6ff;color:#1e3a8a;border:1px solid #bfdbfe">
        👁 <strong>Aperçu administrateur</strong> — le locataire voit cette page ; la saisie et la signature sont désactivées dans l'aperçu.
        <a href="<?= url('/edl/' . $id) ?>">← Retour à l'état des lieux</a></div>
<?php endif; ?>

<p><a href="<?= $link('/locataire/espace') ?>">← Mon espace</a></p>
<div class="page-head">
    <div>
        <h1><?= e(Inspection::title($insp)) ?></h1>
        <p class="muted"><?= e(trim(($l['address'] ?? '') . ', ' . ($l['postal_code'] ?? '') . ' ' . ($l['city'] ?? ''), ', ') ?: $l['property_label']) ?>
            <?= $insp['inspected_at'] ? ' · ' . e(date('d/m/Y à H\hi', strtotime($insp['inspected_at']))) : '' ?></p>
    </div>
    <a class="btn btn-secondary" href="<?= $link('/locataire/edl/' . $id . '/pdf') ?>" target="_blank">📄 <?= $complete ? 'Version signée (PDF)' : 'Voir en PDF' ?></a>
</div>

<div class="card">
    <h3>Où en êtes-vous ?</h3>
    <?php $steps = [
        ['Remplir l\'état des lieux pièce par pièce', $done > 0 && $done === $total, $done . '/' . $total . ' élément(s) renseigné(s)'],
        ['Signer l\'état des lieux', (bool) $mine, $mine ? 'Signé le ' . date('d/m/Y', strtotime($mine['signed_at'])) : 'Après avoir tout vérifié'],
        ['Signature du bailleur', isset($sigs['bailleur']), isset($sigs['bailleur']) ? 'Signé le ' . date('d/m/Y', strtotime($sigs['bailleur']['signed_at'])) : 'En attente'],
    ]; ?>
    <?php foreach ($steps as [$label, $ok, $hint]): ?>
        <div class="task <?= $ok ? 'task-ok' : 'task-ko' ?>">
            <span class="task-ico"><?= $ok ? '✔' : '✘' ?></span>
            <div class="task-body"><strong><?= e($label) ?></strong><span class="small"><?= e($hint) ?></span></div>
        </div>
    <?php endforeach; ?>
</div>

<?php if ($complete): ?>
    <div class="card"><h3>✔ État des lieux signé par les deux parties</h3>
        <p class="muted">Conservez le PDF signé : il fait foi<?= $sortie ? ' pour la restitution de votre dépôt de garantie' : ' et sera comparé à l\'état des lieux de sortie' ?>.</p></div>
<?php elseif ($mine): ?>
    <div class="card"><h3>✔ Vous avez signé le <?= e(date('d/m/Y à H:i', strtotime($mine['signed_at']))) ?></h3>
        <p class="muted">L'état des lieux sera complet après la signature du bailleur.</p></div>
<?php endif; ?>

<?php if ($editable): ?>
    <h2>1. Remplir l'état des lieux</h2>
    <p class="small muted">Faites le tour du logement : pour chaque élément, choisissez son état et notez tout défaut (trace, rayure, tache, casse, manque, mauvais fonctionnement).
        <?= $sortie ? '' : 'Relevez aussi les compteurs et comptez les clés remises.' ?> Enregistrez autant de fois que nécessaire.</p>
    <?php view('inspections/form', ['insp' => $insp, 'lease' => $l, 'action' => url('/locataire/edl/' . $id), 'locked' => (bool) $preview, 'tenantMode' => true], null); ?>

    <h2 class="mt">2. Signer</h2>
    <form method="post" action="<?= url('/locataire/edl/' . $id . '/signer') ?>" class="card" id="sign-form">
        <?= csrf_field() ?>
        <fieldset <?= $preview ? 'disabled' : '' ?> style="border:none;padding:0;margin:0;background:none">
        <p class="small muted">Relisez le document (bouton « Voir en PDF » en haut) puis signez. Après votre signature, vous ne pourrez plus le modifier.</p>
        <div class="form-grid">
            <div class="field"><label>Nom et prénom du signataire</label><input name="signer_name" value="<?= e(trim($l['first_name'] . ' ' . $l['last_name'])) ?>" required></div>
        </div>
        <?php if (!$preview) view('partials/signature_draw', ['formId' => 'sign-form', 'savedSignature' => null], null); ?>
        <div class="field mt">
            <label style="font-weight:400"><input type="checkbox" name="approve" value="1" required style="width:auto">
                <strong>Lu et approuvé.</strong> J'ai relu l'état des lieux et je le signe électroniquement.</label>
        </div>
        <p class="small muted">Signature électronique (art. 1366 et 1367 du Code civil) : la date, l'heure, l'adresse IP, le navigateur et
            l'empreinte numérique du document sont enregistrés comme preuve.</p>
        <button class="btn btn-primary">Signer l'état des lieux</button>
        </fieldset>
    </form>
<?php else: ?>
    <div class="card">
        <iframe src="<?= $link('/locataire/edl/' . $id . '/document') ?>" title="État des lieux" style="width:100%;height:70vh;border:1px solid #d1d5db;border-radius:8px;background:#fff"></iframe>
    </div>
<?php endif; ?>
