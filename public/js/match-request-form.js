/**
 * FastPlay · Filtro y validacion del formulario de solicitud de partido
 * --------------------------------------------------------------------
 * Sustituye el bloque inline anterior por un modulo externo con
 * delegacion de eventos, JSDoc, contadores y validacion previa al
 * envio. Marca opciones con data-* en vez de manipular HTML.
 */
(function () {
    'use strict';

    /**
     * Engancha la busqueda, el contador y la validacion del formulario.
     *
     * @returns {void}
     */
    function init() {
        const form = document.querySelector('[data-match-request-form]');
        if (!form) return;

        const search = form.querySelector('[data-team-search]');
        const cards = Array.from(form.querySelectorAll('[data-team-card]'));
        const counter = form.querySelector('[data-team-count]');

        if (search) {
            search.addEventListener('input', function (event) {
                applyFilter(event.target.value, cards, counter);
            });
        }
        updateCounter(cards, counter);

        form.addEventListener('submit', function (event) {
            const checked = form.querySelector('input[name="requested_team_id"]:checked');
            if (!checked) {
                event.preventDefault();
                if (search) search.focus();
                announce(form, 'Selecciona un equipo rival antes de enviar la solicitud.');
            }
        });
    }

    /**
     * Filtra las tarjetas segun la cadena de busqueda introducida.
     *
     * @param {string} query
     * @param {Array<HTMLElement>} cards
     * @param {HTMLElement|null} counter
     * @returns {void}
     */
    function applyFilter(query, cards, counter) {
        const normalized = String(query || '').toLowerCase().trim();
        cards.forEach(function (card) {
            const haystack = card.getAttribute('data-team-card') || '';
            card.hidden = normalized !== '' && haystack.indexOf(normalized) === -1;
        });
        updateCounter(cards, counter);
    }

    /**
     * Actualiza el contador de equipos visibles.
     *
     * @param {Array<HTMLElement>} cards
     * @param {HTMLElement|null} counter
     * @returns {void}
     */
    function updateCounter(cards, counter) {
        if (!counter) return;
        const visible = cards.filter(function (c) { return !c.hidden; }).length;
        counter.textContent = visible + ' equipo' + (visible === 1 ? '' : 's') + ' visible' + (visible === 1 ? '' : 's');
    }

    /**
     * Muestra un mensaje de aviso accesible cerca del campo de busqueda.
     *
     * @param {HTMLElement} form
     * @param {string} message
     * @returns {void}
     */
    function announce(form, message) {
        let alert = form.querySelector('[data-match-request-alert]');
        if (!alert) {
            alert = document.createElement('p');
            alert.setAttribute('data-match-request-alert', '');
            alert.setAttribute('role', 'alert');
            alert.className = 'fp-field-error';
            form.prepend(alert);
        }
        alert.textContent = message;
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
