<div class="fp-empty-state">
    <i class="bi <?= e($icon ?? 'bi-inbox') ?>"></i>
    <h3><?= e($title ?? 'Sin resultados') ?></h3>
    <p><?= e($description ?? 'No hay contenido para mostrar.') ?></p>
    <?php if (!empty($ctaUrl) && !empty($ctaLabel)): ?>
        <a href="<?= url($ctaUrl) ?>" class="fp-btn fp-btn-primary"><?= e($ctaLabel) ?></a>
    <?php endif; ?>
</div>
