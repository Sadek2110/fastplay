<?php
/** @var array $_flash */
if (empty($_flash)) return; ?>
<div class="fp-flash-stack">
    <?php foreach ($_flash as $f):
        $type = $f['type'] ?? 'ok';
        $cls = 'fp-flash-ok';
        $icon = '✓';
        if ($type === 'warn') { $cls = 'fp-flash-warn'; $icon = '⚠'; }
        if ($type === 'err')  { $cls = 'fp-flash-err';  $icon = '✕'; }
    ?>
        <div class="fp-flash <?= $cls ?>">
            <span class="fp-flash-icon"><?= $icon ?></span>
            <span><?= e($f['msg']) ?></span>
        </div>
    <?php endforeach; ?>
</div>