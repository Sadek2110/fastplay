<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Centro de actividad</p>
            <h1 class="fp-h1">Notificaciones</h1>
        </div>
        <form method="post" action="<?= url('notification/markAllRead') ?>">
            <?= csrf_field() ?>
            <button class="fp-btn fp-btn-ghost"><i class="bi bi-check2-all"></i><span>Marcar todo leído</span></button>
        </form>
    </div>

    <div class="fp-filter-row">
        <a class="fp-filter <?= $filter === 'all' ? 'active' : '' ?>" href="<?= url('notification') ?>">Todas</a>
        <a class="fp-filter <?= $filter === 'unread' ? 'active' : '' ?>" href="<?= url('notification?filter=unread') ?>">No leídas <?= (int) $unread ?></a>
    </div>

    <?php if (empty($notifications)): ?>
        <?php $this->partial('empty-state', ['icon' => 'bi-bell', 'title' => 'No tienes notificaciones', 'description' => 'Las solicitudes de equipo, partidos y premium aparecerán aquí.']); ?>
    <?php else: ?>
        <div class="fp-list fp-notification-list">
            <?php foreach ($notifications as $n): ?>
                <article class="fp-glass fp-notification <?= (int) $n['is_read'] === 0 ? 'unread' : '' ?>">
                    <i class="bi bi-bell"></i>
                    <div>
                        <strong><?= e($n['message']) ?></strong>
                        <small><?= e($n['type']) ?> · <?= e(date('d/m/Y H:i', strtotime($n['created_at']))) ?></small>
                        <div class="fp-actions-row">
                            <?php if (!empty($n['action_url'])): ?>
                                <a class="fp-btn fp-btn-ghost" href="<?= url($n['action_url']) ?>">Abrir</a>
                            <?php endif; ?>
                            <?php if ((int) $n['is_read'] === 0): ?>
                                <form method="post" action="<?= url('notification/markRead/' . (int) $n['id']) ?>">
                                    <?= csrf_field() ?>
                                    <button class="fp-btn fp-btn-ghost">Marcar leída</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
