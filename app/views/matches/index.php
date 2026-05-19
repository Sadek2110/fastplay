<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Calendario de Ceuta</p>
            <h1 class="fp-h1">Partidos</h1>
        </div>
        <?php if (is_auth()): ?>
            <a href="<?= url('matches/create') ?>" class="fp-btn fp-btn-gold"><i class="bi bi-send"></i><span>Solicitar partido</span></a>
        <?php endif; ?>
    </div>

    <section class="matches-layout">
        <div>
            <?php if (empty($matches)): ?>
                <?php $this->partial('empty-state', ['icon' => 'bi-calendar-x', 'title' => 'No hay partidos programados', 'description' => 'Los partidos de Ceuta apareceran cuando ambos capitanes confirmen una solicitud.']); ?>
            <?php else: ?>
                <div class="fp-list">
                    <?php foreach ($matches as $m): ?>
                        <a href="<?= url('matches/show/' . (int) $m['id']) ?>" class="fp-glass fp-match-row fp-card-link">
                            <div class="fp-match-date"><strong><?= e($m['d']) ?></strong><span><?= e($m['m']) ?></span><small><?= e($m['t']) ?></small></div>
                            <div class="fp-match-teams"><span><?= e($m['h']) ?></span><b><?= e($m['s']) ?></b><span><?= e($m['a']) ?></span></div>
                            <div class="fp-match-meta"><small><i class="bi bi-geo-alt"></i> <?= e($m['f']) ?></small><span class="fp-status fp-status-<?= e($m['st']) ?>"><?= e($m['lbl']) ?></span></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <aside class="matches-calendar" data-calendar-matches='<?= e(json_encode($calendarMatches, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?>'>
            <div class="calendar-head">
                <button class="fp-icon-btn" type="button" data-calendar-prev aria-label="Mes anterior"><i class="bi bi-chevron-left"></i></button>
                <strong data-calendar-title></strong>
                <button class="fp-icon-btn" type="button" data-calendar-next aria-label="Mes siguiente"><i class="bi bi-chevron-right"></i></button>
            </div>
            <div class="calendar-weekdays">
                <span>L</span><span>M</span><span>X</span><span>J</span><span>V</span><span>S</span><span>D</span>
            </div>
            <div class="calendar-grid" data-calendar-grid></div>
            <div class="calendar-day-panel">
                <h3 data-calendar-day-title>Partidos del dia</h3>
                <div data-calendar-day-list></div>
            </div>
        </aside>
    </section>
</main>
