<?php /** @var array $groups */ /** @var callable $link */ /** @var int $preview */
// Démarches du locataire : ✔ vert = fait, ✘ rouge = à faire.
$all = array_merge(...array_values($groups));
$done = count(array_filter($all, fn($t) => $t['done']));
?>
<div class="tasks">
    <div class="tasks-progress">
        <div class="progress-bar"><span style="width:<?= $all ? round($done / count($all) * 100) : 0 ?>%"></span></div>
        <strong><?= $done ?>/<?= count($all) ?> fait<?= $done > 1 ? 's' : '' ?></strong>
    </div>
    <?php foreach ($groups as $title => $tasks):
        $left = count(array_filter($tasks, fn($t) => !$t['done'])); ?>
        <h3><?= e($title) ?> <span class="muted">— <?= $left ? $left . ' à faire' : 'tout est fait' ?></span></h3>
        <?php foreach ($tasks as $t): ?>
            <div class="task <?= $t['done'] ? 'task-ok' : 'task-ko' ?>">
                <span class="task-ico" aria-label="<?= $t['done'] ? 'Fait' : 'À faire' ?>"><?= $t['done'] ? '✔' : '✘' ?></span>
                <div class="task-body"><strong><?= e($t['label']) ?></strong><span class="small"><?= e($t['hint'] ?? '') ?></span></div>
                <?php if (!$t['done'] && !empty($t['post'])): ?>
                    <form method="post" action="<?= url($t['post']) ?>"><?= csrf_field() ?>
                        <button class="btn btn-sm btn-primary" <?= $preview ? 'disabled title="Désactivé dans l\'aperçu"' : '' ?>><?= e($t['button']) ?></button></form>
                <?php elseif (!$t['done'] && !empty($t['url'])): ?>
                    <a class="btn btn-sm btn-primary" href="<?= str_starts_with($t['url'], 'mailto:') ? e($t['url']) : $link($t['url']) ?>"><?= e($t['button']) ?></a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
</div>
