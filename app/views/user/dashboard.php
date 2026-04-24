<?php $pageTitle = 'Dashboard'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-7xl mx-auto">

    <!-- Header -->
    <div class="mb-10 fade-up">
        <p class="text-gray-500 text-sm mb-1">Bienvenido de vuelta</p>
        <h1 class="text-3xl font-black">
            Hola, <span class="gradient-text"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Jugador') ?></span> 👋
        </h1>
    </div>

    <!-- Stats cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10 fade-up-1">
        <?php
        $stats = [
            ['label'=>'Partidos jugados', 'val'=>$stats['matches'] ?? 0,    'icon'=>'⚽', 'color'=>'text-green-400'],
            ['label'=>'Goles',            'val'=>$stats['goals'] ?? 0,      'icon'=>'🎯', 'color'=>'text-yellow-400'],
            ['label'=>'Asistencias',      'val'=>$stats['assists'] ?? 0,    'icon'=>'🤝', 'color'=>'text-blue-400'],
            ['label'=>'Tarjetas amarillas','val'=>$stats['yellows'] ?? 0,   'icon'=>'🟨', 'color'=>'text-yellow-300'],
        ];
        foreach($stats as $s): ?>
        <div class="glass rounded-2xl p-5">
            <div class="text-2xl mb-3"><?= $s['icon'] ?></div>
            <div class="text-3xl font-black <?= $s['color'] ?>"><?= $s['val'] ?></div>
            <div class="text-xs text-gray-500 font-medium mt-1"><?= $s['label'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">

        <!-- Equipo -->
        <div class="glass rounded-2xl p-6 fade-up-2">
            <h2 class="text-lg font-black mb-5">Mi equipo</h2>
            <?php if (!empty($team)): ?>
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 glass-green rounded-xl flex items-center justify-center text-2xl flex-shrink-0">
                        🛡️
                    </div>
                    <div>
                        <div class="font-bold"><?= htmlspecialchars($team['name']) ?></div>
                        <div class="text-sm text-gray-400">📍 <?= htmlspecialchars($team['city']) ?></div>
                    </div>
                </div>
                <a href="<?= APP_URL ?>/teams/<?= $team['id'] ?>" class="btn-ghost w-full text-center py-2.5 text-sm">Ver equipo →</a>
            <?php else: ?>
                <div class="text-center py-6">
                    <div class="text-4xl mb-3">⚽</div>
                    <p class="text-gray-400 text-sm mb-4">Aún no perteneces a ningún equipo</p>
                    <a href="<?= APP_URL ?>/teams" class="btn-primary text-sm px-5 py-2.5">Buscar equipos</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Próximos partidos -->
        <div class="glass rounded-2xl p-6 fade-up-2">
            <h2 class="text-lg font-black mb-5">Próximos partidos</h2>
            <?php if (!empty($upcomingMatches)): ?>
                <div class="space-y-3">
                    <?php foreach(array_slice($upcomingMatches,0,3) as $m): ?>
                    <a href="<?= APP_URL ?>/matches/<?= $m['id'] ?>" class="flex items-center gap-3 p-3 rounded-xl hover:bg-white/5 transition-colors">
                        <div class="w-2 h-2 bg-green-400 rounded-full flex-shrink-0"></div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-semibold truncate">
                                <?= htmlspecialchars($m['home_team_name']) ?> vs <?= htmlspecialchars($m['away_team_name']) ?>
                            </div>
                            <div class="text-xs text-gray-500"><?= date('d/m H:i', strtotime($m['match_date'])) ?></div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <a href="<?= APP_URL ?>/matches" class="block text-center text-xs text-green-400 hover:text-green-300 mt-4 transition-colors">Ver todos →</a>
            <?php else: ?>
                <p class="text-gray-500 text-sm text-center py-6">No hay partidos próximos</p>
            <?php endif; ?>
        </div>

        <!-- Logros -->
        <div class="glass rounded-2xl p-6 fade-up-3">
            <h2 class="text-lg font-black mb-5">Logros recientes</h2>
            <?php if (!empty($achievements)): ?>
                <div class="space-y-3">
                    <?php foreach(array_slice($achievements,0,4) as $a): ?>
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5">
                        <div class="text-2xl"><?= $a['icon'] ?? '🏅' ?></div>
                        <div>
                            <div class="text-xs font-semibold"><?= htmlspecialchars($a['name']) ?></div>
                            <div class="text-xs text-gray-500"><?= htmlspecialchars($a['description']) ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-6">
                    <div class="text-4xl mb-3">🎖️</div>
                    <p class="text-gray-400 text-sm">Juega partidos para desbloquear logros</p>
                </div>
            <?php endif; ?>
        </div>

    </div>

</main>
<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
