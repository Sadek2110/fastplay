<main class="fp-fade fp-page">
    <p class="fp-eyebrow">Admin</p>
    <h1 class="fp-h1">Equipos</h1>

    <div class="fp-glass" style="border-radius:18px;overflow:hidden;margin-top:24px;">
        <table class="fp-table">
            <thead><tr><th>ID</th><th>Equipo</th><th>Ciudad</th><th>Capitán</th><th>Jugadores</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($teams as $t): ?>
                    <tr>
                        <td><?= (int) $t['id'] ?></td>
                    <td><span class="fp-team-badge small"><?= e($t['badge'] ?? 'FP') ?></span> <?= e($t['name']) ?></td>
                        <td><?= e($t['city']) ?></td>
                        <td><?= e($t['captain_name']) ?></td>
                        <td><?= (int) $t['players'] ?></td>
                        <td>
                            <a class="fp-btn fp-btn-ghost" style="padding:6px 12px;font-size:11px;" href="<?= url('teams/show/' . (int) $t['id']) ?>">Ver</a>
                            <form method="post" action="<?= url('teams/delete/' . (int) $t['id']) ?>" style="display:inline;margin:0;" onsubmit="return confirm('¿Eliminar el equipo?');">
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
