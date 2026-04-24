<?php $pageTitle = 'Admin'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<main class="pt-24 pb-20 px-6 max-w-7xl mx-auto">
    <div class="mb-10 fade-up">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 text-xs font-semibold mb-4">
            ⚙️ Panel de administración
        </div>
        <h1 class="text-3xl font-black">Dashboard Admin</h1>
    </div>

    <!-- Global stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-10 fade-up-1">
        <?php
        $kpis = [
            ['label'=>'Usuarios',  'val'=>$stats['users']??0,   'icon'=>'👤', 'color'=>'text-blue-400'],
            ['label'=>'Jugadores', 'val'=>$stats['players']??0, 'icon'=>'⚽', 'color'=>'text-green-400'],
            ['label'=>'Capitanes', 'val'=>$stats['captains']??0,'icon'=>'⭐', 'color'=>'text-yellow-400'],
            ['label'=>'Equipos',   'val'=>$stats['teams']??0,   'icon'=>'🛡️', 'color'=>'text-purple-400'],
            ['label'=>'Ligas',     'val'=>$stats['leagues']??0, 'icon'=>'🏆', 'color'=>'text-orange-400'],
            ['label'=>'Partidos',  'val'=>$stats['matches']??0, 'icon'=>'🏟️', 'color'=>'text-red-400'],
        ];
        foreach($kpis as $k): ?>
        <div class="glass rounded-2xl p-5 text-center">
            <div class="text-2xl mb-2"><?= $k['icon'] ?></div>
            <div class="text-2xl font-black <?= $k['color'] ?>"><?= $k['val'] ?></div>
            <div class="text-xs text-gray-600 mt-1"><?= $k['label'] ?></div>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Quick access -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 fade-up-2">
        <?php
        $sections = [
            ['href'=>'/admin/users',  'icon'=>'👤','label'=>'Usuarios',  'desc'=>'Gestionar cuentas y bans'],
            ['href'=>'/admin/teams',  'icon'=>'🛡️','label'=>'Equipos',   'desc'=>'Ver equipos y sanciones'],
            ['href'=>'/admin/leagues','icon'=>'🏆','label'=>'Ligas',     'desc'=>'Crear y moderar ligas'],
            ['href'=>'/admin/fields', 'icon'=>'🏟️','label'=>'Campos',    'desc'=>'Certificar instalaciones'],
        ];
        foreach($sections as $s): ?>
        <a href="<?= APP_URL . $s['href'] ?>"
           class="glass rounded-2xl p-6 hover:bg-white/[.07] hover:border-yellow-500/20 transition-all duration-200 group">
            <div class="text-3xl mb-3"><?= $s['icon'] ?></div>
            <div class="font-black mb-1"><?= $s['label'] ?></div>
            <div class="text-xs text-gray-500"><?= $s['desc'] ?></div>
        </a>
        <?php endforeach; ?>
    </div>
</main>
<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
