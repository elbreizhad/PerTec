<?php /** @var array $leases */ ?>
<div class="page-head">
    <h1>Baux</h1>
    <a href="<?= url('/baux/new') ?>" class="btn btn-primary">+ Nouveau bail</a>
</div>

<?php if (!$leases): ?>
    <div class="card empty">Aucun bail. Créez d'abord un bien et un locataire.</div>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Bien</th><th>Locataire</th><th>Type</th><th>Début</th><th class="num">Loyer CC</th><th>Statut</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($leases as $l): ?>
        <tr>
            <td><a href="<?= url('/biens/'.$l['property_id']) ?>"><?= e($l['property_label']) ?></a></td>
            <td><?= e($l['first_name'].' '.$l['last_name']) ?></td>
            <td><?= $l['lease_type']==='meuble'?'Meublé':'Vide' ?></td>
            <td><?= fdate($l['start_date']) ?></td>
            <td class="num"><?= euros((float)$l['rent_amount'] + (float)$l['charges_amount']) ?></td>
            <td><span class="badge badge-<?= e($l['status']) ?>"><?= $l['status']==='active'?'Actif':'Terminé' ?></span></td>
            <td class="right"><a href="<?= url('/baux/'.$l['id']) ?>" class="btn btn-sm">Gérer</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
