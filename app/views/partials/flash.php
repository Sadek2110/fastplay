<?php
/** @var array $_flash */
if (empty($_flash)) return; ?>
<div class="fp-flash-stack">
    <?php foreach ($_flash as $f):
        $type = $f['type'] ?? 'ok';
        $cls = 'fp-flash-ok';
        $icon = 'bi-check-circle';
        if ($type === 'warn') { $cls = 'fp-flash-warn'; $icon = 'bi-exclamation-triangle'; }
        if ($type === 'err')  { $cls = 'fp-flash-err';  $icon = 'bi-x-circle'; }
    ?>
        <div class="fp-flash <?= $cls ?>">
            <i class="fp-flash-icon bi <?= e($icon) ?>"></i>
            <span><?= e($f['msg']) ?></span>
        </div>
    <?php endforeach; ?>
</div>
