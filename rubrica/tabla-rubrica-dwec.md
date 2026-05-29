# Tabla de localización de código JavaScript - Proyecto Final DWEC

> Esta tabla indica al profesor en qué archivos se encuentra cada conocimiento evaluable según la rúbrica del proyecto final de DWEC.

| Conocimiento a evaluar | Archivo/s donde lo puedo localizar |
|---|---|
| **Validación de datos de formulario. Expresiones regulares.** | `public/js/form-validation.js` — Catálogo de reglas regex (email, password, name, city, team-name, badge, dorsal, position, chat-message). Validación en blur/input/submit con mensajes accesibles (ARIA). |
| | `public/js/match-request-form.js` — Validación previa al envío del formulario de solicitud de partido. |
| **Cookies en frontEnd** | `public/js/cookie-consent.js` — Gestión de cookies del lado cliente con prefijo `fp_client_`, banner de consentimiento (aceptar/rechazar), API pública `window.FastplayCookies`. |
| **DOM** | `public/js/team-detail.js` — createElement, replaceChildren, delegación de eventos, dataset, desestructuración. |
| | `public/js/dwec-context-panel.js` — Manipulación extensiva de DOM: clases CSS, ARIA, dataset, Map para diccionarios de roles, Set para acciones. |
| | `public/js/chat-room.js` — createElement/textContent sin innerHTML, DocumentFragment, replaceChildren. |
| | `public/js/matches-calendar.js` — createElement, insertAdjacentHTML, manipulación de grid de calendario. |
| | `public/js/nav.js` — Sidebar móvil: toggle de clases, ARIA, cierre fuera del panel. |
| | `public/js/theme.js` — Atributo data-theme, toggle de iconos, localStorage. |
| | `public/js/fifa-card.js` — Transform CSS vía style en tiempo real según cursor. |
| | `public/js/scroll-anim.js` — IntersectionObserver, clases CSS, control de video por scroll. |
| | `public/js/home-init.js` — Navbar scrolled, animación de números con requestAnimationFrame. |
| | `public/js/campos-map.js` — Integración con Leaflet/Google Maps, marcadores SVG personalizados. |
| **Eventos** | `public/js/form-validation.js` — blur, input, submit, DOMContentLoaded. |
| | `public/js/match-request-form.js` — input, submit, DOMContentLoaded. |
| | `public/js/team-detail.js` — click con delegación (event.target.closest), DOMContentLoaded. |
| | `public/js/dwec-context-panel.js` — click en botón de refresco, DOMContentLoaded. |
| | `public/js/chat-room.js` — submit (formulario de chat), click (borrar mensaje), setInterval para polling. |
| | `public/js/nav.js` — click (toggle sidebar, cierre fuera), DOMContentLoaded, setInterval para notificaciones. |
| | `public/js/theme.js` — click con delegación en [data-theme-toggle]. |
| | `public/js/cookie-consent.js` — click (aceptar/rechazar banner), change (preferencias). |
| | `public/js/matches-calendar.js` — click (navegación de meses, selección de día). |
| | `public/js/fifa-card.js` — pointermove, pointerleave. |
| | `public/js/scroll-anim.js` — scroll (passive), loadedmetadata, loadeddata. |
| | `public/js/campos-map.js` — click en tarjetas de campos, eventos de marcador Leaflet/Google. |
| **AJAX** | `public/js/dwec-context-panel.js` — fetch GET a `/dashboard/context` (JSON). Pieza principal: evento → AJAX → transformación del DOM según rol. |
| | `public/js/chat-room.js` — fetch POST a `/chat/send/{id}` y fetch GET a `/chat/messages/{id}` (JSON). Polling cada 8s. |
| | `public/js/nav.js` — fetch GET a `/notification/unreadCount` (JSON). Polling cada 60s. |
| **Control de Excepciones try..catch (sin contar las de AJAX)** | `public/js/form-validation.js` — try/catch en `validateField()` al parsear reglas. |
| | `public/js/cookie-consent.js` — try/catch en `readCookie()` y `writeCookieRaw()`. |
| | `public/js/matches-calendar.js` — try/catch en `JSON.parse()` de datos del calendario. |
| | `public/js/campos-map.js` — try/catch en `JSON.parse()` de datos de campos. |
| | `public/js/theme.js` — try/catch en acceso a `localStorage`. |
| **Otros archivos no mencionados con código JavaScript vuestro** | `public/js/scroll-anim.js` — Motor de animación por scroll con IntersectionObserver y requestAnimationFrame. |
| | `public/js/home-init.js` — Inicialización de landing page: navbar con scroll y animación de estadísticas numéricas. |
| | `public/js/fifa-card.js` — Efecto tilt 3D en cartas FIFA siguiendo el cursor. |
