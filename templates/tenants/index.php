<?php /** @var array $tenants */ ?>
<div class="page-head">
    <h1>Locataires</h1>
    <a href="<?= url('/locataires/new') ?>" class="btn btn-primary">+ Nouveau locataire</a>
</div>

<?php if (!$tenants): ?>
    <div class="card empty">Aucun locataire enregistré.</div>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Nom</th><th>Email</th><th>Téléphone</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($tenants as $t): ?>
        <tr>
            <td><strong><?= e($t['first_name'].' '.$t['last_name']) ?></strong></td>
            <td><?= $t['email'] ? '<a href="mailto:'.e($t['email']).'">'.e($t['email']).'</a>' : '—' ?></td>
            <td><?= e($t['phone'] ?: '—') ?></td>
            <td class="right actions" style="justify-content:flex-end">
                <a href="<?= url('/locataires/'.$t['id'].'/edit') ?>" class="btn btn-sm">Modifier</a>
                <form class="inline-form" method="post" action="<?= url('/locataires/'.$t['id'].'/delete') ?>" onsubmit="return confirm('Supprimer ce locataire ?')"><?= csrf_field() ?><button class="btn btn-sm btn-danger">×</button></form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
