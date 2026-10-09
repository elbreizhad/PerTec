<?php /** @var array $tenants */ ?>
<div class="page-head">
    <div><h1>Locataires</h1>
        <p class="muted small">Espace locataire : <a href="<?= url('/locataire') ?>" target="_blank"><?= e(LeaseSignature::absoluteUrl('/locataire')) ?></a> — connexion par code envoyé par email.</p></div>
    <a href="<?= url('/locataires/new') ?>" class="btn btn-primary">+ Nouveau locataire</a>
</div>

<?php if (!$tenants): ?>
    <div class="card empty">Aucun locataire enregistré.</div>
<?php else: ?>
<div class="table-wrap"><table>
    <thead><tr><th>Nom</th><th>Email</th><th>Téléphone</th><th>Espace locataire</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($tenants as $t): ?>
        <tr>
            <td><strong><?= e($t['first_name'].' '.$t['last_name']) ?></strong></td>
            <td><?= $t['email'] ? '<a href="mailto:'.e($t['email']).'">'.e($t['email']).'</a>' : '—' ?></td>
            <td><?= e($t['phone'] ?: '—') ?></td>
            <td class="small muted"><?= !empty($t['portal_last_login']) ? 'Dernière connexion le ' . e(date('d/m/Y', strtotime($t['portal_last_login']))) : ($t['email'] ? 'Jamais connecté' : 'Email manquant') ?></td>
            <td class="right actions" style="justify-content:flex-end">
                <a href="<?= url('/locataires/'.$t['id'].'/espace') ?>" class="btn btn-sm" target="_blank" title="Voir son espace locataire tel qu'il le voit">👁 Voir son espace</a>
                <?php if ($t['email']): ?>
                    <form class="inline-form" method="post" action="<?= url('/locataires/'.$t['id'].'/invitation') ?>" onsubmit="return confirm('Envoyer à <?= e($t['email']) ?> le lien de son espace locataire ?')"><?= csrf_field() ?><button class="btn btn-sm" title="Envoyer le lien de l'espace locataire">📧 Inviter</button></form>
                <?php endif; ?>
                <a href="<?= url('/locataires/'.$t['id'].'/edit') ?>" class="btn btn-sm">Modifier</a>
                <form class="inline-form" method="post" action="<?= url('/locataires/'.$t['id'].'/delete') ?>" onsubmit="return confirm('Supprimer ce locataire ?')"><?= csrf_field() ?><button class="btn btn-sm btn-danger">×</button></form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table></div>
<?php endif; ?>
