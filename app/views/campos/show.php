<main class="fp-fade fp-page" style="max-width:680px;">
    <p class="fp-eyebrow">Campo</p>
    <h1 class="fp-h1"><?= e($field['name']) ?></h1>
    <p style="color:#9ca3af;font-size:14px;margin-top:6px;">📍 <?= e($field['city']) ?></p>

    <div class="fp-glass" style="border-radius:18px;padding:24px;margin-top:24px;">
        <ul class="fp-detail">
            <li><span>Dirección</span><strong><?= e($field['address'] ?? '—') ?></strong></li>
            <li><span>Superficie</span><strong><?= e($field['surface']) ?></strong></li>
            <li><span>Capacidad</span><strong><?= (int) $field['capacity'] ?> jugadores</strong></li>
            <li><span>Tarifa</span><strong><?= number_format((float) $field['hourly_rate'], 2, ',', '.') ?> €/hora</strong></li>
        </ul>
        <a href="<?= url('matches/create') ?>" class="fp-btn fp-btn-primary fp-btn-glow" style="margin-top:18px;">Programar partido aquí →</a>
    </div>
</main>