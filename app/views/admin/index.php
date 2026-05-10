<main class="fp-fade fp-page">
    <p class="fp-eyebrow">Panel de administración</p>
    <h1 class="fp-h1">Admin <span class="fp-gradient-text">FastPlay</span></h1>

    <div class="fp-grid-4" style="margin-top:24px;">
        <?php
        $tiles = [
            ['k' => 'users',   'i' => '👤', 'l' => 'Usuarios', 'u' => url('admin/users')],
            ['k' => 'teams',   'i' => '🛡️', 'l' => 'Equipos',  'u' => url('admin/teams')],
            ['k' => 'leagues', 'i' => '🏆', 'l' => 'Ligas',    'u' => url('admin/leagues')],
            ['k' => 'matches', 'i' => '⚽', 'l' => 'Partidos', 'u' => url('matches')],
            ['k' => 'fields',  'i' => '🏟️', 'l' => 'Campos',   'u' => url('admin/fields')],
            ['k' => 'rooms',   'i' => '💬', 'l' => 'Salas',    'u' => url('chat')],
        ];
        foreach ($tiles as $t): ?>
            <a href="<?= e($t['u']) ?>" class="fp-glass fp-card-link" style="border-radius:18px;padding:22px;text-decoration:none;color:#fff;display:block;">
                <div style="font-size:30px;"><?= e($t['i']) ?></div>
                <div style="font-size:30px;font-weight:900;margin-top:8px;letter-spacing:-.02em;"><?= (int) ($counts[$t['k']] ?? 0) ?></div>
                <div style="font-size:11px;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;margin-top:6px;font-weight:600;"><?= e($t['l']) ?></div>
            </a>
        <?php endforeach; ?>
    </div>

    <section style="margin-top:32px;">
        <h2 class="fp-h2">Últimos intentos de login</h2>
        <div class="fp-glass" style="border-radius:18px;overflow:hidden;">
            <table class="fp-table">
                <thead><tr><th>Email</th><th>Resultado</th><th>Cuándo</th></tr></thead>
                <tbody>
                    <?php if (empty($recent)): ?>
                        <tr><td colspan="3" style="color:#6b7280;">Sin actividad registrada.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($recent as $r): ?>
                        <tr>
                            <td><?= e($r['email']) ?></td>
                            <td>
                                <span class="fp-status fp-status-<?= ((int) $r['success']) ? 'confirmed' : 'cancelled' ?>">
                                    <?= ((int) $r['success']) ? '✓ ok' : '✕ falló' ?>
                                </span>
                            </td>
                            <td style="color:#9ca3af;"><?= e($r['attempted_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>
