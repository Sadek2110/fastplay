<main class="fp-fade fp-page">
    <p class="fp-eyebrow">Admin</p>
    <h1 class="fp-h1">Usuarios</h1>

    <div class="fp-glass" style="border-radius:18px;overflow:hidden;margin-top:24px;">
        <table class="fp-table">
            <thead><tr><th>ID</th><th>Nombre</th><th>Email</th><th>Rol</th><th>Ciudad</th><th>Alta</th><th></th></tr></thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= (int) $u['id'] ?></td>
                        <td><?= e($u['name']) ?></td>
                        <td><?= e($u['email']) ?></td>
                        <td>
                            <form method="post" action="<?= url('admin/setRole/' . (int) $u['id']) ?>" style="margin:0;">
                                <?= csrf_field() ?>
                                <select name="role" class="fp-input" style="padding:6px 10px;font-size:12px;width:auto;" onchange="this.form.submit()">
                                    <?php foreach (['player','admin'] as $r): ?>
                                        <option value="<?= e($r) ?>" <?= $u['role'] === $r ? 'selected' : '' ?>><?= e($r) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                        <td><?= e($u['city'] ?? '—') ?></td>
                        <td style="color:#9ca3af;"><?= e(date('d/m/Y', strtotime($u['created_at']))) ?></td>
                        <td>
                            <?php if ((int) $u['id'] !== (int) current_user()['id']): ?>
                                <form method="post" action="<?= url('admin/deleteUser/' . (int) $u['id']) ?>" style="margin:0;" onsubmit="return confirm('¿Eliminar al usuario <?= e($u['name']) ?>?');">
                                    <?= csrf_field() ?>
                                    <button class="fp-btn fp-btn-ghost" style="padding:6px 12px;font-size:11px;color:#f87171;">Eliminar</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>
