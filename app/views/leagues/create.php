<main class="fp-fade fp-page" style="max-width:720px;">
    <p class="fp-eyebrow">Nueva competición</p>
    <h1 class="fp-h1">Crear liga</h1>

    <div class="fp-glass" style="border-radius:18px;padding:28px;margin-top:24px;">
        <form method="post" action="<?= url('leagues/create') ?>" style="display:flex;flex-direction:column;gap:18px;">
            <?= csrf_field() ?>
            <div class="fp-grid-2" style="gap:14px;">
                <label>
                    <span class="fp-label">Nombre</span>
                    <input name="name" class="fp-input" value="<?= old('name') ?>" required>
                    <?php if (!empty($errors['name'])): ?><small class="fp-err"><?= e($errors['name']) ?></small><?php endif; ?>
                </label>
                <label>
                    <span class="fp-label">Ciudad</span>
                    <input name="city" class="fp-input" value="<?= old('city') ?>" required>
                    <?php if (!empty($errors['city'])): ?><small class="fp-err"><?= e($errors['city']) ?></small><?php endif; ?>
                </label>
            </div>
            <div class="fp-grid-2" style="gap:14px;">
                <label>
                    <span class="fp-label">Fecha inicio</span>
                    <input type="date" name="start_date" class="fp-input" value="<?= old('start_date') ?>" required>
                    <?php if (!empty($errors['start_date'])): ?><small class="fp-err"><?= e($errors['start_date']) ?></small><?php endif; ?>
                </label>
                <label>
                    <span class="fp-label">Fecha fin</span>
                    <input type="date" name="end_date" class="fp-input" value="<?= old('end_date') ?>" required>
                    <?php if (!empty($errors['end_date'])): ?><small class="fp-err"><?= e($errors['end_date']) ?></small><?php endif; ?>
                </label>
            </div>
            <div class="fp-grid-2" style="gap:14px;">
                <label>
                    <span class="fp-label">Tier</span>
                    <select name="pro" class="fp-input">
                        <option value="0">🤝 Amistosa (gratis)</option>
                        <option value="1">🏆 Pro (con premio)</option>
                    </select>
                </label>
                <label>
                    <span class="fp-label">Equipos máx.</span>
                    <input type="number" name="max_teams" class="fp-input" value="<?= old('max_teams', 12) ?>" min="2" max="32">
                </label>
            </div>
            <label>
                <span class="fp-label">Premio (€) — sólo Pro</span>
                <input type="number" step="0.01" name="prize" class="fp-input" value="<?= old('prize') ?>">
            </label>
            <button class="fp-btn fp-btn-primary fp-btn-glow" style="margin-top:6px;">Crear liga →</button>
        </form>
    </div>
</main>