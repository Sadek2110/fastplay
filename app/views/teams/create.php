<main class="fp-fade fp-page" style="max-width:640px;">
    <p class="fp-eyebrow">Nuevo equipo</p>
    <h1 class="fp-h1">Crear mi equipo</h1>

    <div class="fp-glass" style="border-radius:18px;padding:28px;margin-top:24px;">
        <form method="post" action="<?= url('teams/create') ?>" style="display:flex;flex-direction:column;gap:18px;">
            <?= csrf_field() ?>
            <label>
                <span class="fp-label">Nombre del equipo</span>
                <input name="name" class="fp-input" value="<?= old('name') ?>" placeholder="Madrid Real C.F." required minlength="3">
                <?php if (!empty($errors['name'])): ?><small class="fp-err"><?= e($errors['name']) ?></small><?php endif; ?>
            </label>
            <label>
                <span class="fp-label">Ciudad</span>
                <input name="city" class="fp-input" value="<?= old('city') ?>" placeholder="Madrid" required>
                <?php if (!empty($errors['city'])): ?><small class="fp-err"><?= e($errors['city']) ?></small><?php endif; ?>
            </label>
            <label>
                <span class="fp-label">Escudo (emoji)</span>
                <input name="badge" class="fp-input" value="<?= old('badge', '🛡️') ?>" placeholder="🛡️" maxlength="4">
            </label>
            <button class="fp-btn fp-btn-primary fp-btn-glow" style="margin-top:6px;">Crear equipo →</button>
        </form>
    </div>
</main>