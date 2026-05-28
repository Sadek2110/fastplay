/**
 * FastPlay · Cookies frontEnd y consentimiento
 * --------------------------------------------
 * Gestiona las cookies del lado cliente (prefijo `fp_client_`) y un
 * banner discreto de consentimiento. NUNCA guarda datos sensibles ni
 * tokens; eso es responsabilidad de la sesion PHP (FPSESSID).
 *
 * Demuestra: DOM, eventos, document.cookie, try/catch y separacion
 * clara frontEnd / backEnd (rubrica DWEC).
 *
 * Cookies disponibles:
 *   - fp_client_cookie_consent  (true|false)
 *   - fp_client_theme           (light|dark)
 *   - fp_client_last_field      (id de campo visto)
 *   - fp_client_calendar_view   (month|week)
 *   - fp_client_last_team_filter (string corto)
 */
(function () {
    'use strict';

    const PREFIX = 'fp_client_';
    const CONSENT_KEY = PREFIX + 'cookie_consent';
    const DEFAULT_DAYS = 180;

    /**
     * Lee una cookie por nombre.
     *
     * @param {string} name
     * @returns {string|null}
     */
    function readCookie(name) {
        try {
            const target = name + '=';
            const parts = document.cookie ? document.cookie.split(';') : [];
            for (let i = 0; i < parts.length; i++) {
                const c = parts[i].trim();
                if (c.indexOf(target) === 0) {
                    return decodeURIComponent(c.substring(target.length));
                }
            }
        } catch (_e) {
            // document.cookie no accesible (entornos sandbox) → null.
        }
        return null;
    }

    /**
     * Escribe una cookie frontEnd con prefijo `fp_client_`.
     *
     * @param {string} key
     * @param {string} value
     * @param {number} [days]
     * @returns {boolean}
     */
    function writeClientCookie(key, value, days) {
        const fullName = key.indexOf(PREFIX) === 0 ? key : PREFIX + key;
        if (fullName === CONSENT_KEY) {
            return writeCookieRaw(fullName, value, days || DEFAULT_DAYS);
        }
        if (readCookie(CONSENT_KEY) !== 'true') {
            return false;
        }
        return writeCookieRaw(fullName, value, days || DEFAULT_DAYS);
    }

    /**
     * Implementacion baja: encode + atributos seguros.
     *
     * @param {string} name
     * @param {string} value
     * @param {number} days
     * @returns {boolean}
     */
    function writeCookieRaw(name, value, days) {
        try {
            const expires = new Date(Date.now() + days * 24 * 60 * 60 * 1000).toUTCString();
            const samesite = location.protocol === 'https:' ? 'SameSite=Lax; Secure' : 'SameSite=Lax';
            document.cookie = name + '=' + encodeURIComponent(value)
                + '; expires=' + expires
                + '; path=/; '
                + samesite;
            return true;
        } catch (err) {
            console.warn('[cookie-consent] no se pudo escribir cookie:', err);
            return false;
        }
    }

    /**
     * Borra una cookie frontEnd.
     *
     * @param {string} key
     * @returns {void}
     */
    function deleteClientCookie(key) {
        const fullName = key.indexOf(PREFIX) === 0 ? key : PREFIX + key;
        document.cookie = fullName + '=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/';
    }

    /**
     * Muestra el banner si todavia no hay consentimiento registrado.
     *
     * @returns {void}
     */
    function maybeShowBanner() {
        if (readCookie(CONSENT_KEY) === 'true' || readCookie(CONSENT_KEY) === 'false') {
            return;
        }
        const banner = document.querySelector('[data-cookie-banner]');
        if (!banner) return;
        banner.hidden = false;

        const acceptBtn = banner.querySelector('[data-cookie-accept]');
        const rejectBtn = banner.querySelector('[data-cookie-reject]');
        if (acceptBtn) {
            acceptBtn.addEventListener('click', function () {
                writeCookieRaw(CONSENT_KEY, 'true', DEFAULT_DAYS);
                banner.hidden = true;
            });
        }
        if (rejectBtn) {
            rejectBtn.addEventListener('click', function () {
                writeCookieRaw(CONSENT_KEY, 'false', DEFAULT_DAYS);
                // Limpia cualquier cookie frontEnd previa.
                document.cookie.split(';').forEach(function (chunk) {
                    const name = chunk.split('=')[0].trim();
                    if (name.indexOf(PREFIX) === 0 && name !== CONSENT_KEY) {
                        deleteClientCookie(name);
                    }
                });
                banner.hidden = true;
            });
        }
    }

    /**
     * Persiste algunas preferencias visuales habituales (calendario,
     * tema, ultimo campo seleccionado) si hay consentimiento.
     *
     * @returns {void}
     */
    function bindBasicPreferences() {
        document.querySelectorAll('[data-fp-pref-store]').forEach(function (el) {
            const key = el.getAttribute('data-fp-pref-store');
            if (!key) return;
            el.addEventListener('change', function () {
                const value = el.value || '';
                writeClientCookie(key, value);
            });
        });
    }

    // Exponemos un mini API por si otros JS quieren guardar preferencias.
    window.FastplayCookies = {
        get: readCookie,
        set: writeClientCookie,
        remove: deleteClientCookie,
        hasConsent: function () { return readCookie(CONSENT_KEY) === 'true'; },
        PREFIX: PREFIX,
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            maybeShowBanner();
            bindBasicPreferences();
        });
    } else {
        maybeShowBanner();
        bindBasicPreferences();
    }
})();
