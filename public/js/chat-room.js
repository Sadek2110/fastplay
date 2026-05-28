/**
 * FastPlay · Chat AJAX
 * --------------------
 * Envia el formulario por fetch contra /chat/send/{id}, recarga los
 * mensajes via /chat/messages/{id} (JSON) y vuelca el resultado en el
 * DOM creando nodos con createElement/textContent (sin innerHTML).
 *
 * Demuestra: DOM, eventos, AJAX/JSON, async/await, try/catch,
 * desestructuracion y plantillas con createElement seguras.
 */
(function () {
    'use strict';

    /** Intervalo en ms para refrescar mensajes nuevos. */
    const POLL_INTERVAL_MS = 8000;

    /**
     * Entrada principal: enlaza eventos y arranca el polling.
     *
     * @returns {void}
     */
    function init() {
        const panel = document.querySelector('[data-chat-room]');
        if (!panel) return;

        const feed = panel.querySelector('[data-chat-feed]');
        const form = panel.querySelector('[data-chat-form]');
        if (!feed || !form) return;

        feed.scrollTop = feed.scrollHeight;

        form.addEventListener('submit', function (event) {
            event.preventDefault();
            void sendMessage(form, feed);
        });

        // Refresco periodico para ver lo que escriben los demas.
        setInterval(function () { void refreshFeed(feed); }, POLL_INTERVAL_MS);
    }

    /**
     * Envia el mensaje via fetch y refresca el feed.
     *
     * @param {HTMLFormElement} form
     * @param {HTMLElement} feed
     * @returns {Promise<void>}
     */
    async function sendMessage(form, feed) {
        const input = form.querySelector('input[name="body"]');
        const button = form.querySelector('button[type="submit"], button:not([type])');
        if (!input || input.value.trim() === '') return;

        const body = input.value.trim();
        if (body.length > 800) {
            showStatus(form, 'Maximo 800 caracteres.', true);
            return;
        }

        if (button) button.disabled = true;

        try {
            const payload = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: payload,
                credentials: 'same-origin',
                headers: { 'Accept': 'text/html, application/json' },
            });
            if (!response.ok) throw new Error('HTTP ' + response.status);
            input.value = '';
            await refreshFeed(feed);
            showStatus(form, '', false);
        } catch (err) {
            console.warn('[chat-room] envio fallido:', err);
            showStatus(form, 'No se pudo enviar el mensaje. Intentalo de nuevo.', true);
        } finally {
            if (button) button.disabled = false;
            input.focus();
        }
    }

    /**
     * Pide los mensajes al endpoint JSON y los pinta en el feed.
     *
     * @param {HTMLElement} feed
     * @returns {Promise<void>}
     */
    async function refreshFeed(feed) {
        const endpoint = feed.dataset.chatMessages;
        if (!endpoint) return;

        try {
            const response = await fetch(endpoint, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin',
            });
            if (!response.ok) throw new Error('HTTP ' + response.status);
            const messages = await response.json();
            if (!Array.isArray(messages)) return;
            renderFeed(feed, messages);
        } catch (err) {
            console.warn('[chat-room] no se pudieron cargar mensajes:', err);
        }
    }

    /**
     * Vuelca la lista de mensajes en el DOM sin usar innerHTML.
     *
     * @param {HTMLElement} feed
     * @param {Array<Object>} messages
     * @returns {void}
     */
    function renderFeed(feed, messages) {
        if (messages.length === 0) {
            feed.replaceChildren(buildEmptyState());
            return;
        }

        const nearBottom = (feed.scrollHeight - feed.scrollTop - feed.clientHeight) < 80;
        const frag = document.createDocumentFragment();

        const baseUrl = (typeof window.FP_BASE_URL === 'string') ? window.FP_BASE_URL : '';
        const csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';

        messages.forEach(function (m) {
            const {
                id = 0,
                user_name: userName = '',
                body = '',
                created_at: createdAt = '',
                own = false,
                canDelete = false,
            } = m;

            const msg = document.createElement('div');
            msg.className = 'fp-msg' + (own ? ' own' : '');
            msg.setAttribute('data-msg-id', String(id));

            const meta = document.createElement('div');
            meta.className = 'fp-msg-meta';
            const who = document.createElement('strong');
            who.textContent = userName;
            const when = document.createElement('span');
            when.textContent = createdAt;
            meta.append(who, when);

            if (canDelete && id > 0) {
                const form = document.createElement('form');
                form.method = 'post';
                form.action = baseUrl.replace(/\/$/, '') + '/chat/deleteMessage/' + id;
                form.className = 'fp-msg-del-form';
                form.addEventListener('submit', function (event) {
                    if (!window.confirm('¿Eliminar este mensaje?')) {
                        event.preventDefault();
                    }
                });
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_csrf';
                csrf.value = csrfToken;
                const btn = document.createElement('button');
                btn.type = 'submit';
                btn.className = 'fp-msg-del';
                btn.title = 'Borrar mensaje';
                btn.setAttribute('aria-label', 'Borrar mensaje');
                const icon = document.createElement('i');
                icon.className = 'bi bi-trash';
                btn.appendChild(icon);
                form.append(csrf, btn);
                meta.appendChild(form);
            }

            const bodyEl = document.createElement('div');
            bodyEl.className = 'fp-msg-body';
            bodyEl.textContent = body;

            msg.append(meta, bodyEl);
            frag.appendChild(msg);
        });

        feed.replaceChildren(frag);
        if (nearBottom) feed.scrollTop = feed.scrollHeight;
    }

    /**
     * Construye el placeholder cuando no hay mensajes.
     *
     * @returns {HTMLElement}
     */
    function buildEmptyState() {
        const wrap = document.createElement('div');
        wrap.className = 'fp-empty';
        const icon = document.createElement('i');
        icon.className = 'bi bi-chat-dots';
        const title = document.createElement('strong');
        title.textContent = 'Sin mensajes';
        const desc = document.createElement('small');
        desc.textContent = 'Se el primero en escribir.';
        wrap.append(icon, title, desc);
        return wrap;
    }

    /**
     * Muestra un mensaje de estado userFriendly bajo el formulario.
     *
     * @param {HTMLFormElement} form
     * @param {string} message
     * @param {boolean} isError
     * @returns {void}
     */
    function showStatus(form, message, isError) {
        let status = form.querySelector('[data-chat-status]');
        if (!status) {
            status = document.createElement('small');
            status.setAttribute('data-chat-status', '');
            status.className = 'fp-ctx-status';
            form.appendChild(status);
        }
        status.textContent = message;
        status.classList.toggle('fp-ctx-status--error', !!isError);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
