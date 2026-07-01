<?php /** @var string $content */ ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document — PerTec</title>
    <link rel="stylesheet" href="<?= url('/assets/print.css') ?>">
</head>
<body class="print-body">
<div class="print-toolbar no-print">
    <button onclick="window.print()" class="btn btn-primary">🖨️ Imprimer / Enregistrer en PDF</button>
    <a href="javascript:history.back()" class="btn">Retour</a>
</div>
<div class="sheet">
    <?= $content ?>
</div>
</body>
</html>
