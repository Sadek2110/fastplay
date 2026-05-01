<?php $pageTitle = 'Ligas'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-7xl mx-auto">
    <div class="mb-8 fade-up">
        <a href="<?= APP_URL ?>/admin" class="text-sm text-gray-500 hover:text-white mb-3 inline-flex items-center gap-1">
            ← Volver al panel
        </a>
        <h1 class="text-2xl font-black">🏆 Gestión de Ligas</h1>
        <p class="text-gray-500 text-sm mt-1"><?= count($leagues) ?> ligas registradas</p>
    </div>

    <div class="glass rounded-2xl overflow-hidden fade-up-1">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white/[.03] text-gray-400 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-4 py-3">Liga</th>
                        <th class="text-left px-4 py-3 hidden sm:table-cell">Tipo</th>
                        <th class="text-left px-4 py-3 hidden md:table-cell">Ciudad</th>
                        <th class="text-left px-4 py-3 hidden lg:table-cell">Fechas</th>
                        <th class="text-left px-4 py-3">Premio</th>
                        <th class="text-left px-4 py-3">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($leagues as $l): ?>
                    <tr class="hover:bg-white/[.03] transition-colors">
                        <td class="px-4 py-3 font-semibold"><?= htmlspecialchars($l['name']) ?></td>
                        <td class="px-4 py-3 hidden sm:table-cell">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                <?= $l['type'] === 'pro' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-green-500/20 text-green-400' ?>">
                                <?= $l['type'] === 'pro' ? '⭐ Pro' : '🤝 Amistosa' ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 hidden md:table-cell"><?= htmlspecialchars($l['city']) ?></td>
                        <td class="px-4 py-3 text-xs text-gray-500 hidden lg:table-cell">
                            <?= date('d/m/Y', strtotime($l['start_date'])) ?> — <?= date('d/m/Y', strtotime($l['end_date'])) ?>
                        </td>
                        <td class="px-4 py-3 font-semibold <?= $l['prize_pool'] > 0 ? 'text-yellow-400' : 'text-gray-500' ?>">
                            <?= $l['prize_pool'] > 0 ? '€' . number_format((float)$l['prize_pool'], 2) : '—' ?>
                        </td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                <?= $l['status'] === 'active'    ? 'bg-green-500/20 text-green-400' :
                                   ($l['status'] === 'upcoming'  ? 'bg-blue-500/20 text-blue-400'
                                                                 : 'bg-gray-500/20 text-gray-400') ?>">
                                <?= ucfirst($l['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
