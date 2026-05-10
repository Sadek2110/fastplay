<main class="fp-fade fp-page" style="max-width:680px;">
    <p class="fp-eyebrow">Editar</p>
    <h1 class="fp-h1">Mi perfil</h1>

    <div class="fp-glass" style="border-radius:18px;padding:28px;margin-top:24px;">
        <form method="post" action="<?= url('profile/edit') ?>" style="display:flex;flex-direction:column;gap:18px;">
            <?= csrf_field() ?>
            <label>
                <span class="fp-label">Nombre</span>
                <input name="name" class="fp-input" value="<?= old('name', $profile['name']) ?>" required minlength="2">
                <?php if (!empty($errors['name'])): ?><small class="fp-err"><?= e($errors['name']) ?></small><?php endif; ?>
            </label>
            <div class="fp-grid-2" style="gap:14px;">
                <label>
                    <span class="fp-label">Edad</span>
                    <input type="number" name="age" class="fp-input" value="<?= old('age', $profile['age'] ?? '') ?>" min="14" max="99">
                    <?php if (!empty($errors['age'])): ?><small class="fp-err"><?= e($errors['age']) ?></small><?php endif; ?>
                </label>
                <label>
                    <span class="fp-label">Teléfono</span>
                    <input name="phone" class="fp-input" value="<?= old('phone', $profile['phone'] ?? '') ?>" placeholder="+34 600 000 000">
                    <?php if (!empty($errors['phone'])): ?><small class="fp-err"><?= e($errors['phone']) ?></small><?php endif; ?>
                </label>
            </div>
            <div class="fp-grid-2" style="gap:14px;">
                <label>
                    <span class="fp-label">Ciudad</span>
                    <input name="city" class="fp-input" value="<?= old('city', $profile['city'] ?? '') ?>">
                </label>
                <label>
                    <span class="fp-label">Posición</span>
                    <select name="position" class="fp-input">
                        <?php $cur = old('position', $profile['position'] ?? ''); foreach (['','Portero','Portera','Defensa','Mediocampo','Delantero'] as $p): ?>
                            <option value="<?= e($p) ?>" <?= $cur === $p ? 'selected' : '' ?>><?= e($p === '' ? '— sin definir —' : $p) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (!empty($errors['position'])): ?><small class="fp-err"><?= e($errors['position']) ?></small><?php endif; ?>
                </label>
            </div>
            <div style="display:flex;gap:10px;">
                <button class="fp-btn fp-btn-primary fp-btn-glow">Guardar cambios →</button>
                <a class="fp-btn fp-btn-ghost" href="<?= url('profile') ?>">Cancelar</a>
            </div>
        </form>
    </div>
</main>