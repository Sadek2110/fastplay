<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Calendario</p>
            <h1 class="fp-h1">Partidos</h1>
        </div>
        <?php if (is_auth()): ?>
            <a href="<?= url('matches/create') ?>" class="fp-btn fp-btn-primary fp-btn-glow">+ Nuevo partido</a>
        <?php endif; ?>
    </div>

    <?php if (empty($matches)): ?>
        <div class="fp-empty">⚽ No hay partidos programados todavía.</div>
    <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:10px;">
            <?php foreach ($matches as $m): ?>
                <a href="<?= url('matches/show/' . (int) $m['id']) ?>" class="fp-glass fp-match-row fp-card-link" style="text-decoration:none;color:#fff;">
                    <div style="min-width:80px;text-align:center;">
                        <div style="font-size:22px;font-weight:900;line-height:1;"><?= e($m['d']) ?></div>
                        <div style="font-size:11px;color:#6b7280;text-transform:uppercase;margin-top:2px;"><?= e($m['m']) ?></div>
                        <div style="font-size:11px;color:#4ade80;font-weight:500;margin-top:4px;"><?= e($m['t']) ?></div>
                    </div>
                    <div style="width:1px;height:44px;background:rgba(255,255,255,.10);"></div>
                    <div style="flex:1;display:flex;align-items:center;justify-content:center;gap:16px;">
                        <span style="flex:1;text-align:right;font-weight:700;"><?= e($m['h']) ?></span>
                        <span style="padding:8px 14px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:12px;font-size:14px;font-weight:900;color:<?= str_contains($m['s'], '–') ? '#fff' : '#9ca3af' ?>;"><?= e($m['s']) ?></span>
                        <span style="flex:1;font-weight:700;"><?= e($m['a']) ?></span>
                    </div>
                    <div style="min-width:140px;text-align:right;">
                        <div style="font-size:11px;color:#6b7280;">🏟️ <?= e($m['f']) ?></div>
                        <div style="margin-top:8px;">
                            <span class="fp-status fp-status-<?= e($m['st']) ?>"><?= e($m['lbl']) ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>