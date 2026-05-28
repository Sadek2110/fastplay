<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Centro de actividad</p>
            <h1 class="fp-h1">Notificaciones</h1>
        </div>
        <div class="fp-actions-row">
            <form method="post" action="<?= url('notification/markAllRead') ?>">
                <?= csrf_field() ?>
                <button class="fp-btn fp-btn-ghost"><i class="bi bi-check2-all"></i><span>Marcar todo leído</span></button>
            </form>
            <form method="post"
                  action="<?= url('notification/clearRead') ?>"
                  onsubmit="return confirm('¿Eliminar todas las notificaciones leídas?');">
                <?= csrf_field() ?>
                <button class="fp-btn fp-btn-ghost"><i class="bi bi-trash"></i><span>Limpiar leídas</span></button>
            </form>
        </div>
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
                <?php
                $typeIcons = [
                    'team_join_request'  => 'bi-person-plus',
                    'team_join_accepted' => 'bi-person-check',
                    'team_join_rejected' => 'bi-person-x',
                    'match'              => 'bi-calendar2-event',
                    'premium'            => 'bi-star',
                ];
                $icon = $typeIcons[$n['type']] ?? 'bi-bell';
                $joinReqId = null;
                if ($n['type'] === 'team_join_request' && preg_match('#team-join-request/show/(\d+)#', (string) $n['action_url'], $m)) {
                    $joinReqId = (int) $m[1];
                }
                $joinReq = ($joinReqId && isset($joinRequests[$joinReqId])) ? $joinRequests[$joinReqId] : null;
                ?>
                <article class="fp-glass fp-notification fp-notification-card <?= (int) $n['is_read'] === 0 ? 'unread' : '' ?>">
                    <div class="fp-notif-icon-wrap"><i class="bi <?= e($icon) ?>"></i></div>
                    <div class="fp-notif-body">
                        <strong><?= e($n['message']) ?></strong>
                        <small><?= e(date('d/m/Y H:i', strtotime($n['created_at']))) ?></small>
                        <?php if ($joinReq): ?>
                            <div class="fp-actions-row fp-notif-actions">
                                <form method="post" action="<?= url('team-join-request/accept/' . (int) $joinReq['id']) ?>">
                                    <?= csrf_field() ?>
                                    <button class="fp-btn fp-btn-primary fp-btn-sm"><i class="bi bi-check-lg"></i> Aceptar</button>
                                </form>
                                <form method="post" action="<?= url('team-join-request/reject/' . (int) $joinReq['id']) ?>">
                                    <?= csrf_field() ?>
                                    <button class="fp-btn fp-btn-ghost fp-btn-sm"><i class="bi bi-x-lg"></i> Rechazar</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="fp-actions-row fp-notif-actions">
                                <?php if (!empty($n['action_url']) && $n['action_url'] !== 'notification'): ?>
                                    <a class="fp-btn fp-btn-ghost fp-btn-sm" href="<?= url($n['action_url']) ?>">Ver</a>
                                <?php endif; ?>
                                <?php if ((int) $n['is_read'] === 0): ?>
                                    <form method="post" action="<?= url('notification/markRead/' . (int) $n['id']) ?>">
                                        <?= csrf_field() ?>
                                        <button class="fp-btn fp-btn-ghost fp-btn-sm">Marcar leída</button>
                                    </form>
                                <?php endif; ?>
                                <form method="post"
                                      action="<?= url('notification/delete/' . (int) $n['id']) ?>"
                                      onsubmit="return confirm('¿Eliminar esta notificación?');">
                                    <?= csrf_field() ?>
                                    <button class="fp-btn fp-btn-ghost fp-btn-sm" title="Eliminar" aria-label="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>
