/**
 * FastPlay · Validacion de formularios con expresiones regulares
 * --------------------------------------------------------------
 * Modulo declarativo: cualquier formulario con [data-fp-validate] se
 * conecta automaticamente. Cada campo declara su regla via
 * `data-fp-rule` y opcionalmente un `data-fp-error`. La validacion
 * JavaScript es estrictamente complementaria a la validacion PHP,
 * que sigue siendo la unica autoritativa.
 *
 * Demuestra: DOM, eventos, expresiones regulares, try/catch (en el
 * parseo del propio set de reglas), ARIA (aria-invalid,
 * aria-describedby) y mensajes userFriendly cercanos al campo.
 */
(function () {
    'use strict';

    /**
     * Catalogo de reglas reutilizables.
     *
     * @type {Object<string, {pattern: RegExp, message: string, optional?: boolean}>}
     */
    const RULES = {
        email: {
            // Email simple: algo@algo.tld (>=2 chars en TLD).
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/,
            message: 'Introduce un email valido (por ejemplo nombre@dominio.com).',
        },
        'password-strong': {
            // Minimo 8, al menos una letra y un numero. Mensaje guia.
            pattern: /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d\W_]{8,}$/,
            message: 'La contrasena debe tener 8 caracteres minimo, con letras y numeros.',
        },
        'password-basic': {
            pattern: /^.{6,}$/,
            message: 'La contrasena debe tener al menos 6 caracteres.',
        },
        name: {
            // Letras (incluye acentos), espacios, guiones y apostrofes (3-60).
            pattern: /^[A-Za-zÀ-ÿ' \-]{3,60}$/,
            message: 'Nombre invalido. Usa entre 3 y 60 letras, espacios o guiones.',
        },
        city: {
            pattern: /^[A-Za-zÀ-ÿ0-9 ,.'\-]{2,80}$/,
            message: 'Ciudad invalida. Usa letras, numeros y espacios (2-80).',
        },
        'team-name': {
            pattern: /^[A-Za-zÀ-ÿ0-9 .'\-]{3,60}$/,
            message: 'Nombre de equipo invalido (3-60 caracteres validos).',
        },
        badge: {
            pattern: /^[A-Za-z0-9]{1,4}$/,
            message: 'Las siglas deben tener entre 1 y 4 letras o numeros.',
        },
        dorsal: {
            pattern: /^(?:[0-9]|[1-9][0-9])$/,
            message: 'Dorsal invalido. Usa un numero entre 0 y 99.',
            optional: true,
        },
        position: {
            pattern: /^(Portero|Portera|Defensa|Mediocampo|Delantero)$/,
            message: 'Selecciona una posicion valida.',
            optional: true,
        },
        'chat-message': {
            // Permite cualquier caracter no vacio, hasta 800 chars.
            pattern: /^[\s\S]{1,800}$/,
            message: 'El mensaje debe tener entre 1 y 800 caracteres.',
        },
    };

    /**
     * Devuelve la regla por nombre o null si no existe.
     *
     * @param {string|null} key
     * @returns {{pattern: RegExp, message: string, optional?: boolean}|null}
     */
    function findRule(key) {
        if (!key) return null;
        return Object.prototype.hasOwnProperty.call(RULES, key) ? RULES[key] : null;
    }

    /**
     * Inicializa todos los formularios marcados con data-fp-validate.
     *
     * @returns {void}
     */
    function init() {
        document.querySelectorAll('form[data-fp-validate]').forEach(function (form) {
            attachForm(form);
        });
    }

    /**
     * Conecta listeners de blur/input/submit en el formulario.
     *
     * @param {HTMLFormElement} form
     * @returns {void}
     */
    function attachForm(form) {
        const fields = Array.from(form.querySelectorAll('[data-fp-validate-field]'));
        if (!fields.length) return;

        fields.forEach(function (field) {
            field.addEventListener('blur', function () { validateField(field); });
            field.addEventListener('input', function () { clearError(field); });
        });

        form.addEventListener('submit', function (event) {
            let firstInvalid = null;
            fields.forEach(function (field) {
                const valid = validateField(field);
                if (!valid && !firstInvalid) firstInvalid = field;
            });
            if (firstInvalid) {
                event.preventDefault();
                firstInvalid.focus();
            }
        });
    }

    /**
     * Valida un campo concreto y pinta el mensaje si corresponde.
     *
     * @param {HTMLElement} field
     * @returns {boolean}
     */
    function validateField(field) {
        try {
            const ruleKey = field.getAttribute('data-fp-rule');
            const rule = findRule(ruleKey);
            const value = ('value' in field) ? String(field.value || '').trim() : '';

            if (rule && rule.optional && value === '') {
                clearError(field);
                return true;
            }
            if (field.hasAttribute('required') && value === '') {
                setError(field, field.getAttribute('data-fp-error') || 'Este campo es obligatorio.');
                return false;
            }
            if (!rule) {
                clearError(field);
                return true;
            }
            if (!rule.pattern.test(value)) {
                setError(field, field.getAttribute('data-fp-error') || rule.message);
                return false;
            }
            clearError(field);
            return true;
        } catch (err) {
            console.warn('[form-validation] error al validar campo:', err);
            clearError(field);
            return true;
        }
    }

    /**
     * Marca el campo como invalido y muestra el mensaje accesible.
     *
     * @param {HTMLElement} field
     * @param {string} message
     * @returns {void}
     */
    function setError(field, message) {
        field.setAttribute('aria-invalid', 'true');
        const errorEl = ensureErrorNode(field);
        errorEl.textContent = message;
        errorEl.hidden = false;
    }

    /**
     * Limpia los marcadores de error del campo.
     *
     * @param {HTMLElement} field
     * @returns {void}
     */
    function clearError(field) {
        field.removeAttribute('aria-invalid');
        const errorEl = field.parentElement
            ? field.parentElement.querySelector('[data-fp-error-for="' + (field.id || field.name || '') + '"]')
            : null;
        if (errorEl) {
            errorEl.hidden = true;
            errorEl.textContent = '';
        }
    }

    /**
     * Crea o recupera el contenedor del mensaje de error.
     *
     * @param {HTMLElement} field
     * @returns {HTMLElement}
     */
    function ensureErrorNode(field) {
        const id = field.id || field.name || ('fpf_' + Math.random().toString(36).slice(2, 8));
        if (!field.id) field.id = id;
        let errorEl = field.parentElement
            ? field.parentElement.querySelector('[data-fp-error-for="' + id + '"]')
            : null;
        if (!errorEl) {
            errorEl = document.createElement('small');
            errorEl.className = 'fp-field-error';
            errorEl.setAttribute('data-fp-error-for', id);
            errorEl.setAttribute('role', 'alert');
            if (field.parentElement) {
                field.parentElement.appendChild(errorEl);
            }
            const describedBy = field.getAttribute('aria-describedby');
            const describedId = 'err_' + id;
            errorEl.id = describedId;
            field.setAttribute('aria-describedby', describedBy ? describedBy + ' ' + describedId : describedId);
        }
        return errorEl;
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
