(function () {
    'use strict';

    // Auto-inject CSRF token into all POST forms that don't have one
    document.addEventListener('DOMContentLoaded', function () {
        const tokenMeta = document.querySelector('meta[name="csrf-token"]');
        const token = tokenMeta ? tokenMeta.content : null;

        if (!token) return;

        document.querySelectorAll('form[method="POST"]').forEach(function (form) {
            if (form.querySelector('input[name="csrf_token"]')) return;

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'csrf_token';
            input.value = token;
            form.insertBefore(input, form.firstChild);
        });
    });

    // Flash message auto-dismiss
    const flashEl = document.getElementById('flash-msg');
    if (flashEl) {
        setTimeout(function () {
            flashEl.style.opacity = '0';
            flashEl.style.transition = 'opacity .4s';
            setTimeout(function () { flashEl.remove(); }, 400);
        }, 3500);
    }
})();
