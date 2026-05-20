<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="description" content="FastPlay — Plataforma de fútbol amateur. Crea equipos, organiza partidos y compite en ligas locales.">
<meta name="csrf-token" content="<?= e(csrf_token()) ?>">
<meta name="referrer" content="strict-origin-when-cross-origin">
<title><?= e($title ?? 'FastPlay') ?></title>
<link rel="icon" type="image/x-icon" href="<?= asset('images/icono-pag.ico') ?>">
<link rel="apple-touch-icon" href="<?= asset('apple-touch-icon.png') ?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="<?= asset('css/app.css') ?>">
<?= $head ?? '' ?>
</head>
<body>
<div class="fp-bg-glow"></div>

<?php $this->partial('navbar', ['active' => $active ?? '']); ?>

<div class="fp-main-content">
    <?php $this->partial('flash', ['_flash' => $_flash ?? []]); ?>
    <div id="app">
        <?= $content ?>
    </div>
    <?php $this->partial('footer'); ?>
</div>

<script src="<?= asset('js/theme.js') ?>" defer></script>
<script src="<?= asset('js/nav.js') ?>" defer></script>
<script src="<?= asset('js/fifa-card.js') ?>" defer></script>
<script>window.FP_BASE_URL = "<?= e(BASE_URL) ?>";</script>
<?= $scripts ?? '' ?>
</body>
</html>
