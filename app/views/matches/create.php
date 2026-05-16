<main class="fp-fade fp-page" style="max-width:760px;">
    <p class="fp-eyebrow">Programa un partido</p>
    <h1 class="fp-h1">Nuevo partido</h1>

    <div class="fp-glass" style="border-radius:18px;padding:28px;margin-top:24px;">
        <?php if (empty($myTeams) && !is_admin()): ?>
        <div style="text-align:center;padding:32px 24px;">
            <p style="font-size:15px;color:#d1d5db;margin-bottom:20px;">Necesitas pertenecer a un equipo para crear un partido.</p>
            <a href="<?= url('teams/create') ?>" class="fp-btn fp-btn-primary fp-btn-glow" style="padding:14px 28px;font-size:15px;">Crea un equipo →</a>
        </div>
        <?php else: ?>
        <form method="post" action="<?= url('matches/create') ?>" style="display:flex;flex-direction:column;gap:18px;">
            <?= csrf_field() ?>
            <div class="fp-grid-2" style="gap:14px;">
                <label>
                    <span class="fp-label">Equipo local</span>
                    <select name="home_team_id" class="fp-input" required>
                        <option value="">— elige —</option>
                        <?php foreach (!empty($myTeams) ? $myTeams : $teams as $t): ?>
                            <option value="<?= (int) $t['id'] ?>" <?= old('home_team_id') == $t['id'] ? 'selected' : '' ?>><?= e($t['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['home_team_id'])): ?><small class="fp-err"><?= e($errors['home_team_id']) ?></small><?php endif; ?>
                </label>
                <label>
                    <span class="fp-label">Equipo visitante</span>
                    <select name="away_team_id" class="fp-input" required>
                        <option value="">— elige —</option>
                        <?php foreach ($teams as $t): ?>
                            <option value="<?= (int) $t['id'] ?>" <?= old('away_team_id') == $t['id'] ? 'selected' : '' ?>><?= e($t['name']) ?> · <?= e($t['city']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['away_team_id'])): ?><small class="fp-err"><?= e($errors['away_team_id']) ?></small><?php endif; ?>
                </label>
            </div>
            <label>
                <span class="fp-label">Fecha y hora</span>
                <input type="datetime-local" name="scheduled_at" class="fp-input" value="<?= old('scheduled_at') ?>" required>
                <?php if (!empty($errors['scheduled_at'])): ?><small class="fp-err"><?= e($errors['scheduled_at']) ?></small><?php endif; ?>
            </label>
            <div class="fp-grid-2" style="gap:14px;">
                <label>
                    <span class="fp-label">Campo (opcional)</span>
                    <select name="field_id" class="fp-input">
                        <option value="">— sin asignar —</option>
                        <?php foreach ($fields as $f): ?>
                            <option value="<?= (int) $f['id'] ?>"><?= e($f['name']) ?> · <?= e($f['city']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>
                    <span class="fp-label">Liga (opcional)</span>
                    <select name="league_id" class="fp-input">
                        <option value="">— amistoso —</option>
                        <?php foreach ($leagues as $l): ?>
                            <option value="<?= (int) $l['id'] ?>"><?= e($l['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
            <button class="fp-btn fp-btn-primary fp-btn-glow" style="margin-top:6px;">Crear partido →</button>
        </form>
        <?php endif; ?>
    </div>
</main>