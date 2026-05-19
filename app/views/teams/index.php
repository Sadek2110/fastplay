<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Equipo</p>
            <h1 class="fp-h1"><?= !empty($myTeam) ? e($myTeam['name']) : 'Equipos de Ceuta' ?></h1>
        </div>
        <div class="fp-actions-row">
            <a href="<?= url('teams/all') ?>" class="fp-btn fp-btn-ghost"><i class="bi bi-table"></i><span>Ver equipos disponibles</span></a>
            <?php if (is_auth() && empty($myTeam) && !empty($isPremium)): ?>
                <a href="<?= url('teams/create') ?>" class="fp-btn fp-btn-gold"><i class="bi bi-plus-lg"></i><span>Crear equipo</span></a>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!empty($myTeam)): ?>
        <section class="fp-glass fp-panel fp-team-overview">
            <div class="fp-team-summary">
                <div class="fp-team-badge large"><?= e($myTeam['badge'] ?? 'FP') ?></div>
                <div>
                    <p class="fp-eyebrow">Mi equipo actual</p>
                    <h2><?= e($myTeam['name']) ?></h2>
                    <p class="fp-muted"><i class="bi bi-geo-alt"></i> <?= e($myTeam['city'] ?: 'Ceuta') ?> · <i class="bi bi-shield-check"></i> Capitan: <?= e($myTeam['captain_name'] ?? 'N/D') ?></p>
                </div>
            </div>
            <div class="fp-team-kpis">
                <div><strong><?= (int) ($myTeam['matches_played'] ?? 0) ?></strong><span>Partidos</span></div>
                <div><strong><?= (int) ($myTeam['finished_matches'] ?? 0) ?></strong><span>Jugados</span></div>
                <div><strong><?= (int) ($myTeam['players'] ?? 0) ?></strong><span>Jugadores</span></div>
                <div><strong><?= (int) ($myTeam['points'] ?? 0) ?></strong><span>Puntos</span></div>
            </div>
            <div class="fp-actions-row">
                <a href="<?= url('teams/show/' . (int) $myTeam['id']) ?>" class="fp-btn fp-btn-primary"><i class="bi bi-people"></i><span>Ver jugadores</span></a>
                <a href="<?= url('matches') ?>" class="fp-btn fp-btn-ghost"><i class="bi bi-calendar2-week"></i><span>Ver partidos</span></a>
                <?php if (!empty($isCaptain)): ?>
                    <a href="<?= url('matches/create') ?>" class="fp-btn fp-btn-gold"><i class="bi bi-send"></i><span>Buscar rival</span></a>
                <?php endif; ?>
                <a href="<?= url('dashboard') ?>" class="fp-btn fp-btn-ghost"><i class="bi bi-grid"></i><span>Dashboard</span></a>
            </div>
        </section>

        <section class="fp-grid-2 fp-team-detail-grid">
            <div class="fp-glass fp-panel">
                <h2 class="fp-h2">Jugadores</h2>
                <?php if (empty($members)): ?>
                    <?php $this->partial('empty-state', ['icon' => 'bi-people', 'title' => 'Plantilla vacia', 'description' => 'Cuando el capitan acepte jugadores apareceran aqui.']); ?>
                <?php else: ?>
                    <div class="fp-list">
                        <?php foreach ($members as $m): ?>
                            <div class="fp-list-item">
                                <span class="fp-avatar-initial"><?= e(mb_substr($m['name'], 0, 1)) ?></span>
                                <span><strong><?= e($m['name']) ?></strong><small><?= e($m['position'] ?? 'Sin posicion') ?><?= (int) $m['is_captain'] === 1 ? ' · Capitan' : '' ?></small></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="fp-glass fp-panel">
                <h2 class="fp-h2">Partidos del equipo</h2>
                <?php if (empty($teamMatches)): ?>
                    <?php $this->partial('empty-state', ['icon' => 'bi-calendar-x', 'title' => 'Sin partidos', 'description' => 'Tu equipo todavia no tiene partidos registrados en Ceuta.']); ?>
                <?php else: ?>
                    <div class="fp-list">
                        <?php foreach ($teamMatches as $m): ?>
                            <a href="<?= url('matches/show/' . (int) $m['id']) ?>" class="fp-list-item">
                                <i class="bi bi-calendar2-event"></i>
                                <span>
                                    <strong><?= e($m['home_name']) ?> vs <?= e($m['away_name']) ?></strong>
                                    <small><?= e(date('d/m/Y H:i', strtotime($m['scheduled_at']))) ?> · <?= e($m['field_name']) ?></small>
                                </span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    <?php else: ?>
        <section class="fp-glass fp-panel">
            <h2 class="fp-h2">Todavia no perteneces a ningun equipo</h2>
            <p class="fp-muted">Solicita unirte a un equipo local o crea tu propio equipo premium para empezar a competir en Ceuta.</p>
            <div class="fp-actions-row">
                <a href="<?= url('teams/all') ?>" class="fp-btn fp-btn-primary"><i class="bi bi-search"></i><span>Solicitar unirse</span></a>
                <?php if (!empty($isPremium)): ?>
                    <a href="<?= url('teams/create') ?>" class="fp-btn fp-btn-gold"><i class="bi bi-plus-lg"></i><span>Crear equipo</span></a>
                <?php else: ?>
                    <a href="<?= url('premium') ?>" class="fp-btn fp-btn-gold"><i class="bi bi-stars"></i><span>Hacerse premium</span></a>
                <?php endif; ?>
                <a href="<?= url('teams/all') ?>" class="fp-btn fp-btn-ghost"><i class="bi bi-table"></i><span>Ver equipos disponibles</span></a>
            </div>
        </section>

        <?php if (!empty($teams)): ?>
            <div class="fp-team-list">
                <?php foreach ($teams as $t): ?>
                    <article class="fp-glass fp-team-row">
                        <a href="<?= url('teams/show/' . (int) $t['id']) ?>" class="fp-team-row-main">
                            <span class="fp-team-badge"><?= e($t['badge'] ?? 'FP') ?></span>
                            <span><strong><?= e($t['name']) ?></strong><small><i class="bi bi-geo-alt"></i> <?= e($t['city']) ?> · Capitan: <?= e($t['captain_name']) ?></small></span>
                        </a>
                        <?php if (is_auth()): ?>
                            <form method="post" action="<?= url('team-join-request/create') ?>">
                                <?= csrf_field() ?>
                                <input type="hidden" name="team_id" value="<?= (int) $t['id'] ?>">
                                <button class="fp-btn fp-btn-gold">Unirse a equipo</button>
                            </form>
                        <?php else: ?>
                            <a href="<?= url('auth/login') ?>" class="fp-btn fp-btn-ghost">Inicia sesion</a>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</main>
