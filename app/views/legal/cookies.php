<main class="fp-fade" style="max-width:720px;margin:0 auto;padding:120px 24px 96px;">
    <p class="fp-eyebrow">Legal</p>
    <h1 style="font-size:42px;font-weight:900;margin:0 0 24px;letter-spacing:-.02em;">Política de <span class="fp-gradient-text">cookies</span></h1>
    <div style="color:var(--fp-fg);font-size:15px;line-height:1.75;">
        <p>FastPlay utiliza cookies únicamente con fines técnicos esenciales para el funcionamiento de la plataforma.</p>
        <h2 style="font-size:20px;font-weight:700;color:var(--fp-fg);margin:32px 0 12px;">1. ¿Qué son las cookies?</h2>
        <p>Una cookie es un pequeño archivo de texto que un sitio web almacena en tu navegador cuando lo visitas. Las cookies permiten que el sitio recuerde información sobre tu visita.</p>
        <h2 style="font-size:20px;font-weight:700;color:var(--fp-fg);margin:32px 0 12px;">2. Cookies que utilizamos</h2>
        <table style="width:100%;border-collapse:collapse;margin:16px 0;">
            <thead>
                <tr style="border-bottom:1px solid var(--fp-glass-border);text-align:left;color:var(--fp-fg);">
                    <th style="padding:10px 8px;font-size:13px;">Cookie</th>
                    <th style="padding:10px 8px;font-size:13px;">Finalidad</th>
                    <th style="padding:10px 8px;font-size:13px;">Duración</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom:1px solid var(--fp-glass-border);">
                    <td style="padding:12px 8px;font-size:14px;"><strong>FPSESSID</strong> <em>(backEnd)</em></td>
                    <td style="padding:12px 8px;font-size:14px;">Cookie técnica de sesión PHP — <code>HttpOnly</code>, <code>SameSite=Lax</code>, <code>Secure</code> en HTTPS. Imprescindible para la autenticación.</td>
                    <td style="padding:12px 8px;font-size:14px;">Sesión</td>
                </tr>
                <tr style="border-bottom:1px solid var(--fp-glass-border);">
                    <td style="padding:12px 8px;font-size:14px;"><strong>_csrf</strong> <em>(backEnd)</em></td>
                    <td style="padding:12px 8px;font-size:14px;">Token interno (en sesión PHP) contra falsificación de peticiones.</td>
                    <td style="padding:12px 8px;font-size:14px;">Sesión</td>
                </tr>
                <tr style="border-bottom:1px solid var(--fp-glass-border);">
                    <td style="padding:12px 8px;font-size:14px;"><strong>fp_client_cookie_consent</strong> <em>(frontEnd)</em></td>
                    <td style="padding:12px 8px;font-size:14px;">Registra tu respuesta al banner de cookies (aceptar/rechazar). Sin esta cookie no podemos recordar tu decisión.</td>
                    <td style="padding:12px 8px;font-size:14px;">180 días</td>
                </tr>
                <tr style="border-bottom:1px solid var(--fp-glass-border);">
                    <td style="padding:12px 8px;font-size:14px;"><strong>fp_client_theme</strong> <em>(frontEnd)</em></td>
                    <td style="padding:12px 8px;font-size:14px;">Recuerda el tema visual elegido (claro/oscuro).</td>
                    <td style="padding:12px 8px;font-size:14px;">180 días</td>
                </tr>
                <tr style="border-bottom:1px solid var(--fp-glass-border);">
                    <td style="padding:12px 8px;font-size:14px;"><strong>fp_client_last_field</strong>, <strong>fp_client_calendar_view</strong>, <strong>fp_client_last_team_filter</strong> <em>(frontEnd)</em></td>
                    <td style="padding:12px 8px;font-size:14px;">Memorizan últimas vistas y filtros visuales. Nunca contienen datos sensibles, emails ni tokens.</td>
                    <td style="padding:12px 8px;font-size:14px;">180 días</td>
                </tr>
            </tbody>
        </table>
        <p style="margin-top:8px;font-size:13px;color:var(--fp-fg-subtle);">
            <strong>FrontEnd vs BackEnd:</strong> las cookies <code>fp_client_*</code> las gestiona el navegador desde
            <code>public/js/cookie-consent.js</code>, son accesibles por JavaScript y se borran al rechazar el banner.
            En cambio <code>FPSESSID</code> y <code>_csrf</code> son cookies del servidor PHP (sesión segura), no
            accesibles por JavaScript (<code>HttpOnly</code>) y nunca se ven afectadas por el banner.
        </p>
        <h2 style="font-size:20px;font-weight:700;color:var(--fp-fg);margin:32px 0 12px;">3. Cookies de terceros</h2>
        <p>No utilizamos cookies de terceros para publicidad, analítica ni redes sociales. Las fuentes de Google Fonts se cargan sin cookies.</p>
        <h2 style="font-size:20px;font-weight:700;color:var(--fp-fg);margin:32px 0 12px;">4. Cómo gestionar las cookies</h2>
        <p>Puedes configurar tu navegador para bloquear todas las cookies. Sin embargo, dado que utilizamos cookies técnicas esenciales, es posible que algunas funciones de la plataforma dejen de funcionar correctamente si las desactivas.</p>
        <p style="margin-top:32px;color:var(--fp-fg-subtle);font-size:13px;">Última actualización: <?= e($lastUpdated) ?></p>
    </div>
    <a href="<?= url('') ?>" class="fp-btn fp-btn-primary fp-btn-glow" style="display:inline-flex;margin-top:32px;padding:14px 28px;font-size:15px;">Volver al inicio →</a>
</main>
