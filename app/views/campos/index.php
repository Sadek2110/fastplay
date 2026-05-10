<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Campos</p>
            <h1 class="fp-h1">Reserva tu cancha</h1>
        </div>
        <?php if (is_admin()): ?>
            <a href="<?= url('campos/create') ?>" class="fp-btn fp-btn-primary fp-btn-glow">+ Nuevo campo</a>
        <?php endif; ?>
    </div>

    <?php if (empty($fields)): ?>
        <div class="fp-empty">⏳ Todavía no hay campos disponibles.</div>
    <?php else: ?>
        <div class="fp-grid-3">
            <?php foreach ($fields as $f): ?>
                <a href="<?= url('campos/show/' . (int) $f['id']) ?>" class="fp-glass fp-card-link" style="border-radius:18px;padding:22px;text-decoration:none;color:#fff;display:block;">
                    <div style="font-size:30px;">🏟️</div>
                    <h3 style="font-size:16px;font-weight:900;margin:10px 0 4px;"><?= e($f['name']) ?></h3>
                    <p style="font-size:12px;color:#6b7280;margin:0 0 12px;">📍 <?= e($f['city']) ?> · <?= e($f['surface']) ?></p>
                    <div style="display:flex;justify-content:space-between;font-size:12px;color:#9ca3af;">
                        <span>👥 <?= (int) $f['capacity'] ?></span>
                        <span>💶 <?= number_format((float) $f['hourly_rate'], 2, ',', '.') ?> €/h</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>