<main class="fp-fade fp-page">
    <p class="fp-eyebrow">Equipo</p>
    <div style="display:flex;align-items:center;gap:18px;flex-wrap:wrap;">
        <div class="fp-glass fp-glass-green" style="width:64px;height:64px;border-radius:18px;display:flex;align-items:center;justify-content:center;font-size:32px;">
            <?= e($team['badge'] ?? '🛡️') ?>
        </div>
        <div style="flex:1;min-width:240px;">
            <h1 class="fp-h1" style="margin:0;"><?= e($team['name']) ?></h1>
            <p style="margin:6px 0 0;color:#9ca3af;font-size:14px;">📍 <?= e($team['city']) ?> · 🛡️ Capitán: <?= e($team['captain_name']) ?></p>
        </div>
        <?php if (is_auth()): ?>
            <?php if (!$isMember): ?>
                <form method="post" action="<?= url('teams/join/' . (int) $team['id']) ?>" style="margin:0;">
                    <?= csrf_field() ?>
                    <button class="fp-btn fp-btn-primary fp-btn-glow">Unirme al equipo →</button>
                </form>
            <?php elseif ((int) $team['captain_id'] !== (int) current_user()['id']): ?>
                <form method="post" action="<?= url('teams/leave/' . (int) $team['id']) ?>" style="margin:0;" onsubmit="return confirm('¿Seguro que quieres dejar el equipo?');">
                    <?= csrf_field() ?>
                    <button class="fp-btn fp-btn-ghost">Dejar equipo</button>
                </form>
            <?php endif; ?>
            <?php if ((int) $team['captain_id'] === (int) current_user()['id'] || is_admin()): ?>
                <form method="post" action="<?= url('teams/delete/' . (int) $team['id']) ?>" style="margin:0;" onsubmit="return confirm('¿Eliminar el equipo? Esta acción es permanente.');">
                    <?= csrf_field() ?>
                    <button class="fp-btn fp-btn-ghost" style="color:#f87171;">Eliminar</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <section style="margin-top:32px;">
        <h2 class="fp-h2">Plantilla (<?= count($members) ?>)</h2>
        <?php if (empty($members)): ?>
            <div class="fp-empty">Aún no hay miembros.</div>
        <?php else: ?>
            <div class="fp-grid-3">
                <?php foreach ($members as $m): ?>
                    <div class="fp-glass" style="border-radius:14px;padding:18px;display:flex;align-items:center;gap:14px;">
                        <span style="width:42px;height:42px;border-radius:9999px;background:#16a34a;color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:700;"><?= e(mb_substr($m['name'], 0, 1)) ?></span>
                        <div style="flex:1;">
                            <div style="font-weight:700;font-size:14px;"><?= e($m['name']) ?> <?php if ((int) $m['is_captain'] === 1): ?><span style="color:#fbbf24;font-size:11px;">★ capitán</span><?php endif; ?></div>
                            <div style="font-size:11px;color:#6b7280;"><?= e($m['position'] ?? 'Sin posición') ?> · <?= e($m['city'] ?? '—') ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>