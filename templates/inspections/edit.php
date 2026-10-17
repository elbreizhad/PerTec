<?php /** @var array $insp */ /** @var array $lease */ /** @var array $settings */
$l = $lease;
$d = $insp['data'];
$id = (int) $insp['id'];
$sortie = $insp['kind'] === 'sortie';
$meuble = $l['lease_type'] === 'meuble';
$sigAll = Inspection::signatures($id);
$sigValid = $sigAll ? Inspection::valid($insp, $l, $settings) : [];
$locked = count($sigValid) === count(Inspection::ROLES);
[$done, $total] = Inspection::progress($insp);
?>
<div class="page-head">
    <div>
        <h1><?= e(Inspection::title($insp)) ?></h1>
        <p class="muted"><a href="<?= url('/baux/' . $l['id'] . '#edl') ?>">Bail — <?= e($l['first_name'] . ' ' . $l['last_name']) ?></a>
            · <?= e($l['property_label']) ?> · <?= $done ?>/<?= $total ?> élément(s) renseigné(s)
            <?php if ($insp['shared_at']): ?>· <span class="badge badge-active">Visible dans l'espace locataire</span><?php endif; ?></p>
    </div>
    <div class="actions">
        <a href="<?= url('/edl/' . $id . '/document') ?>" class="btn btn-secondary" target="_blank">📄 Aperçu / PDF</a>
        <a href="<?= url('/baux/' . $l['id'] . '#edl') ?>" class="btn">Retour au bail</a>
    </div>
</div>

<?php if ($sigAll && count($sigValid) < count($sigAll)): ?>
    <div class="flash flash-error">L'état des lieux a été modifié après une signature : elle ne correspond plus à cette version. Faites signer à nouveau.</div>
<?php endif; ?>

<div class="card">
    <h3>Signatures et partage</h3>
    <?php foreach (Inspection::ROLES as $role => $label): $sg = $sigValid[$role] ?? null; ?>
        <div class="actions" style="justify-content:space-between;padding:.5rem 0;border-bottom:1px solid var(--line)">
            <div><strong><?= $label ?></strong> —
                <?= $sg ? '<span class="badge badge-paid">Signé</span> <span class="small">par ' . e($sg['signer_name']) . ' le ' . e(date('d/m/Y à H:i', strtotime($sg['signed_at']))) . '</span>'
                        : '<span class="badge badge-pending">À signer</span>' ?></div>
            <?php if (!$sg): ?><a class="btn btn-sm btn-primary" href="<?= url('/edl/' . $id . '/signer/' . $role) ?>">
                <?= $role === 'bailleur' ? '✍️ Signer' : '✍️ Faire signer le locataire ici' ?></a><?php endif; ?>
        </div>
    <?php endforeach; ?>
    <form method="post" action="<?= url('/edl/' . $id . '/partage') ?>" class="mt">
        <?= csrf_field() ?>
        <?php if ($insp['shared_at']): ?>
            <p class="small">Le locataire voit cet état des lieux dans son espace depuis le <?= fdate($insp['shared_at']) ?> :
                il peut le consulter<?= $sortie ? ', indiquer sa nouvelle adresse et son IBAN' : '' ?>, ajouter ses observations et le signer.</p>
            <div class="actions">
                <button class="btn btn-sm" name="action" value="notify">📧 Prévenir le locataire par email</button>
                <button class="btn btn-sm" name="action" value="unshare">Retirer de l'espace locataire</button>
            </div>
        <?php else: ?>
            <p class="small muted">Pour que le locataire le consulte et le signe à distance, mettez-le à disposition dans son espace.
                Sur place, vous pouvez aussi le faire signer directement sur cet appareil.</p>
            <div class="actions">
                <button class="btn btn-sm btn-primary" name="action" value="share_notify">Mettre à disposition + prévenir par email</button>
                <button class="btn btn-sm" name="action" value="share">Mettre à disposition sans email</button>
            </div>
        <?php endif; ?>
    </form>
    <div class="actions mt">
        <?php if ($locked && ($pdf = Inspection::signedPdf($id))): ?>
            <a class="btn btn-primary" href="<?= url('/edl/' . $id . '/document/pdf') ?>" target="_blank">📄 État des lieux signé (PDF du <?= e(date('d/m/Y', strtotime($pdf['created_at']))) ?>)</a>
        <?php endif; ?>
        <?php if ($sigAll): ?>
            <form method="post" action="<?= url('/edl/' . $id . '/signatures/reset') ?>" class="inline-form" onsubmit="return confirm('Annuler les signatures pour modifier l\'état des lieux ?')">
                <?= csrf_field() ?><button class="btn btn-sm">Annuler les signatures</button></form>
        <?php endif; ?>
    </div>
</div>

<?php if (!$sigAll):
    $prop = Property::find((int) $l['property_id']) ?? [];
    $tplInfo = Inspection::template($prop);
    $invCount = array_sum(array_map(fn($g) => count($g['items']), $insp['data']['inventory'])); ?>
<div class="card">
    <h3>Contenu de l'état des lieux</h3>
    <p class="small muted" style="margin-top:0">Pièces, clés et inventaire viennent du
        <a href="<?= url('/biens/' . $l['property_id'] . '#edl') ?>">modèle d'état des lieux du logement</a>
        (<?= $tplInfo['custom'] ? 'personnalisé' : 'modèle générique par défaut' ?>)<?= $insp['kind'] === 'sortie' ? ", ou de l'état des lieux d'entrée" : '' ?>.
        <?= $meuble ? $invCount . ' élément(s) d\'inventaire.' : '' ?> Mettre à jour conserve tout ce qui est déjà saisi.</p>
    <form method="post" action="<?= url('/edl/' . $id . '/modele') ?>" class="actions">
        <?= csrf_field() ?>
        <button class="btn btn-sm btn-primary" name="action" value="example"
            onclick="return confirm('Remplacer les pièces, les clés et l\'inventaire du modèle de ce logement par le modèle complet (studio meublé : 5 pièces, 58 éléments d\'inventaire), puis mettre à jour cet état des lieux ? Ce qui est déjà saisi est conservé.')">
            📋 Charger le modèle complet (studio meublé, PDF)</button>
        <button class="btn btn-sm" name="action" value="reload">🔄 Mettre à jour depuis le modèle du logement</button>
    </form>
</div>
<?php endif; ?>

<?php if ($locked): ?>
    <div class="flash flash-success">État des lieux signé par les deux parties : il n'est plus modifiable. Annulez les signatures pour le corriger.</div>
<?php endif; ?>

<?php view('inspections/form', ['insp' => $insp, 'lease' => $l, 'action' => url('/edl/' . $id), 'locked' => $locked, 'tenantMode' => false], null); ?>

<?php if (!$sigAll): ?>
<form method="post" action="<?= url('/edl/' . $id . '/delete') ?>" class="mt" onsubmit="return confirm('Supprimer cet état des lieux ?')">
    <?= csrf_field() ?><button class="btn btn-danger btn-sm">Supprimer cet état des lieux</button>
</form>
<?php endif; ?>
