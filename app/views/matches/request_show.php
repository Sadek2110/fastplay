<main class="fp-fade fp-page" style="max-width:900px;">
    <?php $this->partial('back-button', ['href' => url('matches')]); ?>
    <p class="fp-eyebrow">Solicitud de partido</p>
    <h1 class="fp-h1"><?= e($request['requesting_team_name']) ?> <span class="fp-muted">vs</span> <?= e($request['requested_team_name']) ?></h1>

    <section class="fp-glass fp-panel">
        <div class="fp-status-line">
            <span class="fp-status fp-status-<?= $request['status'] === 'accepted' ? 'confirmed' : 'pending' ?>"><?= e($request['status']) ?></span>
        </div>
        <div class="fp-actions-row">
            <?php if ($request['status'] === 'pending' && (int) $request['requested_captain_id'] === (int) current_user()['id']): ?>
                <form method="post" action="<?= url('match-request/accept/' . (int) $request['id']) ?>"><?= csrf_field() ?><button class="fp-btn fp-btn-primary">Aceptar</button></form>
                <form method="post" action="<?= url('match-request/reject/' . (int) $request['id']) ?>"><?= csrf_field() ?><button class="fp-btn fp-btn-ghost">Rechazar</button></form>
            <?php endif; ?>
            <?php if ($request['status'] === 'accepted' && !empty($room)): ?>
                <a href="<?= url('chat/matchNegotiation/' . (int) $request['id']) ?>" class="fp-btn fp-btn-ghost"><i class="bi bi-chat-dots"></i><span>Chat de capitanes</span></a>
            <?php endif; ?>
        </div>
    </section>

    <?php if ($request['status'] === 'accepted'): ?>
        <section class="fp-glass fp-panel">
            <h2 class="fp-h2">Confirmar fecha, hora y lugar</h2>
            <form method="post" action="<?= url('match-request/confirm/' . (int) $request['id']) ?>" class="fp-form">
                <?= csrf_field() ?>
                <div class="fp-grid-2">
                    <label><span class="fp-label">Fecha</span><input class="fp-input" type="date" name="match_date" value="<?= e($request['proposed_date'] ?? '') ?>" required></label>
                    <label><span class="fp-label">Hora</span><input class="fp-input" type="time" name="match_time" value="<?= e($request['proposed_time'] ?? '') ?>" required></label>
                </div>
                <label><span class="fp-label">Lugar</span><input class="fp-input" name="location" value="<?= e($request['location'] ?? '') ?>" required></label>
                <button class="fp-btn fp-btn-primary">Confirmar acuerdo</button>
            </form>
        </section>
    <?php endif; ?>
</main>
