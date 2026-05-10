<main class="fp-fade" style="max-width:1280px;margin:0 auto;padding:96px 24px 80px;">

    <div style="margin-bottom:36px;">
        <p style="color:#6b7280;font-size:13px;margin-bottom:4px;">Bienvenido de vuelta</p>
        <h1 style="font-size:30px;font-weight:900;margin:0;">Hola, <span class="fp-gradient-text"><?= e($user['name']) ?></span> 👋</h1>
    </div>

    <div class="fp-grid-4" style="margin-bottom:36px;">
        <?php foreach ($stats as $s): ?>
            <div class="fp-glass" style="border-radius:18px;padding:20px;">
                <div style="font-size:22px;margin-bottom:10px;"><?= e($s['i']) ?></div>
                <div style="font-size:30px;font-weight:900;color:<?= e($s['c']) ?>;letter-spacing:-.02em;line-height:1;"><?= (int) $s['v'] ?></div>
                <div style="font-size:11px;color:#6b7280;margin-top:6px;font-weight:500;"><?= e($s['l']) ?></div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="fp-grid-3">
        <!-- Mi equipo -->
        <div class="fp-glass" style="border-radius:18px;padding:24px;">
            <h2 style="font-size:16px;font-weight:900;margin-bottom:18px;">Mi equipo</h2>
            <?php if (!empty($team)): ?>
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:22px;">
                    <div class="fp-glass fp-glass-green" style="width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;"><?= e($team['badge'] ?? '🛡️') ?></div>
                    <div>
                        <div style="font-weight:700;"><?= e($team['name']) ?></div>
                        <div style="font-size:13px;color:#9ca3af;">📍 <?= e($team['city']) ?></div>
                    </div>
                </div>
                <a href="<?= url('teams/show/' . (int) $team['id']) ?>" class="fp-btn fp-btn-ghost" style="width:100%;justify-content:center;padding:10px 0;font-size:13px;">Ver equipo →</a>
            <?php else: ?>
                <p style="font-size:13px;color:#9ca3af;margin:0 0 14px;">Aún no tienes equipo. ¡Crea uno o únete a uno existente!</p>
                <a href="<?= url('teams/create') ?>" class="fp-btn fp-btn-primary fp-btn-glow" style="width:100%;justify-content:center;padding:10px 0;font-size:13px;">Crear equipo →</a>
            <?php endif; ?>
        </div>

        <!-- Próximos partidos -->
        <div class="fp-glass" style="border-radius:18px;padding:24px;">
            <h2 style="font-size:16px;font-weight:900;margin-bottom:18px;">Próximos partidos</h2>
            <div style="display:flex;flex-direction:column;gap:10px;">
                <?php foreach ($upcoming as $m): ?>
                    <div style="display:flex;align-items:center;gap:12px;padding:10px;border-radius:12px;">
                        <span style="width:8px;height:8px;border-radius:9999px;background:#4ade80;flex-shrink:0;"></span>
                        <div style="flex:1;">
                            <div style="font-size:12px;font-weight:600;"><?= e($m['home']) ?> vs <?= e($m['away']) ?></div>
                            <div style="font-size:11px;color:#6b7280;"><?= e($m['when']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Logros -->
        <div class="fp-glass" style="border-radius:18px;padding:24px;">
            <h2 style="font-size:16px;font-weight:900;margin-bottom:18px;">Logros recientes</h2>
            <?php if (empty($achievements)): ?>
                <p style="font-size:13px;color:#9ca3af;margin:0;">Aún sin logros. ¡Juega tu primer partido para empezar a sumar!</p>
            <?php else: ?>
                <div style="display:flex;flex-direction:column;gap:10px;">
                    <?php foreach ($achievements as $a): ?>
                        <div style="display:flex;align-items:center;gap:12px;padding:10px;border-radius:12px;background:rgba(255,255,255,.05);">
                            <span style="font-size:22px;"><?= e($a['i']) ?></span>
                            <div>
                                <div style="font-size:12px;font-weight:600;"><?= e($a['n']) ?></div>
                                <div style="font-size:11px;color:#6b7280;"><?= e($a['d']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</main>
