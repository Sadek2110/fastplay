<main class="fp-fade fp-page" style="max-width:900px;">
    <?php $this->partial('back-button', ['href' => url('matches')]); ?>
    <p class="fp-eyebrow">Partidos</p>
    <h1 class="fp-h1">Solicitar partido</h1>

    <?php if (empty($myTeam)): ?>
        <?php $this->partial('empty-state', ['icon' => 'bi-shield-exclamation', 'title' => 'Necesitas un equipo', 'description' => 'Primero debes unirte o crear un equipo para solicitar partidos.', 'ctaUrl' => 'teams', 'ctaLabel' => 'Ir a equipos']); ?>
    <?php elseif (empty($captainTeams)): ?>
        <?php $this->partial('empty-state', ['icon' => 'bi-person-lock', 'title' => 'Solo capitanes', 'description' => 'Solo el capitán del equipo puede solicitar partidos contra otros equipos.']); ?>
    <?php else: ?>
        <section class="fp-glass fp-panel">
            <form method="post"
                  action="<?= url('match-request/create') ?>"
                  class="fp-form"
                  data-match-request-form
                  data-fp-validate
                  novalidate>
                <?= csrf_field() ?>
                <label>
                    <span class="fp-label">Tu equipo</span>
                    <select name="requesting_team_id" class="fp-input" required>
                        <?php foreach ($captainTeams as $team): ?>
                            <option value="<?= (int) $team['id'] ?>"><?= e($team['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>
                    <span class="fp-label">Equipo rival</span>
                    <input class="fp-input" data-team-search placeholder="Buscar equipo rival">
                </label>
                <small class="fp-ctx-status" data-team-count></small>
                <div class="fp-grid-2">
                    <?php foreach ($teams as $t): ?>
                        <label class="fp-glass fp-select-team" data-team-card="<?= e(mb_strtolower($t['name'] . ' ' . $t['city'] . ' ' . $t['captain_name'])) ?>">
                            <input type="radio" name="requested_team_id" value="<?= (int) $t['id'] ?>" required>
                            <span class="fp-team-badge"><?= e($t['badge'] ?? 'FP') ?></span>
                            <span><strong><?= e($t['name']) ?></strong><small><?= e($t['city']) ?> · <?= e($t['captain_name']) ?> · <?= (int) $t['players'] ?> jugadores</small></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <button class="fp-btn fp-btn-primary"><i class="bi bi-send"></i><span>Solicitar partido</span></button>
            </form>
        </section>
    <?php endif; ?>
</main>
