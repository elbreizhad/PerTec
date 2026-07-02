<div class="card center">
    <h1><?= e($title ?? 'Erreur') ?></h1>
    <p class="muted"><?= e($message ?? '') ?></p>
    <a href="<?= url('/') ?>" class="btn btn-primary mt">Retour à l'accueil</a>
</div>
