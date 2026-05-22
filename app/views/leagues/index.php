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
                <a href="<?= url('leagues/show/' . (int) $l['id']) ?>" class="fp-glass fp-card-link fp-panel fp-league-card">
                    <h3 class="fp-league-card-title"><?= e($l['name']) ?></h3>
                    <div class="fp-league-card-meta">
                        <span><i class="bi bi-geo-alt"></i> <?= e($l['city']) ?></span>
                        <span><i class="bi bi-people"></i> <?= (int) ($l['team_count'] ?? 0) ?>/<?= (int) $l['max_teams'] ?> equipos</span>
                    </div>
                    <div class="fp-league-card-footer">
                        <span class="fp-league-card-dates"><i class="bi bi-calendar3"></i> <?= e($l['start']) ?> – <?= e($l['end']) ?></span>
                        <?php if ($l['pro']): ?><span class="fp-pro-badge">PRO</span><?php endif; ?>
                    </div>
                    <?php if (!empty($l['prize'])): ?>
                        <span class="fp-league-prize"><i class="bi bi-cash-coin"></i> Premio: <?= number_format((float) $l['prize'], 2, ',', '.') ?> EUR</span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
