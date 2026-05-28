/**
 * FastPlay · Cambio de tema claro/oscuro
 * --------------------------------------
 * Persiste la preferencia en localStorage para que sobreviva entre
 * recargas. La landing fuerza tema oscuro porque el diseno asi lo
 * exige (gradientes y fotos optimizadas para fondo negro).
 */
(function () {
    'use strict';

    const root = document.documentElement;

    if (root.classList.contains('fp-landing-page')) {
        root.setAttribute('data-theme', 'dark');
        return;
    }

    /**
     * Recupera el tema persistido o devuelve oscuro por defecto.
     *
     * @returns {string}
     */
    function readStoredTheme() {
        try {
            return localStorage.getItem('theme') || 'dark';
        } catch (_e) {
            return 'dark';
        }
    }

    root.setAttribute('data-theme', readStoredTheme());

    /**
     * Refresca el icono del boton de tema.
     *
     * @returns {void}
     */
    function syncIcon() {
        const btn = document.querySelector('[data-theme-toggle]');
        if (!btn) return;
        const icon = btn.querySelector('i');
        if (!icon) return;
        icon.className = root.getAttribute('data-theme') === 'light' ? 'bi bi-moon' : 'bi bi-sun';
    }

    document.addEventListener('click', function (event) {
        const btn = event.target.closest('[data-theme-toggle]');
        if (!btn) return;
        const next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
        root.setAttribute('data-theme', next);
        try { localStorage.setItem('theme', next); } catch (_e) { /* sandbox / quota */ }
        syncIcon();
    });

    document.addEventListener('DOMContentLoaded', syncIcon);
})();
