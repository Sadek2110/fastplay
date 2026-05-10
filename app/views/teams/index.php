<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Directorio</p>
            <h1 class="fp-h1">Equipos</h1>
        </div>
        <?php if (is_auth()): ?>
            <a href="<?= url('teams/create') ?>" class="fp-btn fp-btn-primary fp-btn-glow">+ Crear equipo</a>
        <?php endif; ?>
    </div>

    <?php if (empty($teams)): ?>
        <div class="fp-empty">⚽ Aún no hay equipos. ¡Sé el primero en crear uno!</div>
    <?php else: ?>
        <div class="fp-grid-4">
            <?php foreach ($teams as $t): ?>
                <a href="<?= url('teams/show/' . (int) $t['id']) ?>" class="fp-glass fp-card-link" style="border-radius:18px;padding:22px;display:block;text-decoration:none;color:#fff;">
                    <div class="fp-glass fp-glass-green" style="width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:14px;"><?= e($t['badge'] ?? '🛡️') ?></div>
                    <h3 style="font-size:15px;font-weight:900;margin:0 0 4px;"><?= e($t['name']) ?></h3>
                    <p style="font-size:11px;color:#6b7280;margin:0 0 6px;">📍 <?= e($t['city']) ?></p>
                    <p style="font-size:11px;color:#6b7280;margin:0 0 14px;">🛡️ Capitán: <?= e($t['captain_name']) ?></p>
                    <div style="display:flex;justify-content:space-between;font-size:11px;">
                        <span style="color:#4b5563;"><?= (int) $t['players'] ?> jugadores</span>
                        <span style="color:#4ade80;font-weight:600;">Ver →</span>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>