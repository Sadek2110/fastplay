<main class="fp-fade fp-page">
    <?php $this->partial('back-button', ['href' => url('notification'), 'label' => 'Notificaciones']); ?>
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Solicitud de equipo</p>
            <h1 class="fp-h1"><?= e($request['user_name']) ?> quiere unirse</h1>
        </div>
    </div>

    <div class="fp-glass fp-panel" style="max-width:540px">
        <div class="fp-join-req-profile">
            <div class="fp-team-badge large"><i class="bi bi-person"></i></div>
            <div>
                <strong style="font-size:20px;font-weight:900"><?= e($request['user_name']) ?></strong>
                <p class="fp-muted" style="margin:4px 0 0"><?= e($request['user_email']) ?></p>
            </div>
        </div>
        <ul class="fp-detail" style="margin:24px 0">
            <li><span>Equipo</span><strong><?= e($request['team_name']) ?></strong></li>
            <li><span>Estado</span><strong><?= e($request['status']) ?></strong></li>
            <li><span>Fecha</span><strong><?= e(date('d/m/Y H:i', strtotime($request['created_at']))) ?></strong></li>
        </ul>
        <?php if ($request['status'] === 'pending'): ?>
            <div class="fp-actions-row" style="margin-top:8px">
                <form method="post" action="<?= url('team-join-request/accept/' . (int) $request['id']) ?>">
                    <?= csrf_field() ?>
                    <button class="fp-btn fp-btn-primary"><i class="bi bi-check-lg"></i> Aceptar en el equipo</button>
                </form>
                <form method="post" action="<?= url('team-join-request/reject/' . (int) $request['id']) ?>">
                    <?= csrf_field() ?>
                    <button class="fp-btn fp-btn-ghost"><i class="bi bi-x-lg"></i> Rechazar</button>
                </form>
            </div>
        <?php else: ?>
            <p class="fp-muted">Esta solicitud ya fue procesada.</p>
        <?php endif; ?>
    </div>
</main>
