<?php $pageTitle = 'Partido'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-5xl mx-auto">

    <!-- Scoreboard -->
    <div class="glass rounded-3xl p-8 mb-6 fade-up text-center"
         style="background:linear-gradient(135deg,rgba(22,163,74,.08),rgba(22,163,74,.03));border-color:rgba(22,163,74,.15);">
        <div class="text-xs text-gray-500 uppercase tracking-widest mb-6 font-medium">
            📅 <?= date('d/m/Y · H:i', strtotime($match['match_date'])) ?>
            &nbsp;·&nbsp; 🏟️ <?= htmlspecialchars($match['field_name'] ?? 'Campo por confirmar') ?>
        </div>

        <div class="flex items-center justify-center gap-6 sm:gap-12">
            <div class="flex-1 text-right">
                <div class="text-2xl sm:text-3xl font-black"><?= htmlspecialchars($match['home_team_name']) ?></div>
                <div class="text-gray-500 text-sm mt-1">Local</div>
            </div>
            <div class="text-center flex-shrink-0">
                <?php if ($match['status'] === 'finished'): ?>
                    <div class="text-5xl sm:text-6xl font-black leading-none"><?= $match['home_score'] ?> – <?= $match['away_score'] ?></div>
                    <div class="text-xs text-green-400 font-semibold mt-3 uppercase tracking-wide">Finalizado</div>
                <?php else: ?>
                    <div class="glass rounded-2xl px-8 py-4 text-2xl font-black text-gray-400">VS</div>
                    <div class="text-xs text-yellow-400 font-semibold mt-3 uppercase tracking-wide">
                        <?= $match['status'] === 'confirmed' ? 'Confirmado' : 'Pendiente' ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="flex-1 text-left">
                <div class="text-2xl sm:text-3xl font-black"><?= htmlspecialchars($match['away_team_name']) ?></div>
                <div class="text-gray-500 text-sm mt-1">Visitante</div>
            </div>
        </div>
    </div>

    <!-- Lineups -->
    <?php if (!empty($lineups)): ?>
    <div class="grid md:grid-cols-2 gap-4 fade-up-1">
        <?php foreach(['home'=>'Local','away'=>'Visitante'] as $side=>$label): ?>
        <div class="glass rounded-2xl p-6">
            <h2 class="text-lg font-black mb-5"><?= $label ?> — <?= htmlspecialchars($match[$side.'_team_name']) ?></h2>
            <div class="space-y-2">
                <?php foreach(($lineups[$side] ?? []) as $p): ?>
                <div class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-white/5 transition-colors">
                    <span class="w-7 h-7 glass rounded-lg flex items-center justify-center text-xs font-black text-green-400 flex-shrink-0">
                        <?= $p['jersey_number'] ?? '?' ?>
                    </span>
                    <span class="text-sm font-medium flex-1"><?= htmlspecialchars($p['name']) ?></span>
                    <span class="text-xs text-gray-500 capitalize"><?= htmlspecialchars($p['position'] ?? '') ?></span>
                    <?php if (!$p['is_starter']): ?>
                    <span class="text-xs text-yellow-400">Suplente</span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</main>
<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
