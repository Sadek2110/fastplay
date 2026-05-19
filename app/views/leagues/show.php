<main class="fp-fade fp-page">
    <?php $this->partial('back-button', ['href' => url('leagues')]); ?>
    <p class="fp-eyebrow"><?= $league['pro'] ? 'Liga Pro' : 'Liga Amistosa' ?></p>
    <h1 class="fp-h1"><?= e($league['name']) ?></h1>
    <p class="fp-muted"><i class="bi bi-geo-alt"></i> <?= e($league['city']) ?> · <?= e($league['start']) ?> - <?= e($league['end']) ?> · estado <strong><?= e($league['status']) ?></strong></p>

    <?php if (!empty($league['prize'])): ?><div class="fp-pro-badge" style="margin-top:14px;"><i class="bi bi-cash-coin"></i> Premio: <?= number_format((float) $league['prize'], 2, ',', '.') ?> EUR</div><?php endif; ?>

    <section class="fp-panel">
        <h2 class="fp-h2">Clasificación</h2>
        <?php if (empty($standings)): ?>
            <?php $this->partial('empty-state', ['icon' => 'bi-table', 'title' => 'Aún no hay equipos inscritos', 'description' => 'La clasificación se generará al inscribir equipos.']); ?>
        <?php else: ?>
            <div class="fp-glass fp-table-wrap">
                <table class="fp-table">
                    <thead><tr><th>#</th><th>Equipo</th><th>PJ</th><th>G</th><th>E</th><th>P</th><th>GF</th><th>GC</th><th>DG</th><th>Pts</th></tr></thead>
                    <tbody>
                    <?php foreach ($standings as $i => $s): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><span class="fp-team-badge small"><?= e($s['badge'] ?? 'FP') ?></span> <?= e($s['team_name']) ?> <small><?= e($s['team_city']) ?></small></td>
                            <td><?= (int) $s['played'] ?></td><td><?= (int) $s['won'] ?></td><td><?= (int) $s['drawn'] ?></td><td><?= (int) $s['lost'] ?></td>
                            <td><?= (int) $s['gf'] ?></td><td><?= (int) $s['ga'] ?></td><td><?= ((int) $s['gf']) - ((int) $s['ga']) ?></td>
                            <td style="color:var(--fp-green-400);font-weight:900;"><?= (int) $s['points'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</main>
