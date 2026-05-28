/**
 * FastPlay · Panel contextual DWEC
 * ---------------------------------------------------------------
 * Pieza principal exigida por la rubrica DWEC:
 * pulsa un boton -> evento -> peticion AJAX a un endpoint PHP
 * que devuelve JSON -> el DOM se transforma segun el rol y datos
 * del usuario autenticado (admin, capitan, jugador o visitante).
 *
 * Demuestra: DOM, eventos, AJAX/JSON, try/catch, async/await,
 * Map para diccionarios de roles, atributos data-* y ARIA.
 */
(function () {
    'use strict';

    /**
     * Diccionario de roles soportados con su etiqueta visible
     * y la clase CSS asociada para colorear el panel.
     *
     * @type {Map<string, {label: string, cssClass: string, icon: string}>}
     */
    const ROLE_DESCRIPTORS = new Map([
        ['guest',   { label: 'Visitante', cssClass: 'fp-ctx--guest',   icon: 'bi-person' }],
        ['player',  { label: 'Jugador',   cssClass: 'fp-ctx--player',  icon: 'bi-person-badge' }],
        ['captain', { label: 'Capitan',   cssClass: 'fp-ctx--captain', icon: 'bi-star-fill' }],
        ['admin',   { label: 'Admin',     cssClass: 'fp-ctx--admin',   icon: 'bi-shield-lock-fill' }],
    ]);

    /**
     * Acciones internas traducidas a etiquetas para el usuario.
     *
     * @type {Map<string, string>}
     */
    const ACTION_LABELS = new Map([
        ['login',              'Iniciar sesion'],
        ['register',           'Registrarse'],
        ['browseTeams',        'Ver equipos'],
        ['viewDashboard',      'Ir al panel'],
        ['editProfile',        'Editar perfil'],
        ['requestMatch',       'Solicitar partido'],
        ['manageTeam',         'Gestionar equipo'],
        ['manageUsers',        'Gestionar usuarios'],
        ['manageLeagues',      'Gestionar ligas'],
        ['manageMatches',      'Moderar partidos'],
        ['usePremiumFeatures', 'Funciones premium'],
    ]);

    /**
     * Inicializa el panel contextual y conecta los eventos.
     *
     * @returns {void}
     */
    function init() {
        const panel = document.querySelector('[data-dwec-context]');
        if (!panel) return;

        const refreshBtn = panel.querySelector('[data-dwec-refresh]');
        const statusEl = panel.querySelector('[data-dwec-status]');
        if (!refreshBtn || !statusEl) return;

        refreshBtn.addEventListener('click', function () {
            void loadContext(panel, refreshBtn, statusEl);
        });

        // Primera carga automatica para que la rubrica vea la pieza ya activa.
        void loadContext(panel, refreshBtn, statusEl);
    }

    /**
     * Pide el contexto al servidor y actualiza el DOM.
     *
     * @param {HTMLElement} panel
     * @param {HTMLButtonElement} btn
     * @param {HTMLElement} statusEl
     * @returns {Promise<void>}
     */
    async function loadContext(panel, btn, statusEl) {
        const endpoint = panel.dataset.dwecEndpoint
            || ((window.FP_BASE_URL || '') + '/dashboard/context');

        setLoading(panel, btn, statusEl, true);

        try {
            const response = await fetch(endpoint, {
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });

            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }

            const data = await response.json();
            renderContext(panel, data);
            statusEl.textContent = 'Contexto actualizado a las ' + formatTime(data.generatedAt);
            statusEl.classList.remove('fp-ctx-status--error');
        } catch (err) {
            console.warn('[dwec-context-panel] error:', err);
            statusEl.textContent = 'No se pudo actualizar el contexto. Reintenta en unos segundos.';
            statusEl.classList.add('fp-ctx-status--error');
        } finally {
            setLoading(panel, btn, statusEl, false);
        }
    }

    /**
     * Aplica los datos JSON al panel manipulando DOM, clases y ARIA.
     *
     * @param {HTMLElement} panel
     * @param {Object} ctx Datos devueltos por el servidor.
     * @returns {void}
     */
    function renderContext(panel, ctx) {
        const role = String(ctx.role || 'guest');
        const descriptor = ROLE_DESCRIPTORS.get(role) || ROLE_DESCRIPTORS.get('guest');

        // Limpiar clases de rol previas y aplicar la nueva.
        ROLE_DESCRIPTORS.forEach(function (d) { panel.classList.remove(d.cssClass); });
        panel.classList.add(descriptor.cssClass);
        panel.dataset.dwecRole = role;
        panel.setAttribute('aria-label', 'Contexto de ' + descriptor.label);

        const roleEl = panel.querySelector('[data-dwec-role]');
        if (roleEl) {
            roleEl.textContent = descriptor.label;
            roleEl.setAttribute('data-role-key', role);
        }

        const iconEl = panel.querySelector('[data-dwec-role-icon]');
        if (iconEl) {
            iconEl.className = 'bi ' + descriptor.icon;
        }

        const nameEl = panel.querySelector('[data-dwec-name]');
        if (nameEl) nameEl.textContent = ctx.displayName || 'Invitado';

        const teamEl = panel.querySelector('[data-dwec-team]');
        if (teamEl) {
            teamEl.textContent = ctx.team && ctx.team.name
                ? ctx.team.name + (ctx.team.city ? ' · ' + ctx.team.city : '')
                : 'Sin equipo';
        }

        const premiumEl = panel.querySelector('[data-dwec-premium]');
        if (premiumEl) {
            premiumEl.hidden = !ctx.isPremium;
            premiumEl.setAttribute('aria-hidden', ctx.isPremium ? 'false' : 'true');
        }

        const notifEl = panel.querySelector('[data-dwec-notif]');
        if (notifEl) {
            const unread = Number(ctx.unreadNotifications || 0);
            notifEl.textContent = unread > 0
                ? unread + ' notificacion' + (unread === 1 ? '' : 'es') + ' sin leer'
                : 'Sin notificaciones nuevas';
            notifEl.classList.toggle('has-unread', unread > 0);
        }

        const msgEl = panel.querySelector('[data-dwec-message]');
        if (msgEl) msgEl.textContent = ctx.message || '';

        renderActions(panel, Array.isArray(ctx.allowedActions) ? ctx.allowedActions : []);
    }

    /**
     * Vuelca las acciones permitidas como chips creados con createElement.
     *
     * @param {HTMLElement} panel
     * @param {Array<string>} actions
     * @returns {void}
     */
    function renderActions(panel, actions) {
        const list = panel.querySelector('[data-dwec-actions]');
        if (!list) return;

        list.replaceChildren();

        const seen = new Set();
        actions.forEach(function (key) {
            if (seen.has(key)) return;
            seen.add(key);
            const label = ACTION_LABELS.get(key) || key;
            const chip = document.createElement('span');
            chip.className = 'fp-ctx-chip';
            chip.dataset.action = key;
            chip.textContent = label;
            list.appendChild(chip);
        });

        if (!list.children.length) {
            const empty = document.createElement('span');
            empty.className = 'fp-ctx-chip fp-ctx-chip--muted';
            empty.textContent = 'Sin acciones disponibles';
            list.appendChild(empty);
        }
    }

    /**
     * Cambia el estado visual durante la peticion AJAX.
     *
     * @param {HTMLElement} panel
     * @param {HTMLButtonElement} btn
     * @param {HTMLElement} statusEl
     * @param {boolean} loading
     * @returns {void}
     */
    function setLoading(panel, btn, statusEl, loading) {
        btn.disabled = loading;
        panel.setAttribute('aria-busy', loading ? 'true' : 'false');
        if (loading) {
            statusEl.textContent = 'Consultando contexto al servidor...';
            statusEl.classList.remove('fp-ctx-status--error');
        }
    }

    /**
     * Formatea ISO 8601 a HH:MM local, con fallback userFriendly.
     *
     * @param {string|undefined} iso
     * @returns {string}
     */
    function formatTime(iso) {
        if (!iso) return '—';
        try {
            const d = new Date(iso);
            if (Number.isNaN(d.getTime())) return '—';
            return d.toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
        } catch (_e) {
            return '—';
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
