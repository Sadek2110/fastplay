<main class="fp-fade fp-page" style="max-width:920px;">
    <?php $this->partial('back-button', ['href' => url('matches')]); ?>
    <p class="fp-eyebrow">Partido</p>
    <h1 class="fp-h1"><?= e($match['home_name']) ?> <span class="fp-muted">vs</span> <?= e($match['away_name']) ?></h1>

    <section class="fp-glass fp-panel">
        <div class="fp-scoreboard">
            <div><strong><?= e($match['home_name']) ?></strong><small>Local</small></div>
            <b><?= e($match['s']) ?></b>
            <div><strong><?= e($match['away_name']) ?></strong><small>Visitante</small></div>
        </div>
        <div class="fp-actions-row fp-match-details">
            <span><i class="bi bi-calendar2-event"></i> <?= e(date('d/m/Y H:i', strtotime($match['scheduled_at']))) ?></span>
            <span><i class="bi bi-geo-alt"></i> <?= e($match['location'] ?? $match['field_name'] ?? 'Campo a confirmar') ?></span>
            <?php if (!empty($match['league_name'])): ?><span><i class="bi bi-trophy"></i> <?= e($match['league_name']) ?></span><?php endif; ?>
            <span class="fp-status fp-status-<?= e($match['st']) ?>"><?= e($match['lbl']) ?></span>
        </div>
    </section>

    <?php if ($isManager && $match['st'] !== 'finished' && $match['st'] !== 'cancelled'): ?>
        <section class="fp-actions-row" style="margin-top:24px;">
            <?php if ($match['st'] === 'pending'): ?>
                <form method="post" action="<?= url('matches/confirm/' . (int) $match['id']) ?>"><?= csrf_field() ?><button class="fp-btn fp-btn-primary">Confirmar partido</button></form>
            <?php endif; ?>
            <form method="post" action="<?= url('matches/cancel/' . (int) $match['id']) ?>" onsubmit="return confirm('¿Cancelar el partido?');"><?= csrf_field() ?><button class="fp-btn fp-btn-ghost">Cancelar</button></form>
        </section>
        <?php if ($match['st'] === 'confirmed'): ?>
            <section class="fp-glass fp-panel">
                <h2 class="fp-h2">Cerrar resultado</h2>
                <form method="post" action="<?= url('matches/finish/' . (int) $match['id']) ?>" class="fp-actions-row">
                    <?= csrf_field() ?>
                    <input type="number" name="home_score" min="0" max="99" placeholder="Local" class="fp-input" style="width:120px;">
                    <input type="number" name="away_score" min="0" max="99" placeholder="Visitante" class="fp-input" style="width:120px;">
                    <button class="fp-btn fp-btn-primary">Finalizar</button>
                </form>
            </section>
        <?php endif; ?>
    <?php endif; ?>
</main>
