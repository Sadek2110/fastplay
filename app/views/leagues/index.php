<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Competiciones</p>
            <h1 class="fp-h1">Ligas activas</h1>
        </div>
        <?php if (is_admin()): ?>
            <a href="<?= url('leagues/create') ?>" class="fp-btn fp-btn-primary fp-btn-glow">+ Nueva liga</a>
        <?php endif; ?>
    </div>

    <?php if (empty($leagues)): ?>
        <div class="fp-empty">⏳ Aún no hay ligas activas.</div>
    <?php else: ?>
        <div class="fp-grid-2">
            <?php foreach ($leagues as $l): ?>
                <a href="<?= url('leagues/show/' . (int) $l['id']) ?>" style="cursor:pointer;border-radius:18px;padding:22px;text-decoration:none;color:#fff;display:block;position:relative;
                    background:<?= $l['pro'] ? 'linear-gradient(135deg,rgba(22,163,74,.12),rgba(22,163,74,.04))' : 'rgba(255,255,255,.04)' ?>;
                    border:<?= $l['pro'] ? '1px solid rgba(245,158,11,.20)' : '1px solid rgba(255,255,255,.08)' ?>;">
                    <?php if ($l['pro']): ?>
                        <span style="position:absolute;top:16px;right:16px;font-size:18px;">🏆</span>
                    <?php endif; ?>
                    <div style="font-size:18px;font-weight:900;margin-bottom:6px;padding-right:32px;"><?= e($l['name']) ?></div>
                    <div style="font-size:12px;color:#9ca3af;margin-bottom:14px;">📍 <?= e($l['city']) ?> · 👥 <?= (int) ($l['team_count'] ?? 0) ?>/<?= (int) $l['max_teams'] ?> equipos</div>
                    <div style="display:flex;justify-content:space-between;font-size:11px;">
                        <span style="color:#4b5563;"><?= e($l['start']) ?> – <?= e($l['end']) ?></span>
                        <span style="color:#4ade80;font-weight:600;">Ver →</span>
                    </div>
                    <?php if (!empty($l['prize'])): ?>
                        <div style="margin-top:12px;display:inline-block;padding:3px 10px;border-radius:9999px;font-size:11px;color:#fbbf24;background:rgba(245,158,11,.10);border:1px solid rgba(245,158,11,.20);font-weight:500;">
                            💰 Premio: <?= number_format((float) $l['prize'], 2, ',', '.') ?> €
                        </div>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>