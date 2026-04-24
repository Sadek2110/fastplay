<?php $pageTitle = $team['name'] ?? 'Equipo'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-5xl mx-auto">

    <!-- Header team -->
    <div class="glass rounded-3xl p-8 mb-6 fade-up">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
            <div class="w-20 h-20 glass-green rounded-2xl flex items-center justify-center text-4xl flex-shrink-0">🛡️</div>
            <div class="flex-1">
                <h1 class="text-3xl font-black mb-1"><?= htmlspecialchars($team['name']) ?></h1>
                <p class="text-gray-400 text-sm mb-3">📍 <?= htmlspecialchars($team['city']) ?></p>
                <?php if (!empty($team['description'])): ?>
                <p class="text-gray-300 text-sm leading-relaxed"><?= htmlspecialchars($team['description']) ?></p>
                <?php endif; ?>
            </div>
            <div class="flex flex-col items-end gap-2">
                <div class="text-xs px-3 py-1 rounded-full bg-green-500/10 text-green-400 border border-green-500/20">
                    ⭐ Rep: <?= $team['reputation'] ?? 100 ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Players -->
    <div class="glass rounded-3xl p-8 fade-up-1">
        <h2 class="text-xl font-black mb-6">Plantilla</h2>
        <?php if (empty($players)): ?>
        <p class="text-gray-500 text-sm text-center py-8">No hay jugadores en el equipo todavía</p>
        <?php else: ?>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <?php foreach($players as $p): ?>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 hover:bg-white/10 transition-colors">
                <div class="w-10 h-10 glass rounded-full flex items-center justify-center text-sm font-bold text-green-400 flex-shrink-0">
                    <?= strtoupper(substr($p['name'],0,1)) ?>
                </div>
                <div class="min-w-0">
                    <div class="font-semibold text-sm truncate"><?= htmlspecialchars($p['name']) ?></div>
                    <div class="text-xs text-gray-500 capitalize"><?= htmlspecialchars($p['position'] ?? '—') ?> · <?= $p['role'] === 'captain' ? '⭐ Capitán' : ($p['role'] === 'cocaptain' ? '🔑 Co-cap' : 'Jugador') ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

</main>
<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
