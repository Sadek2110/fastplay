<main class="fp-fade fp-page fp-chat-container"
      data-chat-room
      data-room-id="<?= (int) $room['id'] ?>">
    <p class="fp-eyebrow"><?= $room['type'] === 'match_negotiation' ? 'Negociación de partido' : 'Chat de equipo' ?></p>
    <div class="fp-page-head">
        <h1 class="fp-h1"><?= e($room['name']) ?></h1>
        <?php $this->partial('back-button', ['href' => $room['type'] === 'match_negotiation' ? url('match-request/show/' . (int) $room['match_request_id']) : url('teams')]); ?>
    </div>

    <div class="fp-glass fp-panel fp-chat-panel">
        <div id="fp-chat-feed"
             class="fp-chat-feed"
             data-chat-feed
             data-chat-messages="<?= url('chat/messages/' . (int) $room['id']) ?>">
            <?php if (empty($messages)): ?>
                <?php $this->partial('empty-state', ['icon' => 'bi-chat-dots', 'title' => 'Sin mensajes', 'description' => 'Sé el primero en escribir.']); ?>
            <?php else: ?>
                <?php foreach ($messages as $m):
                    $own = (int) $m['user_id'] === (int) current_user()['id'];
                    $canDelete = is_admin() || $own;
                ?>
                    <div class="fp-msg <?= $own ? 'own' : '' ?>" data-msg-id="<?= (int) $m['id'] ?>">
                        <div class="fp-msg-meta">
                            <strong><?= e($m['user_name']) ?></strong>
                            <span><?= e(date('d/m H:i', strtotime($m['created_at']))) ?></span>
                            <?php if ($canDelete): ?>
                                <form method="post"
                                      action="<?= url('chat/deleteMessage/' . (int) $m['id']) ?>"
                                      class="fp-msg-del-form"
                                      onsubmit="return confirm('¿Eliminar este mensaje?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="fp-msg-del" title="Borrar mensaje" aria-label="Borrar mensaje">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <div class="fp-msg-body"><?= e($m['body']) ?></div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <form method="post"
              action="<?= url('chat/send/' . (int) $room['id']) ?>"
              class="fp-chat-form"
              data-chat-form
              data-fp-validate
              novalidate>
            <?= csrf_field() ?>
            <input name="body"
                   class="fp-input"
                   placeholder="Escribe un mensaje"
                   maxlength="800"
                   minlength="1"
                   required
                   data-fp-validate-field
                   data-fp-rule="chat-message"
                   autofocus>
            <button class="fp-btn fp-btn-primary"><i class="bi bi-send"></i><span>Enviar</span></button>
        </form>
    </div>
</main>
