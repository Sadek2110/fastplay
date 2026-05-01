<?php $pageTitle = 'Campos'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-7xl mx-auto">
    <div class="mb-8 fade-up">
        <a href="<?= APP_URL ?>/admin" class="text-sm text-gray-500 hover:text-white mb-3 inline-flex items-center gap-1">
            ← Volver al panel
        </a>
        <h1 class="text-2xl font-black">🏟️ Gestión de Campos</h1>
        <p class="text-gray-500 text-sm mt-1"><?= count($fields) ?> campos registrados</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 fade-up-1">
        <?php foreach ($fields as $f): ?>
        <div class="glass rounded-2xl p-5 hover:bg-white/[.04] transition-colors">
            <div class="flex items-start justify-between mb-3">
                <h3 class="font-bold text-lg"><?= htmlspecialchars($f['name']) ?></h3>
                <?php if ($f['is_certified']): ?>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-500/20 text-green-400">✓ Certificado</span>
                <?php endif; ?>
            </div>

            <p class="text-sm text-gray-400 mb-3">📍 <?= htmlspecialchars($f['address']) ?></p>
            <p class="text-xs text-gray-500 mb-4">🏙️ <?= htmlspecialchars($f['city']) ?></p>

            <div class="flex items-center gap-2 text-xs text-gray-500 border-t border-white/5 pt-3">
                <span class="px-2 py-0.5 rounded-full
                    <?= $f['surface'] === 'grass'     ? 'bg-green-500/10 text-green-400' :
                       ($f['surface'] === 'artificial' ? 'bg-blue-500/10 text-blue-400'
                                                       : 'bg-purple-500/10 text-purple-400') ?>">
                    <?= match($f['surface']) {
                        'grass'     => '🌿 Césped natural',
                        'artificial' => '🟢 Artificial',
                        'futsal'    => '🏀 Futsal',
                        default     => $f['surface']
                    } ?>
                </span>
                <span class="ml-auto
                    <?= $f['status'] === 'active' ? 'text-green-400' : 'text-red-400' ?>">
                    <?= $f['status'] === 'active' ? '● Activo' : '● Inactivo' ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if (empty($fields)): ?>
            <div class="col-span-full text-center py-16 text-gray-500">
                <div class="text-4xl mb-3">🏟️</div>
                <p>No hay campos registrados</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
