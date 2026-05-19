<main class="fp-fade fp-page">
    <div class="fp-page-head">
        <div>
            <p class="fp-eyebrow">Directorio</p>
            <h1 class="fp-h1">Todos los equipos</h1>
        </div>
        <?php $this->partial('back-button', ['href' => url('teams')]); ?>
    </div>

    <form class="fp-toolbar" method="get" action="<?= url('teams/all') ?>">
        <input class="fp-input" name="q" value="<?= e($q ?? '') ?>" placeholder="Buscar por nombre, ciudad o capitán">
        <select class="fp-input" name="sort">
            <option value="name" <?= ($sort ?? '') === 'name' ? 'selected' : '' ?>>Ordenar por nombre</option>
            <option value="points" <?= ($sort ?? '') === 'points' ? 'selected' : '' ?>>Ordenar por puntos</option>
            <option value="created" <?= ($sort ?? '') === 'created' ? 'selected' : '' ?>>Más recientes</option>
        </select>
        <button class="fp-btn fp-btn-primary"><i class="bi bi-search"></i><span>Filtrar</span></button>
    </form>

    <div class="fp-table-wrap fp-glass">
        <table class="fp-table">
            <thead><tr><th>Escudo</th><th>Nombre</th><th>Capitán</th><th>Puntos</th><th>Jugadores</th><th>Estado</th><th></th></tr></thead>
            <tbody>
            <?php foreach ($teams as $t): ?>
                <tr>
                    <td><span class="fp-team-badge small"><?= e($t['badge'] ?? 'FP') ?></span></td>
                    <td><?= e($t['name']) ?><br><small><?= e($t['city']) ?></small></td>
                    <td><?= e($t['captain_name']) ?></td>
                    <td><?= (int) ($t['points'] ?? 0) ?></td>
                    <td><?= (int) $t['players'] ?></td>
                    <td><span class="fp-status fp-status-confirmed">Activo</span></td>
                    <td><a class="fp-btn fp-btn-ghost" href="<?= url('teams/show/' . (int) $t['id']) ?>">Detalles</a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
