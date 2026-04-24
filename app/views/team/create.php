<?php $pageTitle = 'Crear equipo'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-2xl mx-auto">
    <div class="mb-8 fade-up">
        <h1 class="text-3xl font-black">Crear equipo</h1>
        <p class="text-gray-400 text-sm mt-1">Conviértete en capitán. Tasa única de <strong class="text-green-400">4,99 €</strong>.</p>
    </div>

    <div class="glass rounded-3xl p-8 fade-up-1">
        <form method="POST" action="<?= APP_URL ?>/teams/create" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Nombre del equipo</label>
                <input type="text" name="name" required class="input-dark" placeholder="Los Galácticos FC">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Ciudad</label>
                <input type="text" name="city" required class="input-dark" placeholder="Madrid">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Descripción <span class="text-gray-600">(opcional)</span></label>
                <textarea name="description" rows="3" class="input-dark resize-none" placeholder="Cuéntanos sobre tu equipo…"></textarea>
            </div>

            <div class="glass-green rounded-2xl p-5 text-sm">
                <div class="font-semibold mb-2">💡 Condiciones del capitán</div>
                <ul class="text-gray-400 space-y-1.5 text-xs">
                    <li>✓ Tasa única de 4,99 € para crear el equipo</li>
                    <li>✓ Mínimo recomendado: 8 jugadores (7 titulares + 1 suplente)</li>
                    <li>✓ Las alineaciones se bloquean 1 hora antes del partido</li>
                    <li>✓ Puedes delegar funciones a co-capitanes</li>
                </ul>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <a href="<?= APP_URL ?>/teams" class="btn-ghost flex-1 text-center py-3 text-sm">Cancelar</a>
                <button type="submit" class="btn-primary flex-1 justify-center py-3 text-sm glow-sm">Crear equipo → 4,99 €</button>
            </div>
        </form>
    </div>
</main>
<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
