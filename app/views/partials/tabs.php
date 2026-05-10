<?php
// Floating preview tabs (réplica del UI Kit) — útil durante desarrollo
$tabs = [
    ['id' => 'home',      'l' => 'Home',      'url' => ''],
    ['id' => 'leagues',   'l' => 'Ligas',     'url' => 'leagues'],
    ['id' => 'matches',   'l' => 'Partidos',  'url' => 'matches'],
    ['id' => 'teams',     'l' => 'Equipos',   'url' => 'teams'],
    ['id' => 'campos',    'l' => 'Campos',    'url' => 'campos'],
];
if (is_auth()) {
    $tabs[] = ['id' => 'dashboard', 'l' => 'Dashboard', 'url' => 'dashboard'];
    $tabs[] = ['id' => 'chat',      'l' => 'Chat',      'url' => 'chat'];
    $tabs[] = ['id' => 'profile',   'l' => 'Perfil',    'url' => 'profile'];
} else {
    $tabs[] = ['id' => 'login',    'l' => 'Login',    'url' => 'auth/login'];
    $tabs[] = ['id' => 'register', 'l' => 'Registro', 'url' => 'auth/register'];
}
?>
<div class="fp-tabs">
    <?php foreach ($tabs as $t): ?>
        <a href="<?= url($t['url']) ?>" class="fp-tab <?= ($active ?? '') === $t['id'] ? 'active' : '' ?>"><?= e($t['l']) ?></a>
    <?php endforeach; ?>
</div>