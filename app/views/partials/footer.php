<?php
$cols = [
    ['h' => 'Plataforma', 'items' => [
        ['l' => 'Equipos',  'u' => 'teams'],
        ['l' => 'Partidos', 'u' => 'matches'],
        ['l' => 'Ligas',    'u' => 'leagues'],
        ['l' => 'Campos',   'u' => 'campos'],
    ]],
    ['h' => 'Cuenta', 'items' => [
        ['l' => 'Registrarse',    'u' => 'auth/register'],
        ['l' => 'Iniciar sesión', 'u' => 'auth/login'],
        ['l' => 'Mi panel',       'u' => 'dashboard'],
        ['l' => 'Mi perfil',      'u' => 'profile'],
    ]],
    ['h' => 'Legal', 'items' => [
        ['l' => 'Términos de uso',     'u' => 'legal/terms'],
        ['l' => 'Privacidad (GDPR)',   'u' => 'legal/privacy'],
        ['l' => 'Cookies',             'u' => 'legal/cookies'],
    ]],
];
?>
<footer class="fp-footer">
    <div class="fp-footer-inner">
        <div>
            <a href="<?= url('') ?>" class="fp-logo" style="margin-bottom:16px;font-size:24px;">
                <img src="<?= asset('images/logo.png') ?>" alt="" class="fp-logo-icon">
                <img src="<?= asset('images/logo-nombre.png') ?>" alt="FastPlay" class="fp-logo-word">
            </a>
            <p class="fp-footer-tagline">Fútbol amateur organizado para jugadores, capitanes y equipos de Ceuta.</p>
            <div class="fp-footer-social" aria-label="Redes sociales (próximamente)">
                <?php foreach (['X', 'in', 'ig'] as $s): ?>
                    <span aria-disabled="true" title="Próximamente" class="fp-footer-social-btn"><?= e($s) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php foreach ($cols as $col): ?>
            <div>
                <span class="fp-footer-col-title"><?= e($col['h']) ?></span>
                <ul class="fp-footer-col-links">
                    <?php foreach ($col['items'] as $item): ?>
                        <li><a href="<?= url($item['u']) ?>" class="fp-footer-link"><?= e($item['l']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="fp-footer-bottom">© <?= date('Y') ?> FastPlay — Todos los derechos reservados.</div>
</footer>
