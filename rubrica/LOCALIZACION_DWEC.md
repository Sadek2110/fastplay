# Localizacion del codigo evaluable DWEC en Fastplay

Documento generado tras aplicar `INSTRUCCIONES_CUMPLIMIENTO_DWEC.md`.
Permite al profesor abrir directamente el archivo que demuestra cada
criterio sin tener que rastrear el codigo.

## Pieza principal exigida por la rubrica

**Boton -> evento -> AJAX -> JSON PHP -> transformacion DOM por rol**

- Vista: [`app/views/dashboard/index.php`](../app/views/dashboard/index.php) (panel `data-dwec-context`).
- JavaScript: [`public/js/dwec-context-panel.js`](../public/js/dwec-context-panel.js).
- Endpoint JSON: [`DashboardController::context()`](../app/controllers/DashboardController.php) -> `GET /dashboard/context`.
- Roles soportados: `guest`, `player`, `captain`, `admin`. Cambian
  clase CSS, icono, etiqueta, mensaje y chips de acciones permitidas.

## Tabla de localizacion por criterio

| Criterio DWEC | Archivo(s) donde se demuestra |
|---|---|
| Validacion de formularios y expresiones regulares | [`public/js/form-validation.js`](../public/js/form-validation.js), vistas `auth/login.php`, `auth/register.php`, `profile/edit.php`, `teams/create.php`, `matches/create.php`, `chat/room.php`. |
| Cookies frontEnd diferenciadas de las cookies de sesion PHP | [`public/js/cookie-consent.js`](../public/js/cookie-consent.js), [`app/views/partials/cookie-banner.php`](../app/views/partials/cookie-banner.php), [`app/views/legal/cookies.php`](../app/views/legal/cookies.php). |
| DOM | [`public/js/dwec-context-panel.js`](../public/js/dwec-context-panel.js), [`public/js/team-detail.js`](../public/js/team-detail.js), [`public/js/matches-calendar.js`](../public/js/matches-calendar.js), [`public/js/campos-map.js`](../public/js/campos-map.js). |
| Eventos | [`public/js/dwec-context-panel.js`](../public/js/dwec-context-panel.js), [`public/js/match-request-form.js`](../public/js/match-request-form.js), [`public/js/nav.js`](../public/js/nav.js), [`public/js/team-detail.js`](../public/js/team-detail.js), [`public/js/chat-room.js`](../public/js/chat-room.js). |
| AJAX / JSON | [`public/js/dwec-context-panel.js`](../public/js/dwec-context-panel.js), [`public/js/chat-room.js`](../public/js/chat-room.js), [`public/js/nav.js`](../public/js/nav.js); endpoints: `DashboardController::context`, `ChatController::messages`, `NotificationController::unreadCount`. |
| Excepciones `try/catch` (sin contar AJAX) | [`public/js/form-validation.js`](../public/js/form-validation.js), [`public/js/cookie-consent.js`](../public/js/cookie-consent.js), [`public/js/campos-map.js`](../public/js/campos-map.js), [`public/js/theme.js`](../public/js/theme.js). |
| APIs / librerias externas | Leaflet/OpenStreetMap y Google Maps opcional en [`public/js/campos-map.js`](../public/js/campos-map.js) + [`CamposController`](../app/controllers/CamposController.php); Bootstrap Icons en `app/views/layouts/main.php`. |
| JavaScript propio adicional | [`public/js/theme.js`](../public/js/theme.js), [`public/js/home-init.js`](../public/js/home-init.js), [`public/js/scroll-anim.js`](../public/js/scroll-anim.js), [`public/js/fifa-card.js`](../public/js/fifa-card.js). |

## CRUD por entidad (mantenimiento de tablas)

| Tabla | Insertar | Consultar | Actualizar | Borrar / cancelar |
|---|---|---|---|---|
| `users` | `auth/register.php` | `auth/login.php`, `profile`, `admin` | `profile/edit.php`, `admin` (cambio rol) | admin (eliminar usuario) |
| `teams` | `teams/create.php` | `teams/index.php`, `teams/show.php` | admin / capitan | admin / dejar equipo |
| `team_members` | aceptar solicitud | plantilla en `teams/show.php` | gestion de capitan | dejar equipo |
| `matches` | `matches/create.php` (solicitud) | `matches/index.php`, `matches/show.php` | confirmar / finalizar | cancelar |
| `fields` | admin | `campos/index.php` (mapa Leaflet) | admin | admin |
| `leagues` | admin | `leagues/index.php` | admin | admin |
| `chat_messages` | `chat/room.php` (AJAX) | `chat/room.php` (poll AJAX) | n/a | `chat/deleteMessage/{id}` (autor o admin) |
| `notifications` | servicios PHP | `notifications/index.php` + badge AJAX | marcar leida (`notification/markRead/{id}`) | `notification/delete/{id}` y `notification/clearRead` (limpiar leidas) |

## Sesiones y cookies

- Sesion PHP endurecida: [`config/config.php`](../config/config.php)
  (`HttpOnly`, `SameSite=Lax`, `Secure` en HTTPS, `session_regenerate_id`
  tras login, `_csrf` por token aleatorio).
- Cookies frontEnd con prefijo `fp_client_*` gestionadas en
  [`public/js/cookie-consent.js`](../public/js/cookie-consent.js).
- Politica visible en [`app/views/legal/cookies.php`](../app/views/legal/cookies.php).
- Banner discreto en cada pagina via [`app/views/partials/cookie-banner.php`](../app/views/partials/cookie-banner.php).

## Checklist de cumplimiento

- [x] No queda JavaScript funcional relevante inline en las vistas
      principales (chat, equipo, partidos, dashboard).
- [x] Todos los JS propios incluyen `'use strict';`.
- [x] Las funciones principales tienen JSDoc en espanol.
- [x] Demo clara DOM + eventos + AJAX/JSON + rol de usuario
      (`/dashboard` -> "Actualizar contexto").
- [x] Cookies frontEnd con aviso y prefijo propio.
- [x] Validacion JS con expresiones regulares en formularios clave.
- [x] Errores AJAX y no AJAX con mensajes userFriendly y no tecnicos.
- [x] Leaflet/OpenStreetMap documentado como API/libreria.
- [x] Tabla de localizacion (este archivo) lista para la presentacion.
- [x] CRUD de entidades principales cubierto.
- [x] Sesiones, CSRF y cookies documentadas.
- [x] `phpunit` pasa los 218 tests existentes.

## Demo recomendada (8-10 min)

1. Login en `/auth/login` -> regex valida el email en cliente.
2. Dashboard -> pulsar **"Actualizar contexto"** para mostrar la pieza
   DWEC principal (rol, equipo, premium, notificaciones, acciones).
3. Navegar a `/teams/show/{id}` para ver el detalle de plantilla con
   delegacion de eventos (sin `onclick` inline).
4. Entrar al chat de equipo y mandar un mensaje: se envia por fetch y
   refresca el feed via `/chat/messages/{id}`.
5. `/matches/create` para mostrar busqueda en cliente.
6. `/campos` para mostrar Leaflet + marcadores propios.
7. Banner de cookies: aceptar/rechazar y abrir `/legal/cookies` para
   mostrar la distincion frontEnd / backEnd.
8. Abrir este archivo para que el profesor vea la tabla de
   localizacion del codigo propio.
