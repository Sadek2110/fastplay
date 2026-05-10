<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Admin</p>
            <h1 class="fp-h1">Ligas</h1>
        </div>
        <a class="fp-btn fp-btn-primary fp-btn-glow" href="<?= url('leagues/create') ?>">+ Nueva liga</a>
    </div>

    <div class="fp-glass" style="border-radius:18px;overflow:hidden;margin-top:24px;">
        <table class="fp-table">
            <thead><tr><th>ID</th><th>Liga</th><th>Tier</th><th>Ciudad</th><th>Equipos</th><th>Calendario</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($leagues as $l): ?>
                    <tr>
                        <td><?= (int) $l['id'] ?></td>
                        <td><?= e($l['name']) ?></td>
                        <td><?= $l['pro'] ? '🏆 Pro' : '🤝 Amistosa' ?></td>
                        <td><?= e($l['city']) ?></td>
                        <td><?= (int) ($l['team_count'] ?? 0) ?>/<?= (int) $l['max_teams'] ?></td>
                        <td style="color:#9ca3af;"><?= e($l['start']) ?> – <?= e($l['end']) ?></td>
                        <td>
                            <a class="fp-btn fp-btn-ghost" style="padding:6px 12px;font-size:11px;" href="<?= url('leagues/show/' . (int) $l['id']) ?>">Ver</a>
                            <form method="post" action="<?= url('admin/deleteLeague/' . (int) $l['id']) ?>" style="display:inline;margin:0;" onsubmit="return confirm('¿Eliminar la liga?');">
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
