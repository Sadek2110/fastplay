<?php
// Banner de consentimiento de cookies frontEnd (rubrica DWEC).
// Las cookies con prefijo fp_client_ son frontEnd y se gestionan en
// public/js/cookie-consent.js. La cookie de sesion PHP (FPSESSID) es
// backEnd, HttpOnly y no se ve afectada por este banner.
?>
<div class="fp-cookie-banner"
     role="region"
     aria-label="Aviso de cookies"
     data-cookie-banner
     hidden>
    <div class="fp-cookie-banner__text">
        FastPlay usa cookies tecnicas (sesion segura) y cookies frontEnd con prefijo
        <code>fp_client_</code> para recordar tus preferencias visuales (tema, ultimo campo,
        vista de calendario). No guardamos datos sensibles ni perfiles publicitarios.
        Mas info en <a href="<?= url('legal/cookies') ?>">politica de cookies</a>.
    </div>
    <div class="fp-cookie-banner__actions">
        <button type="button" class="fp-btn fp-btn-ghost fp-btn-sm" data-cookie-reject>Rechazar</button>
        <button type="button" class="fp-btn fp-btn-primary fp-btn-sm" data-cookie-accept>Aceptar</button>
    </div>
</div>
