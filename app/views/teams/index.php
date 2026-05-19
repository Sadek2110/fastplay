<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Equipo</p>
            <h1 class="fp-h1"><?= !empty($myTeam) ? 'Mi equipo' : 'Buscar equipo' ?></h1>
        </div>
        <div class="fp-actions-row">
            <a href="<?= url('teams/all') ?>" class="fp-btn fp-btn-ghost"><i class="bi bi-table"></i><span>Ver todos los equipos</span></a>
            <?php if (is_auth() && empty($myTeam)): ?>
                <a href="<?= url('teams/create') ?>" class="fp-btn fp-btn-primary"><i class="bi bi-plus-lg"></i><span>Crear equipo</span></a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($myTeam)): ?>
        <section class="fp-glass fp-panel">
            <div class="fp-team-summary">
                <div class="fp-team-badge large"><?= e($myTeam['badge'] ?? 'FP') ?></div>
                <div>
                    <h2><?= e($myTeam['name']) ?></h2>
                    <p><i class="bi bi-geo-alt"></i> <?= e($myTeam['city']) ?></p>
                </div>
            </div>
            <div class="fp-actions-row">
                <a href="<?= url('teams/show/' . (int) $myTeam['id']) ?>" class="fp-btn fp-btn-primary">Gestionar equipo</a>
                <a href="<?= url('chat/team/' . (int) $myTeam['id']) ?>" class="fp-btn fp-btn-ghost"><i class="bi bi-chat-dots"></i><span>Chat interno</span></a>
            </div>
        </section>
    <?php else: ?>
        <section class="fp-glass fp-panel">
            <h2 class="fp-h2">Solicita unirte a un equipo</h2>
            <p class="fp-muted">El capitán revisará tu solicitud antes de aceptarte. No puedes pertenecer a más de un equipo al mismo tiempo.</p>
        </section>

        <?php if (empty($teams)): ?>
            <?php $this->partial('empty-state', ['icon' => 'bi-shield-plus', 'title' => 'Aún no hay equipos', 'description' => 'Crea el primer equipo de la plataforma.', 'ctaUrl' => 'teams/create', 'ctaLabel' => 'Crear equipo']); ?>
        <?php else: ?>
            <div class="fp-team-list">
                <?php foreach ($teams as $t): ?>
                    <article class="fp-glass fp-team-row">
                        <a href="<?= url('teams/show/' . (int) $t['id']) ?>" class="fp-team-row-main">
                            <span class="fp-team-badge"><?= e($t['badge'] ?? 'FP') ?></span>
                            <span><strong><?= e($t['name']) ?></strong><small><i class="bi bi-geo-alt"></i> <?= e($t['city']) ?> · Capitán: <?= e($t['captain_name']) ?></small></span>
                        </a>
                        <?php if (is_auth()): ?>
                            <form method="post" action="<?= url('team-join-request/create') ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="team_id" value="<?= (int) $t['id'] ?>">
                                <button class="fp-btn fp-btn-primary">Solicitar unirse</button>
                            </form>
                        <?php else: ?>
                            <a href="<?= url('auth/login') ?>" class="fp-btn fp-btn-ghost">Inicia sesión</a>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>
