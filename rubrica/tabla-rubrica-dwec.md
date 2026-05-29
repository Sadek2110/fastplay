# Dónde encontrar cada cosa en mi código — Proyecto Final DWEC

> Profe, aquí te dejo un mapa rápido de dónde está cada conocimiento que evalúas. Así no tienes que buscar a ciegas.

| Qué evaluar | Dónde mirarlo |
|---|---|
| **Validación de formularios y regex** | `form-validation.js` — Aquí está el gordo: regex para email, password, nombre, ciudad, dorsal, etc. Valida en blur, input y submit con mensajes ARIA. También `match-request-form.js` para el formulario de solicitar partido. |
| **Cookies** | `cookie-consent.js` — Banner de aceptar/rechazar, cookies con prefijo `fp_client_`, y la API `window.FastplayCookies` para usar desde cualquier lado. |
| **DOM** | Los más potentes: `team-detail.js` (createElement, delegación, dataset), `dwec-context-panel.js` (Map, Set, ARIA, clases), `chat-room.js` (sin innerHTML, todo con textContent y DocumentFragment). También toco DOM en `matches-calendar.js`, `nav.js`, `theme.js`, `fifa-card.js`, `scroll-anim.js`, `home-init.js` y `campos-map.js`. |
| **Eventos** | Está repartido por casi todos los archivos: delegación de click en `team-detail.js` y `theme.js`, blur/input/submit en los formularios, pointermove en `fifa-card.js`, scroll en `scroll-anim.js`, setInterval para polling en `chat-room.js` y `nav.js`. |
| **AJAX (fetch)** | `dwec-context-panel.js` — fetch GET al contexto del dashboard, luego transformo el DOM según el rol. `chat-room.js` — POST para enviar y GET para leer mensajes, polling cada 8s. `nav.js` — GET del contador de notificaciones, polling cada 60s. |
| **try/catch** | Los tengo en `form-validation.js` (al parsear reglas), `cookie-consent.js` (al leer/escribir cookies), `matches-calendar.js` y `campos-map.js` (al parsear JSON), y `theme.js` (al tocar localStorage). |
| **Extras con JS propio** | `scroll-anim.js` — animaciones con IntersectionObserver. `home-init.js` — contador animado en landing. `fifa-card.js` — efecto tilt 3D que sigue al cursor. |
