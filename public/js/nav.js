/**
 * FastPlay · Navegacion lateral y contador de notificaciones
 * ----------------------------------------------------------
 * - Abre/cierra el sidebar en movil.
 * - Recupera periodicamente el contador de notificaciones sin leer
 *   atacando /notification/unreadCount con fetch() + try/catch.
 * - Usa window.FP_BASE_URL para construir las rutas (definido en el
 *   layout principal) en vez de adivinar el prefijo.
 */
(function () {
    'use strict';

    const NOTIF_POLL_MS = 60000;

    /**
     * Resuelve una URL relativa al prefijo de instalacion (FP_BASE_URL).
     *
     * @param {string} path
     * @returns {string}
     */
    function fpUrl(path) {
        const base = (typeof window.FP_BASE_URL === 'string') ? window.FP_BASE_URL : '';
        return base.replace(/\/$/, '') + '/' + String(path || '').replace(/^\//, '');
    }

    /**
     * Configura el sidebar movil (toggle + cierre fuera).
     *
     * @returns {void}
     */
    function setupSidebar() {
        const sidebar = document.getElementById('fpSidebar');
        const toggle = document.querySelector('[data-nav-toggle]');
        if (!sidebar || !toggle) return;

        toggle.addEventListener('click', function (e) {
            e.stopPropagation();
            const open = sidebar.classList.toggle('open');
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        });

        sidebar.querySelectorAll('a, button[type="submit"]').forEach(function (el) {
            el.addEventListener('click', function () {
                if (window.innerWidth < 1024) {
                    sidebar.classList.remove('open');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            });
        });

        document.addEventListener('click', function (e) {
            if (window.innerWidth < 1024 && sidebar.classList.contains('open')) {
                if (!sidebar.contains(e.target)) {
                    sidebar.classList.remove('open');
                    toggle.setAttribute('aria-expanded', 'false');
                }
            }
        });
    }

    /**
     * Consulta el contador de notificaciones via fetch.
     *
     * @param {HTMLElement} badge
     * @returns {Promise<void>}
     */
    async function refreshNotifications(badge) {
        try {
            const response = await fetch(fpUrl('notification/unreadCount'), {
                credentials: 'same-origin',
                headers: { 'Accept': 'application/json' },
            });
            if (!response.ok) throw new Error('HTTP ' + response.status);
            const data = await response.json();
            const count = Number(data && data.count);
            if (Number.isNaN(count)) return;
            badge.textContent = String(count);
            badge.hidden = count <= 0;
        } catch (err) {
            // Mantenemos UX silenciosa: si falla, no rompemos la pagina.
            console.warn('[nav] no se pudo refrescar notificaciones:', err);
        }
    }

    /**
     * Engancha el polling de notificaciones si el badge esta en la pagina.
     *
     * @returns {void}
     */
    function setupNotifications() {
        const badge = document.querySelector('[data-notification-badge]');
        if (!badge) return;
        void refreshNotifications(badge);
        setInterval(function () { void refreshNotifications(badge); }, NOTIF_POLL_MS);
    }

    document.addEventListener('DOMContentLoaded', function () {
        setupSidebar();
        setupNotifications();
    });
})();
