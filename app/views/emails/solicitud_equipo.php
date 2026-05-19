<h1>Solicitud para unirse a tu equipo</h1>
<p><?= e($request['user_name'] ?? 'Un usuario') ?> quiere unirse a <?= e($request['team_name'] ?? 'tu equipo') ?>.</p>
<p><a href="<?= e($url ?? '') ?>">Revisar solicitud</a></p>
<p>FastPlay</p>
