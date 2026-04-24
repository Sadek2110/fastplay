<?php $pageTitle = $league['name'] ?? 'Liga'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-5xl mx-auto">

    <!-- Header liga -->
    <div class="rounded-3xl p-8 mb-6 fade-up"
         style="background:<?= $league['type']==='pro' ? 'linear-gradient(135deg,rgba(22,163,74,.12),rgba(22,163,74,.04))' : 'rgba(255,255,255,.04)' ?>;
                border:1px solid <?= $league['type']==='pro' ? 'rgba(22,163,74,.25)' : 'rgba(255,255,255,.08)' ?>;">
        <div class="flex flex-col sm:flex-row gap-6">
            <div class="text-5xl flex-shrink-0"><?= $league['type']==='pro' ? '🏆' : '🤝' ?></div>
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2 flex-wrap">
                    <h1 class="text-3xl font-black"><?= htmlspecialchars($league['name']) ?></h1>
                    <?php if ($league['type']==='pro'): ?>
                    <span class="px-3 py-1 rounded-full text-xs font-black" style="background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#000;">PRO</span>
                    <?php endif; ?>
                </div>
                <p class="text-gray-400 text-sm mb-4">📍 <?= htmlspecialchars($league['city']) ?> · <?= date('d/m/Y',strtotime($league['start_date'])) ?> – <?= date('d/m/Y',strtotime($league['end_date'])) ?></p>
                <?php if (!empty($league['prize_pool'])): ?>
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-yellow-500/10 border border-yellow-500/20">
                    <span class="text-yellow-400 font-black">💰 <?= number_format($league['prize_pool'],2) ?> €</span>
                    <span class="text-gray-500 text-xs">fondo de premios · 🥇40% 🥈20% 🥉10%</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Standings -->
    <?php if (!empty($standings)): ?>
    <div class="glass rounded-3xl p-6 mb-6 fade-up-1 overflow-x-auto">
        <h2 class="text-xl font-black mb-5">Clasificación</h2>
        <table class="w-full text-sm min-w-[500px]">
            <thead>
                <tr class="text-xs text-gray-500 uppercase tracking-wider border-b border-white/10">
                    <th class="pb-3 text-left w-8">#</th>
                    <th class="pb-3 text-left">Equipo</th>
                    <th class="pb-3 text-center w-10">PJ</th>
                    <th class="pb-3 text-center w-10">G</th>
                    <th class="pb-3 text-center w-10">E</th>
                    <th class="pb-3 text-center w-10">P</th>
                    <th class="pb-3 text-center w-10">GF</th>
                    <th class="pb-3 text-center w-10">GC</th>
                    <th class="pb-3 text-center w-12 font-bold text-white">Pts</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($standings as $i=>$s): ?>
                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors <?= $i===0 ? 'text-yellow-400' : ($i===1 ? 'text-gray-300' : ($i===2 ? 'text-orange-400' : '')) ?>">
                    <td class="py-3 font-black text-sm"><?= $i+1 ?></td>
                    <td class="py-3 font-semibold"><?= htmlspecialchars($s['team_name']) ?></td>
                    <td class="py-3 text-center text-gray-400"><?= $s['played'] ?></td>
                    <td class="py-3 text-center text-gray-400"><?= $s['won'] ?></td>
                    <td class="py-3 text-center text-gray-400"><?= $s['drawn'] ?></td>
                    <td class="py-3 text-center text-gray-400"><?= $s['lost'] ?></td>
                    <td class="py-3 text-center text-gray-400"><?= $s['goals_for'] ?></td>
                    <td class="py-3 text-center text-gray-400"><?= $s['goals_against'] ?></td>
                    <td class="py-3 text-center font-black text-white"><?= $s['points'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

</main>
<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
