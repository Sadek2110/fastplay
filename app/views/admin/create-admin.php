<main class="fp-fade fp-page">
    <p class="fp-eyebrow">Admin</p>
    <h1 class="fp-h1">Nuevo <span class="fp-gradient-text">Administrador</span></h1>

    <div class="fp-glass" style="border-radius:18px;padding:32px;margin-top:24px;max-width:560px;">
        <?php if (!empty($errors)): ?>
            <div class="fp-alert fp-alert-err" style="margin-bottom:20px;">Revisa los datos del formulario.</div>
        <?php endif; ?>

        <form method="post" action="<?= url('admin/storeAdmin') ?>" class="fp-form" data-fp-validate novalidate>
            <?= csrf_field() ?>

            <div style="margin-bottom:18px;">
                <span class="fp-label">Nombre completo</span>
                <div class="fp-input-icon-group">
                    <i class="bi bi-person"></i>
                    <input name="name" type="text" placeholder="Nombre del administrador" class="fp-input" value="<?= old('name') ?>" required
                           data-fp-validate-field data-fp-rule="name">
                </div>
                <?php if (!empty($errors['name'])): ?><small class="fp-err"><?= e($errors['name']) ?></small><?php endif; ?>
            </div>

            <div style="margin-bottom:18px;">
                <span class="fp-label">Email</span>
                <div class="fp-input-icon-group">
                    <i class="bi bi-envelope"></i>
                    <input name="email" type="email" placeholder="admin@email.com" class="fp-input" value="<?= old('email') ?>" required autocomplete="email"
                           data-fp-validate-field data-fp-rule="email">
                </div>
                <?php if (!empty($errors['email'])): ?><small class="fp-err"><?= e($errors['email']) ?></small><?php endif; ?>
            </div>

            <div style="margin-bottom:18px;">
                <span class="fp-label">Contraseña</span>
                <div class="fp-input-icon-group">
                    <i class="bi bi-lock"></i>
                    <input name="password" type="password" placeholder="Mínimo 8 caracteres" class="fp-input" minlength="8" required autocomplete="new-password"
                           data-fp-validate-field data-fp-rule="password-strong">
                </div>
                <?php if (!empty($errors['password'])): ?><small class="fp-err"><?= e($errors['password']) ?></small><?php endif; ?>
            </div>

            <div style="margin-bottom:24px;">
                <span class="fp-label">Confirmar contraseña</span>
                <div class="fp-input-icon-group">
                    <i class="bi bi-lock-fill"></i>
                    <input name="password_confirm" type="password" placeholder="Repite la contraseña" class="fp-input" minlength="8" required autocomplete="new-password"
                           data-fp-validate-field data-fp-rule="password-basic">
                </div>
                <?php if (!empty($errors['password_confirm'])): ?><small class="fp-err"><?= e($errors['password_confirm']) ?></small><?php endif; ?>
            </div>

            <div style="display:flex;gap:12px;">
                <button type="submit" class="fp-btn fp-btn-primary fp-btn-glow">
                    <i class="bi bi-shield-plus"></i> Crear administrador
                </button>
                <a href="<?= url('admin') ?>" class="fp-btn fp-btn-ghost">Cancelar</a>
            </div>
        </form>
    </div>
</main>
