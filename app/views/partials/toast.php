<?php $type = $type ?? 'info'; ?>
<div class="fp-toast fp-toast-<?= e($type) ?>">
    <i class="bi bi-info-circle"></i>
    <span><?= e($message ?? '') ?></span>
</div>
