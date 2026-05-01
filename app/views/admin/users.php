<?php $pageTitle = 'Usuarios'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-7xl mx-auto">
    <div class="mb-8 fade-up">
        <a href="<?= APP_URL ?>/admin" class="text-sm text-gray-500 hover:text-white mb-3 inline-flex items-center gap-1">
            ← Volver al panel
        </a>
        <h1 class="text-2xl font-black">👤 Gestión de Usuarios</h1>
        <p class="text-gray-500 text-sm mt-1"><?= count($users) ?> usuarios registrados</p>
    </div>

    <div class="glass rounded-2xl overflow-hidden fade-up-1">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white/[.03] text-gray-400 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="text-left px-4 py-3">Usuario</th>
                        <th class="text-left px-4 py-3 hidden sm:table-cell">Teléfono</th>
                        <th class="text-left px-4 py-3 hidden md:table-cell">Rol</th>
                        <th class="text-left px-4 py-3 hidden lg:table-cell">Posición</th>
                        <th class="text-left px-4 py-3 hidden md:table-cell">Ciudad</th>
                        <th class="text-left px-4 py-3">Estado</th>
                        <th class="text-right px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($users as $u): ?>
                    <tr class="hover:bg-white/[.03] transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-3">
                                <img src="<?= APP_URL ?>/public/images/uploads/profiles/<?= htmlspecialchars($u['photo'] ?? 'default-avatar.svg') ?>"
                                     onerror="this.src='<?= APP_URL ?>/public/images/default-avatar.svg'"
                                     class="w-8 h-8 rounded-full object-cover" alt="">
                                <div>
                                    <div class="font-semibold"><?= htmlspecialchars($u['name']) ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($u['email']) ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-400 hidden sm:table-cell"><?= htmlspecialchars($u['phone'] ?? '—') ?></td>
                        <td class="px-4 py-3 hidden md:table-cell">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                <?= $u['role'] === 'admin' ? 'bg-red-500/20 text-red-400' :
                                    ($u['role'] === 'captain' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-green-500/20 text-green-400') ?>">
                                <?= htmlspecialchars($u['role']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 text-xs hidden lg:table-cell"><?= htmlspecialchars($u['position'] ?: '—') ?></td>
                        <td class="px-4 py-3 text-gray-500 hidden md:table-cell"><?= htmlspecialchars($u['city'] ?: '—') ?></td>
                        <td class="px-4 py-3">
                            <?php if ($u['is_banned']): ?>
                                <span class="text-red-400 text-xs font-semibold">⛔ Baneado</span>
                            <?php else: ?>
                                <span class="text-green-400 text-xs">Activo</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="<?= APP_URL ?>/admin/users/<?= $u['id'] ?>/toggle-ban" class="inline">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
                                <button type="submit" class="text-xs px-3 py-1 rounded-lg font-semibold transition-colors
                                    <?= $u['is_banned']
                                        ? 'bg-green-600/20 text-green-400 hover:bg-green-600/30'
                                        : 'bg-red-600/20 text-red-400 hover:bg-red-600/30' ?>">
                                    <?= $u['is_banned'] ? 'Desbanear' : 'Banear' ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
