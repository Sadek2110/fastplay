/**
 * FastPlay · Detalle del equipo (plantilla y campo)
 * --------------------------------------------------
 * Sustituye los onclick inline por delegacion de eventos sobre los
 * botones del roster y del campo, y construye la tarjeta del jugador
 * seleccionado con createElement (sin innerHTML) para evitar XSS.
 *
 * Demuestra: DOM, eventos, dataset, desestructuracion y plantillas
 * DOM seguras a partir de un objeto JSON inyectado por PHP.
 */
(function () {
    'use strict';

    /**
     * Punto de entrada: lee los miembros embebidos y conecta los listeners.
     *
     * @returns {void}
     */
    function init() {
        const layout = document.querySelector('[data-team-detail]');
        if (!layout) return;

        const members = Array.isArray(window.fpTeamMembers) ? window.fpTeamMembers : [];
        const detail = layout.querySelector('[data-player-detail]');
        const detailInner = layout.querySelector('[data-player-detail-inner]');
        const closeBtn = layout.querySelector('[data-player-detail-close]');

        if (!detail || !detailInner) return;

        layout.addEventListener('click', function (event) {
            const trigger = event.target.closest('[data-player-idx]');
            if (!trigger) return;
            const idx = parseInt(trigger.dataset.playerIdx, 10);
            if (Number.isNaN(idx)) return;
            selectPlayer(members[idx], idx, layout, detail, detailInner);
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                closeDetail(layout, detail);
            });
        }
    }

    /**
     * Resalta al jugador seleccionado y pinta su tarjeta.
     *
     * @param {Object|undefined} player
     * @param {number} idx
     * @param {HTMLElement} layout
     * @param {HTMLElement} detail
     * @param {HTMLElement} detailInner
     * @returns {void}
     */
    function selectPlayer(player, idx, layout, detail, detailInner) {
        if (!player) return;

        layout.querySelectorAll('.fp-roster-item, .fp-pitch-player').forEach(function (el) {
            const elIdx = parseInt(el.dataset.playerIdx, 10);
            el.classList.toggle('selected', elIdx === idx);
        });

        const card = buildPlayerCard(player);
        detailInner.replaceChildren(card);
        detail.hidden = false;
        detail.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    /**
     * Cierra el panel de detalle y limpia la seleccion.
     *
     * @param {HTMLElement} layout
     * @param {HTMLElement} detail
     * @returns {void}
     */
    function closeDetail(layout, detail) {
        detail.hidden = true;
        layout.querySelectorAll('.fp-roster-item, .fp-pitch-player').forEach(function (el) {
            el.classList.remove('selected');
        });
    }

    /**
     * Crea la mini-card del jugador a partir del objeto JSON.
     *
     * @param {Object} player
     * @returns {HTMLElement}
     */
    function buildPlayerCard(player) {
        const {
            name = '?',
            avatar = '',
            position = 'N/D',
            city = 'N/D',
            dorsal = null,
            is_captain: isCaptainRaw = 0,
        } = player;

        const isCaptain = parseInt(isCaptainRaw, 10) === 1;
        const dorsalLabel = (dorsal !== null && dorsal !== undefined)
            ? String(dorsal).padStart(2, '0')
            : 'N/D';

        const wrap = document.createElement('div');
        wrap.className = 'fp-player-card-mini';

        // Avatar
        const avatarWrap = document.createElement('div');
        avatarWrap.className = 'fp-player-card-avatar';
        if (avatar) {
            const img = document.createElement('img');
            img.src = (window.FP_BASE_URL || '') + '/' + avatar;
            img.alt = name;
            avatarWrap.appendChild(img);
        } else {
            const initial = document.createElement('span');
            initial.className = 'fp-player-card-initial';
            initial.textContent = (name || '?').charAt(0).toUpperCase();
            avatarWrap.appendChild(initial);
        }

        // Info
        const info = document.createElement('div');
        info.className = 'fp-player-card-info';

        const heading = document.createElement('h3');
        heading.textContent = name;
        if (isCaptain) {
            const cap = document.createElement('span');
            cap.className = 'fp-gold-text';
            cap.textContent = ' ⭐ Capitán';
            heading.appendChild(cap);
        }

        const stats = document.createElement('div');
        stats.className = 'fp-player-card-stats';
        stats.append(
            buildStat('bi-person-badge', position),
            buildStat('bi-hash', dorsalLabel),
            buildStat('bi-geo-alt', city),
        );

        info.append(heading, stats);
        wrap.append(avatarWrap, info);
        return wrap;
    }

    /**
     * Crea un fragmento con icono + texto para una estadistica.
     *
     * @param {string} iconClass
     * @param {string} text
     * @returns {HTMLElement}
     */
    function buildStat(iconClass, text) {
        const span = document.createElement('span');
        const icon = document.createElement('i');
        icon.className = 'bi ' + iconClass;
        span.append(icon, document.createTextNode(' ' + text));
        return span;
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
