<?php
$positionShort = static function (string $p): string {
    return match ($p) {
        'Portero', 'Portera' => 'POR',
        'Defensa' => 'DEF',
        'Mediocampo' => 'MED',
        'Delantero' => 'DEL',
        default => 'N/D',
    };
};
$card = $card ?? [];
$dorsalLabel = isset($card['dorsal']) && $card['dorsal'] !== null ? str_pad((string) (int) $card['dorsal'], 2, '0', STR_PAD_LEFT) : 'N/D';
$heightLabel = isset($card['height_cm']) && $card['height_cm'] !== null ? number_format(((int) $card['height_cm']) / 100, 2, '.', '') . 'm' : 'N/D';
$avatarSrc = !empty($card['avatar']) ? asset($card['avatar']) : asset('images/default-avatar.svg');
$teamLabel = !empty($card['team']['name']) ? $card['team']['name'] : 'Sin equipo';
$posShort = $positionShort((string) ($card['position'] ?? ''));
?>
<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Inicio</p>
            <h1 class="fp-h1">Hola, <span class="fp-gradient-text"><?= e($user['name']) ?></span></h1>
        </div>
        <a href="<?= url('profile/edit') ?>" class="fp-btn fp-btn-ghost"><i class="bi bi-person-gear"></i><span>Editar perfil</span></a>
    </div>

    <section class="fp-hero-card">
        <div class="fp-card-fifa-wrap">
            <article class="fp-card-fifa <?= !empty($isPremium) ? 'premium' : '' ?>" aria-label="Carta de jugador">
                <div class="fp-card-fifa-shine" aria-hidden="true"></div>
                <header class="fp-card-fifa-head">
                    <div class="fp-card-fifa-rating">
                        <span class="fp-card-fifa-num"><?= e($dorsalLabel) ?></span>
                        <span class="fp-card-fifa-pos"><?= e($posShort) ?></span>
                    </div>
                    <i class="bi bi-shield-fill-check fp-card-fifa-icon"></i>
                </header>
                <div class="fp-card-fifa-photo"><img src="<?= e($avatarSrc) ?>" alt="<?= e($card['name'] ?? $user['name']) ?>" loading="lazy"></div>
                <div class="fp-card-fifa-name"><?= e(mb_strtoupper($card['name'] ?? $user['name'])) ?></div>
                <div class="fp-card-fifa-stats">
                    <div class="fp-card-fifa-stat"><i class="bi bi-calendar2-check"></i><b><?= (int) ($card['played'] ?? 0) ?></b><span>PJ</span></div>
                    <div class="fp-card-fifa-stat"><i class="bi bi-bullseye"></i><b><?= (int) ($card['goals'] ?? 0) ?></b><span>GOL</span></div>
                    <div class="fp-card-fifa-stat"><i class="bi bi-crosshair"></i><b><?= (int) ($card['assists'] ?? 0) ?></b><span>ASI</span></div>
                    <div class="fp-card-fifa-stat"><i class="bi bi-rulers"></i><b><?= e($heightLabel) ?></b><span>ALT</span></div>
                </div>
                <footer class="fp-card-fifa-foot"><span class="fp-card-fifa-club"><?= e($teamLabel) ?></span></footer>
            </article>
        </div>

        <div class="fp-hero-stats">
            <div class="fp-grid-3">
                <?php foreach ($stats as $s): ?>
                    <div class="fp-glass fp-stat-card">
                        <i class="bi <?= e($s['i']) ?>" style="color:<?= e($s['c']) ?>"></i>
                        <strong><?= (int) $s['v'] ?></strong>
                        <span><?= e($s['l']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <div class="fp-grid-3 fp-dashboard-grid">
        <section class="fp-glass fp-panel">
            <h2 class="fp-h2">Mi equipo</h2>
            <?php if (!empty($team)): ?>
                <div class="fp-team-mini">
                    <div class="fp-team-badge"><?= e($team['badge'] ?? 'FP') ?></div>
                    <div><strong><?= e($team['name']) ?></strong><span><?= e($team['city']) ?></span></div>
                </div>
                <div class="fp-actions-row">
                    <a href="<?= url('teams/show/' . (int) $team['id']) ?>" class="fp-btn fp-btn-ghost">Ver equipo</a>
                    <a href="<?= url('chat/team/' . (int) $team['id']) ?>" class="fp-btn fp-btn-ghost"><i class="bi bi-chat-dots"></i><span>Chat</span></a>
                </div>
            <?php else: ?>
                <?php $this->partial('empty-state', ['icon' => 'bi-shield-plus', 'title' => 'Todavia no perteneces a ningun equipo', 'description' => 'Unete a uno o crea tu propio equipo para empezar a competir en Ceuta.', 'ctaUrl' => 'teams', 'ctaLabel' => 'Ver equipos de Ceuta']); ?>
            <?php endif; ?>
        </section>

        <section class="fp-glass fp-panel">
            <h2 class="fp-h2">Próximos partidos</h2>
            <?php if (empty($upcoming)): ?>
                <?php $this->partial('empty-state', ['icon' => 'bi-calendar-x', 'title' => 'Sin partidos programados', 'description' => 'Cuando tengas equipo podras solicitar partidos en campos de Ceuta.', 'ctaUrl' => 'matches/create', 'ctaLabel' => 'Solicitar partido']); ?>
            <?php else: ?>
                <div class="fp-list">
                    <?php foreach ($upcoming as $m): ?>
                        <a href="<?= url('matches/show/' . (int) $m['id']) ?>" class="fp-list-item">
                            <i class="bi bi-calendar2-event"></i>
                            <span><strong><?= e($m['home']) ?> vs <?= e($m['away']) ?></strong><small><?= e($m['when']) ?></small></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>

        <section class="fp-glass fp-panel">
            <div class="fp-section-title-row">
                <h2 class="fp-h2">Notificaciones</h2>
                <a href="<?= url('notification') ?>">Ver todas</a>
            </div>
            <?php if (empty($notifications)): ?>
                <?php $this->partial('empty-state', ['icon' => 'bi-bell', 'title' => 'Sin notificaciones', 'description' => 'Aqui veras solicitudes y avisos importantes.']); ?>
            <?php else: ?>
                <div class="fp-list">
                    <?php foreach ($notifications as $n): ?>
                        <a href="<?= !empty($n['action_url']) ? url($n['action_url']) : url('notification') ?>" class="fp-list-item <?= (int) $n['is_read'] === 0 ? 'unread' : '' ?>">
                            <i class="bi bi-bell"></i>
                            <span><strong><?= e($n['message']) ?></strong><small><?= e(date('d/m/Y H:i', strtotime($n['created_at']))) ?></small></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</main>
