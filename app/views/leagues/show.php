<main class="fp-fade fp-page">
    <p class="fp-eyebrow"><?= $league['pro'] ? '🏆 Liga Pro' : '🤝 Liga Amistosa' ?></p>
    <h1 class="fp-h1"><?= e($league['name']) ?></h1>
    <p style="color:#9ca3af;font-size:14px;margin-top:6px;">📍 <?= e($league['city']) ?> · <?= e($league['start']) ?> – <?= e($league['end']) ?> · estado <strong style="color:#fff;text-transform:uppercase;letter-spacing:.06em;font-size:11px;"><?= e($league['status']) ?></strong></p>

    <?php if (!empty($league['prize'])): ?>
        <div style="margin-top:14px;display:inline-block;padding:6px 14px;border-radius:9999px;font-size:13px;color:#fbbf24;background:rgba(245,158,11,.10);border:1px solid rgba(245,158,11,.20);font-weight:600;">
            💰 Premio: <?= number_format((float) $league['prize'], 2, ',', '.') ?> €
        </div>
    <?php endif; ?>

    <section style="margin-top:32px;">
        <h2 class="fp-h2">Clasificación</h2>
        <?php if (empty($standings)): ?>
            <div class="fp-empty">Aún no hay equipos inscritos.</div>
        <?php else: ?>
            <div class="fp-glass" style="border-radius:18px;overflow:hidden;">
                <table class="fp-table">
                    <thead>
                        <tr>
                            <th style="width:40px;">#</th>
                            <th>Equipo</th>
                            <th>PJ</th><th>G</th><th>E</th><th>P</th>
                            <th>GF</th><th>GC</th><th>DG</th><th>Pts</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($standings as $i => $s): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= e($s['badge'] ?? '🛡️') ?> <?= e($s['team_name']) ?> <span style="color:#6b7280;font-size:11px;">· <?= e($s['team_city']) ?></span></td>
                                <td><?= (int) $s['played'] ?></td>
                                <td><?= (int) $s['won'] ?></td>
                                <td><?= (int) $s['drawn'] ?></td>
                                <td><?= (int) $s['lost'] ?></td>
                                <td><?= (int) $s['gf'] ?></td>
                                <td><?= (int) $s['ga'] ?></td>
                                <td><?= ((int) $s['gf']) - ((int) $s['ga']) ?></td>
                                <td style="color:#4ade80;font-weight:900;"><?= (int) $s['points'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>

    <?php if (is_auth() && !empty($myTeams) && $league['status'] === 'open'): ?>
        <section style="margin-top:32px;">
            <h2 class="fp-h2">Inscribir mi equipo</h2>
            <div class="fp-glass" style="border-radius:18px;padding:22px;">
                <form method="post" action="<?= url('leagues/register/' . (int) $league['id']) ?>" style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end;">
                    <?= csrf_field() ?>
                    <label style="flex:1;min-width:240px;">
                        <span class="fp-label">Selecciona uno de tus equipos</span>
                        <select name="team_id" class="fp-input" required>
                            <?php foreach ($myTeams as $t): ?>
                                <option value="<?= (int) $t['id'] ?>"><?= e($t['name']) ?> · <?= e($t['city']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <button class="fp-btn fp-btn-primary fp-btn-glow">Inscribir mi equipo →</button>
                </form>
                <p style="font-size:12px;color:#6b7280;margin-top:12px;">Sólo el capitán puede inscribir un equipo.</p>
            </div>
        </section>
    <?php endif; ?>
</main>