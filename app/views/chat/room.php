<?php $pageTitle = $room['name']; require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="flex-1 pt-24 pb-12 px-6">
    <div class="max-w-4xl mx-auto">
        <!-- Room header -->
        <div class="glass rounded-2xl p-4 mb-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-600/20 flex items-center justify-center text-green-400 font-bold">
                    <?= $room['type'] === 'direct' ? '💬' : ($room['type'] === 'match_negotiation' ? '⚽' : '👥') ?>
                </div>
                <div>
                    <h1 class="font-bold text-lg"><?= htmlspecialchars($room['name']) ?></h1>
                    <p class="text-xs text-gray-500"><?= ucfirst(str_replace('_', ' ', $room['type'])) ?></p>
                </div>
            </div>
            <span id="online-dot" class="w-2.5 h-2.5 rounded-full bg-green-500 pulse-dot"></span>
        </div>

        <!-- Messages -->
        <div id="messages-container" class="glass rounded-2xl p-4 mb-4" style="min-height:400px; max-height:60vh; overflow-y:auto;">
            <?php if (empty($messages)): ?>
                <div class="flex flex-col items-center justify-center h-64 text-gray-500">
                    <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <p class="text-sm">No hay mensajes aún. ¡Sé el primero!</p>
                </div>
            <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                <div class="mb-4 <?= $msg['user_id'] === $_SESSION['user_id'] ? 'text-right' : '' ?>">
                    <div class="inline-block max-w-[75%]">
                        <div class="flex items-center gap-2 mb-1 <?= $msg['user_id'] === $_SESSION['user_id'] ? 'justify-end' : '' ?>">
                            <img src="<?= APP_URL ?>/public/images/uploads/profiles/<?= htmlspecialchars($msg['author_photo'] ?? 'default-avatar.svg') ?>"
                                 onerror="this.src='<?= APP_URL ?>/public/images/default-avatar.svg'"
                                 class="w-5 h-5 rounded-full object-cover" alt="">
                            <span class="text-xs text-gray-400"><?= htmlspecialchars($msg['author_name']) ?></span>
                            <span class="text-[10px] text-gray-600"><?= date('H:i', strtotime($msg['created_at'])) ?></span>
                        </div>
                        <div class="rounded-2xl px-4 py-2 <?= $msg['user_id'] === $_SESSION['user_id'] ? 'bg-green-600/30 rounded-br-md' : 'bg-white/5 rounded-bl-md' ?>">
                            <p class="text-sm leading-relaxed break-words"><?= nl2br(htmlspecialchars($msg['content'])) ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Message input -->
        <form id="chat-form" class="flex gap-3" action="<?= APP_URL ?>/chat/<?= $room['id'] ?>/send" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="text" id="chat-input" name="content"
                   placeholder="Escribe un mensaje..."
                   class="flex-1 input-dark" autocomplete="off" maxlength="1000" required>
            <button type="submit"
                    class="bg-green-600 hover:bg-green-500 text-white px-6 rounded-full font-semibold transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
                Enviar
            </button>
        </form>
    </div>
</main>

<script>
const chatForm = document.getElementById('chat-form');
const msgContainer = document.getElementById('messages-container');
const chatInput = document.getElementById('chat-input');

if (msgContainer) msgContainer.scrollTop = msgContainer.scrollHeight;

chatForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const content = chatInput.value.trim();
    if (!content) return;

    chatInput.disabled = true;
    const btn = chatForm.querySelector('button');
    btn.disabled = true;
    btn.classList.add('opacity-50');

    try {
        const res = await fetch(chatForm.action, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ content, csrf_token: chatForm.querySelector('[name="csrf_token"]').value })
        });
        const data = await res.json();
        if (data.success) {
            chatInput.value = '';
            appendMessage(data.message);
        } else {
            alert(data.error || 'Error al enviar');
        }
    } catch (err) {
        alert('Error de conexión');
    }

    chatInput.disabled = false;
    btn.disabled = false;
    btn.classList.remove('opacity-50');
    chatInput.focus();
});

function appendMessage(msg) {
    const isMine = msg.user_id == <?= $_SESSION['user_id'] ?>;
    const div = document.createElement('div');
    div.className = `mb-4 ${isMine ? 'text-right' : ''}`;
    div.innerHTML = `
        <div class="inline-block max-w-[75%]">
            <div class="flex items-center gap-2 mb-1 ${isMine ? 'justify-end' : ''}">
                <img src="<?= APP_URL ?>/public/images/default-avatar.svg" class="w-5 h-5 rounded-full object-cover" alt="">
                <span class="text-xs text-gray-400">${utils.escapeHtml(msg.author_name)}</span>
                <span class="text-[10px] text-gray-600">${msg.created_at}</span>
            </div>
            <div class="rounded-2xl px-4 py-2 ${isMine ? 'bg-green-600/30 rounded-br-md' : 'bg-white/5 rounded-bl-md'}">
                <p class="text-sm leading-relaxed break-words">${utils.escapeHtml(msg.content)}</p>
            </div>
        </div>`;
    msgContainer.appendChild(div);
    msgContainer.scrollTop = msgContainer.scrollHeight;
}
</script>

<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
