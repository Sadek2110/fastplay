<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Admin</p>
            <h1 class="fp-h1">Campos</h1>
        </div>
        <a class="fp-btn fp-btn-primary fp-btn-glow" href="<?= url('campos/create') ?>">+ Nuevo campo</a>
    </div>

    <div class="fp-glass" style="border-radius:18px;overflow:hidden;margin-top:24px;">
        <table class="fp-table">
            <thead><tr><th>ID</th><th>Nombre</th><th>Ciudad</th><th>Superficie</th><th>Cap.</th><th>Tarifa</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($fields as $f): ?>
                    <tr>
                        <td><?= (int) $f['id'] ?></td>
                        <td><?= e($f['name']) ?></td>
                        <td><?= e($f['city']) ?></td>
                        <td><?= e($f['surface']) ?></td>
                        <td><?= (int) $f['capacity'] ?></td>
                        <td><?= number_format((float) $f['hourly_rate'], 2, ',', '.') ?> €/h</td>
                        <td>
                            <a class="fp-btn fp-btn-ghost" style="padding:6px 12px;font-size:11px;" href="<?= url('campos/show/' . (int) $f['id']) ?>">Ver</a>
                            <form method="post" action="<?= url('admin/deleteField/' . (int) $f['id']) ?>" style="display:inline;margin:0;" onsubmit="return confirm('¿Eliminar el campo?');">
                                <?= csrf_field() ?>
                                <button class="fp-btn fp-btn-ghost" style="padding:6px 12px;font-size:11px;color:#f87171;">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
