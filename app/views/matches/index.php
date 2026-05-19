<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Calendario</p>
            <h1 class="fp-h1">Partidos</h1>
        </div>
        <?php if (is_auth()): ?>
            <a href="<?= url('matches/create') ?>" class="fp-btn fp-btn-primary"><i class="bi bi-send"></i><span>Solicitar partido</span></a>
        <?php endif; ?>
    </div>

    <?php if (empty($matches)): ?>
        <?php $this->partial('empty-state', ['icon' => 'bi-calendar-x', 'title' => 'No hay partidos programados', 'description' => 'Los partidos aparecerán cuando ambos capitanes confirmen una solicitud.']); ?>
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
</main>
