<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div><p class="fp-eyebrow">Competiciones</p><h1 class="fp-h1">Ligas activas</h1></div>
        <?php if (is_admin()): ?><a href="<?= url('leagues/create') ?>" class="fp-btn fp-btn-primary"><i class="bi bi-plus-lg"></i><span>Nueva liga</span></a><?php endif; ?>
    </div>

    <?php if (empty($leagues)): ?>
        <?php $this->partial('empty-state', ['icon' => 'bi-trophy', 'title' => 'Aún no hay ligas activas', 'description' => 'Las ligas disponibles aparecerán aquí.']); ?>
    <?php else: ?>
        <div class="fp-grid-2">
            <?php foreach ($leagues as $l): ?>
                <a href="<?= url('leagues/show/' . (int) $l['id']) ?>" class="fp-glass fp-card-link fp-panel" style="text-decoration:none;color:var(--fp-fg);">
                    <h3 style="margin:0 0 8px;"><?= e($l['name']) ?></h3>
                    <p class="fp-muted"><i class="bi bi-geo-alt"></i> <?= e($l['city']) ?> · <i class="bi bi-people"></i> <?= (int) ($l['team_count'] ?? 0) ?>/<?= (int) $l['max_teams'] ?> equipos</p>
                    <div class="fp-actions-row" style="justify-content:space-between;">
                        <small class="fp-muted"><?= e($l['start']) ?> - <?= e($l['end']) ?></small>
                        <?php if ($l['pro']): ?><span class="fp-pro-badge">PRO</span><?php endif; ?>
                    </div>
                    <?php if (!empty($l['prize'])): ?><p class="fp-gold-text"><i class="bi bi-cash-coin"></i> Premio: <?= number_format((float) $l['prize'], 2, ',', '.') ?> EUR</p><?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
