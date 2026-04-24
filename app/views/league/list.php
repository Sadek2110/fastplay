<?php $pageTitle = 'Ligas'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-7xl mx-auto">
    <div class="mb-12 fade-up">
        <p class="text-green-400 font-semibold text-sm uppercase tracking-widest mb-2">Competiciones</p>
        <h1 class="text-4xl font-black">Ligas activas</h1>
        <p class="text-gray-400 mt-2">Compite en tu ciudad. Amistosa (gratis) o Pro (20 €/temporada).</p>
    </div>

    <!-- Liga Pro -->
    <?php $pro = array_filter($leagues ?? [], fn($l)=>$l['type']==='pro'); ?>
    <?php if (!empty($pro)): ?>
    <div class="mb-10">
        <div class="flex items-center gap-3 mb-5">
            <span class="px-3 py-1 rounded-full text-xs font-black" style="background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#000;">🏆 LIGA PRO</span>
            <h2 class="text-xl font-black">Ligas Profesionales</h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 fade-up-1">
            <?php foreach($pro as $l): ?>
            <a href="<?= APP_URL ?>/leagues/<?= $l['id'] ?>"
               class="rounded-2xl p-6 hover:scale-[1.02] transition-all duration-200 relative overflow-hidden"
               style="background:linear-gradient(135deg,rgba(22,163,74,.12),rgba(22,163,74,.04));border:1px solid rgba(22,163,74,.25);">
                <div class="absolute top-4 right-4 text-yellow-400 text-xl">🏆</div>
                <h3 class="font-black text-lg mb-1 pr-8"><?= htmlspecialchars($l['name']) ?></h3>
                <p class="text-gray-400 text-sm mb-4">📍 <?= htmlspecialchars($l['city']) ?></p>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-600"><?= date('d/m/Y', strtotime($l['start_date'])) ?> – <?= date('d/m/Y', strtotime($l['end_date'])) ?></span>
                    <span class="text-green-400 font-semibold">Ver →</span>
                </div>
                <?php if (!empty($l['prize_pool'])): ?>
                <div class="mt-3 text-xs px-2.5 py-1 rounded-full bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 inline-block font-medium">
                    💰 Premio: <?= number_format($l['prize_pool'],2) ?> €
                </div>
                <?php endif; ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Liga Amistosa -->
    <?php $friendly = array_filter($leagues ?? [], fn($l)=>$l['type']==='friendly'); ?>
    <?php if (!empty($friendly)): ?>
    <div>
        <div class="flex items-center gap-3 mb-5">
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-white/10 text-gray-300 border border-white/10">🤝 AMISTOSA</span>
            <h2 class="text-xl font-black">Ligas Amistosas</h2>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 fade-up-2">
            <?php foreach($friendly as $l): ?>
            <a href="<?= APP_URL ?>/leagues/<?= $l['id'] ?>"
               class="glass rounded-2xl p-6 hover:bg-white/[.07] transition-all duration-200">
                <h3 class="font-black text-base mb-1"><?= htmlspecialchars($l['name']) ?></h3>
                <p class="text-gray-500 text-sm mb-4">📍 <?= htmlspecialchars($l['city']) ?></p>
                <div class="flex items-center justify-between text-xs">
                    <span class="text-gray-600"><?= date('d/m/Y', strtotime($l['start_date'])) ?> – <?= date('d/m/Y', strtotime($l['end_date'])) ?></span>
                    <span class="text-green-400 font-semibold">Ver →</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if (empty($leagues)): ?>
    <div class="text-center py-24 fade-up-1">
        <div class="text-5xl mb-4">🏆</div>
        <p class="text-gray-400 font-medium">No hay ligas activas en este momento</p>
        <p class="text-gray-600 text-sm mt-2">Vuelve pronto para la nueva temporada</p>
    </div>
    <?php endif; ?>
</main>
<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
