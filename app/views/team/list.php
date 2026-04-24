<?php $pageTitle = 'Equipos'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-7xl mx-auto">
    <div class="flex items-end justify-between mb-10 flex-wrap gap-4 fade-up">
        <div>
            <p class="text-green-400 font-semibold text-sm uppercase tracking-widest mb-2">Directorio</p>
            <h1 class="text-3xl font-black">Equipos</h1>
        </div>
        <?php if (!empty($_SESSION['user_id']) && in_array($_SESSION['user_role']??'',['captain','admin'])): ?>
        <a href="<?= APP_URL ?>/teams/create" class="btn-primary text-sm px-6 py-2.5">+ Crear equipo</a>
        <?php endif; ?>
    </div>

    <!-- Search / filter -->
    <form method="GET" class="mb-8 fade-up-1">
        <div class="flex gap-3">
            <input type="text" name="city" placeholder="Filtrar por ciudad…" value="<?= htmlspecialchars($_GET['city'] ?? '') ?>"
                   class="input-dark max-w-xs">
            <button type="submit" class="btn-primary px-6 py-2.5 text-sm">Buscar</button>
        </div>
    </form>

    <?php if (empty($teams)): ?>
    <div class="text-center py-24 fade-up-2">
        <div class="text-5xl mb-4">⚽</div>
        <p class="text-gray-400 font-medium mb-2">No hay equipos registrados</p>
        <p class="text-gray-600 text-sm mb-6">¡Sé el primero en crear un equipo en tu ciudad!</p>
        <?php if (!empty($_SESSION['user_id'])): ?>
        <a href="<?= APP_URL ?>/teams/create" class="btn-primary px-8 py-3">Crear el primer equipo</a>
        <?php endif; ?>
    </div>
    <?php else: ?>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 fade-up-1">
        <?php foreach($teams as $t): ?>
        <a href="<?= APP_URL ?>/teams/<?= $t['id'] ?>"
           class="glass rounded-2xl p-6 hover:bg-white/[.07] hover:border-green-500/20 transition-all duration-200 group">
            <div class="w-14 h-14 glass-green rounded-xl flex items-center justify-center text-2xl mb-4 group-hover:scale-105 transition-transform">
                🛡️
            </div>
            <h3 class="font-black text-base mb-1 truncate"><?= htmlspecialchars($t['name']) ?></h3>
            <p class="text-gray-500 text-xs mb-4">📍 <?= htmlspecialchars($t['city']) ?></p>
            <div class="flex items-center justify-between text-xs">
                <span class="text-gray-600"><?= $t['player_count'] ?? '—' ?> jugadores</span>
                <span class="text-green-400 font-semibold">Ver →</span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</main>
<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
