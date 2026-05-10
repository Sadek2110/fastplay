<main class="fp-fade fp-page" style="max-width:980px;">
    <p class="fp-eyebrow"><?= $room['type'] === 'match_negotiation' ? '🤝 Negociación de partido' : '💬 Sala' ?></p>
    <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
        <h1 class="fp-h1" style="margin:0;"><?= e($room['name']) ?></h1>
        <a href="<?= url('chat') ?>" class="fp-btn fp-btn-ghost" style="padding:8px 14px;font-size:12px;">← Todas las salas</a>
    </div>

    <div class="fp-glass" style="border-radius:18px;padding:18px;margin-top:18px;display:flex;flex-direction:column;height:60vh;min-height:420px;">
        <div id="fp-chat-feed" class="fp-chat-feed">
            <?php if (empty($messages)): ?>
                <div class="fp-empty" style="margin:auto;">⏳ Sé el primero en escribir.</div>
            <?php else: ?>
                <?php foreach ($messages as $m): $own = (int) $m['user_id'] === (int) current_user()['id']; ?>
                    <div class="fp-msg <?= $own ? 'own' : '' ?>">
                        <div class="fp-msg-meta">
                            <strong><?= e($m['user_name']) ?></strong>
                            <span><?= e(date('d/m H:i', strtotime($m['created_at']))) ?></span>
                        </div>
                        <div class="fp-msg-body"><?= e($m['body']) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <form method="post" action="<?= url('chat/send/' . (int) $room['id']) ?>" style="display:flex;gap:10px;margin-top:12px;">
            <?= csrf_field() ?>
            <input name="body" class="fp-input" placeholder="Escribe un mensaje…" maxlength="800" required autofocus>
            <button class="fp-btn fp-btn-primary fp-btn-glow">Enviar →</button>
        </form>
    </div>
</main>
<script>
(function () {
    var feed = document.getElementById('fp-chat-feed');
    if (feed) feed.scrollTop = feed.scrollHeight;
})();
</script>