<?php $pageTitle = 'Partidos'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-7xl mx-auto">
    <div class="mb-10 fade-up">
        <p class="text-green-400 font-semibold text-sm uppercase tracking-widest mb-2">Calendario</p>
        <h1 class="text-3xl font-black">Partidos</h1>
    </div>

    <?php if (empty($matches)): ?>
    <div class="text-center py-24 fade-up-1">
        <div class="text-5xl mb-4">🏟️</div>
        <p class="text-gray-400 font-medium">No hay partidos programados</p>
        <p class="text-gray-600 text-sm mt-2">Los capitanes pueden pactar partidos desde el chat</p>
    </div>
    <?php else: ?>
    <div class="space-y-3 fade-up-1">
        <?php foreach($matches as $m): ?>
        <a href="<?= APP_URL ?>/matches/<?= $m['id'] ?>"
           class="glass rounded-2xl p-6 flex flex-col sm:flex-row items-start sm:items-center gap-5 hover:bg-white/[.07] hover:border-green-500/20 transition-all duration-200 group">

            <div class="min-w-[80px] text-center">
                <div class="text-lg font-black"><?= date('d', strtotime($m['match_date'])) ?></div>
                <div class="text-xs text-gray-500 uppercase"><?= date('M', strtotime($m['match_date'])) ?></div>
                <div class="text-xs text-green-400 font-medium"><?= date('H:i', strtotime($m['match_date'])) ?></div>
            </div>

            <div class="w-px h-12 bg-white/10 hidden sm:block flex-shrink-0"></div>

            <div class="flex-1 flex items-center justify-center gap-4">
                <span class="font-bold text-right flex-1 truncate"><?= htmlspecialchars($m['home_team_name']) ?></span>
                <div class="flex-shrink-0 px-4 py-2 glass rounded-xl">
                    <?php if ($m['status'] === 'finished'): ?>
                        <span class="font-black text-lg"><?= $m['home_score'] ?> – <?= $m['away_score'] ?></span>
                    <?php else: ?>
                        <span class="font-black text-sm text-gray-400">VS</span>
                    <?php endif; ?>
                </div>
                <span class="font-bold text-left flex-1 truncate"><?= htmlspecialchars($m['away_team_name']) ?></span>
            </div>

            <div class="text-right min-w-[100px]">
                <div class="text-xs text-gray-500 truncate">🏟️ <?= htmlspecialchars($m['field_name'] ?? '—') ?></div>
                <div class="mt-2">
                    <?php
                    $badge = match($m['status']) {
                        'confirmed' => ['bg-green-500/10 text-green-400 border-green-500/20','Confirmado'],
                        'finished'  => ['bg-white/10 text-gray-400 border-white/10','Finalizado'],
                        'cancelled' => ['bg-red-500/10 text-red-400 border-red-500/20','Cancelado'],
                        default     => ['bg-yellow-500/10 text-yellow-400 border-yellow-500/20','Pendiente'],
                    };
                    ?>
                    <span class="text-xs px-2.5 py-0.5 rounded-full border <?= $badge[0] ?> font-medium"><?= $badge[1] ?></span>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</main>
<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
