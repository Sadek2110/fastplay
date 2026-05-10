<main class="fp-fade fp-page" style="max-width:520px;">
    <p class="fp-eyebrow">Seguridad</p>
    <h1 class="fp-h1">Cambiar contraseña</h1>

    <div class="fp-glass" style="border-radius:18px;padding:28px;margin-top:24px;">
        <form method="post" action="<?= url('profile/password') ?>" style="display:flex;flex-direction:column;gap:18px;">
            <?= csrf_field() ?>
            <label>
                <span class="fp-label">Contraseña actual</span>
                <input type="password" name="current" class="fp-input" required autocomplete="current-password">
                <?php if (!empty($errors['current'])): ?><small class="fp-err"><?= e($errors['current']) ?></small><?php endif; ?>
            </label>
            <label>
                <span class="fp-label">Nueva contraseña</span>
                <input type="password" name="new" class="fp-input" required minlength="8" autocomplete="new-password">
                <?php if (!empty($errors['new'])): ?><small class="fp-err"><?= e($errors['new']) ?></small><?php endif; ?>
            </label>
            <label>
                <span class="fp-label">Repetir nueva contraseña</span>
                <input type="password" name="confirm" class="fp-input" required minlength="8" autocomplete="new-password">
                <?php if (!empty($errors['confirm'])): ?><small class="fp-err"><?= e($errors['confirm']) ?></small><?php endif; ?>
            </label>
            <button class="fp-btn fp-btn-primary fp-btn-glow">Actualizar contraseña →</button>
        </form>
    </div>
</main>