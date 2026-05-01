<?php $pageTitle = 'Equipos'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-7xl mx-auto">
    <div class="mb-8 fade-up">
        <a href="<?= APP_URL ?>/admin" class="text-sm text-gray-500 hover:text-white mb-3 inline-flex items-center gap-1">
            ← Volver al panel
        </a>
        <h1 class="text-2xl font-black">🛡️ Gestión de Equipos</h1>
        <p class="text-gray-500 text-sm mt-1"><?= count($teams) ?> equipos registrados</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 fade-up-1">
        <?php foreach ($teams as $t): ?>
        <div class="glass rounded-2xl p-5 hover:bg-white/[.04] transition-colors">
            <div class="flex items-center gap-4 mb-4">
                <?php if ($t['shield']): ?>
                    <img src="<?= APP_URL ?>/public/images/uploads/shields/<?= htmlspecialchars($t['shield']) ?>"
                         class="w-12 h-12 rounded-xl object-cover" alt="">
                <?php else: ?>
                    <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-2xl">🛡️</div>
                <?php endif; ?>
                <div>
                    <h3 class="font-bold"><?= htmlspecialchars($t['name']) ?></h3>
                    <p class="text-xs text-gray-500">📍 <?= htmlspecialchars($t['city']) ?></p>
                </div>
            </div>

            <?php if ($t['description']): ?>
                <p class="text-sm text-gray-400 mb-3 line-clamp-2"><?= htmlspecialchars($t['description']) ?></p>
            <?php endif; ?>

            <div class="flex items-center justify-between text-xs text-gray-500 border-t border-white/5 pt-3">
                <span>Rep: <strong class="text-white"><?= $t['reputation'] ?></strong></span>
                <span>Creado: <?= date('d/m/Y', strtotime($t['created_at'])) ?></span>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($teams)): ?>
            <div class="col-span-full text-center py-16 text-gray-500">
                <div class="text-4xl mb-3">🛡️</div>
                <p>No hay equipos registrados</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
