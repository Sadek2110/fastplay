<main class="fp-fade fp-page" style="max-width:640px;">
    <?php $this->partial('back-button', ['href' => url('teams')]); ?>
    <p class="fp-eyebrow">Premium</p>
    <h1 class="fp-h1">Crear mi equipo</h1>

    <div class="fp-glass fp-panel">
        <form method="post" action="<?= url('teams/create') ?>" class="fp-form" data-fp-validate novalidate>
            <?= csrf_field() ?>
            <label>
                <span class="fp-label">Nombre del equipo</span>
                <input name="name" class="fp-input" value="<?= old('name') ?>" placeholder="Madrid Real C.F." required minlength="3"
                       data-fp-validate-field data-fp-rule="team-name">
                <?php if (!empty($errors['name'])): ?><small class="fp-err"><?= e($errors['name']) ?></small><?php endif; ?>
            </label>
            <label>
                <span class="fp-label">Ciudad</span>
                <input name="city" class="fp-input" value="<?= old('city') ?>" placeholder="Ceuta" required
                       data-fp-validate-field data-fp-rule="city">
                <?php if (!empty($errors['city'])): ?><small class="fp-err"><?= e($errors['city']) ?></small><?php endif; ?>
            </label>
            <label>
                <span class="fp-label">Escudo breve</span>
                <input name="badge" class="fp-input" value="<?= old('badge', 'FP') ?>" placeholder="FP" maxlength="4"
                       data-fp-validate-field data-fp-rule="badge">
                <?php if (!empty($errors['badge'])): ?><small class="fp-err"><?= e($errors['badge']) ?></small><?php endif; ?>
            </label>
            <button class="fp-btn fp-btn-primary"><i class="bi bi-plus-lg"></i><span>Crear equipo</span></button>
        </form>
    </div>
</main>
