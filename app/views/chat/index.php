<?php $pageTitle = 'Chat'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-4xl mx-auto">
    <div class="mb-8 fade-up">
        <p class="text-green-400 font-semibold text-sm uppercase tracking-widest mb-2">Mensajes</p>
        <h1 class="text-3xl font-black">Chat</h1>
    </div>

    <?php if (empty($rooms)): ?>
    <div class="text-center py-24 fade-up-1">
        <div class="text-5xl mb-4">💬</div>
        <p class="text-gray-400 font-medium">No tienes conversaciones todavía</p>
        <p class="text-gray-600 text-sm mt-2">Únete a un equipo para acceder al chat</p>
    </div>
    <?php else: ?>
    <div class="space-y-2 fade-up-1">
        <?php foreach($rooms as $r): ?>
        <a href="<?= APP_URL ?>/chat/<?= $r['id'] ?>"
           class="glass rounded-2xl p-5 flex items-center gap-4 hover:bg-white/[.07] transition-all duration-200">
            <div class="w-12 h-12 glass-green rounded-xl flex items-center justify-center text-xl flex-shrink-0">
                <?= $r['type']==='team' ? '👥' : ($r['type']==='match_negotiation' ? '⚽' : '💬') ?>
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-bold text-sm"><?= htmlspecialchars($r['name']) ?></div>
                <div class="text-xs text-gray-500 truncate mt-0.5">
                    <?= htmlspecialchars($r['last_message'] ?? 'Sin mensajes todavía') ?>
                </div>
            </div>
            <?php if (!empty($r['last_message_time'])): ?>
            <div class="text-xs text-gray-600 flex-shrink-0">
                <?= date('H:i', strtotime($r['last_message_time'])) ?>
            </div>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</main>
<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
