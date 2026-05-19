<h1>Nueva solicitud de partido</h1>
<p><?= e($request['requesting_team_name'] ?? 'Un equipo') ?> quiere jugar contra <?= e($request['requested_team_name'] ?? 'tu equipo') ?>.</p>
<p><a href="<?= e($url ?? '') ?>">Responder solicitud</a></p>
<p>FastPlay</p>
