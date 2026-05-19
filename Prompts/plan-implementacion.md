# Plan de implementación — Fastplay (4 agentes asíncronos)

Refactor según [arreglos.md](arreglos.md). Cada fase es un agente independiente con su ámbito de archivos. Agente 1 entrega primero (infra compartida); 2, 3 y 4 trabajan en paralelo sin colisión.

---

## Contrato común (los 4 agentes lo respetan)

- [ ] Sin emojis en toda la app. Iconos = Bootstrap Icons (`<i class="bi bi-..."></i>`).
- [ ] Tema mediante `data-theme="dark|light"` en `<html>` + variables CSS.
- [ ] Notificaciones se crean SIEMPRE vía `NotificationService::create($userId, $type, $message, $url)`.
- [ ] Correos se envían SIEMPRE vía `MailService::send($to, $subject, $template, $data)`.
- [ ] Premium se valida en backend con `Usuario::isPremium($userId)`.
- [ ] CSRF en todos los POST nuevos (usar helper existente).
- [ ] Tests PHPUnit por agente, prefijados por dominio.

---

## Fase 1 — Agente "Infra & Diseño base"

**Ámbito exclusivo:** `app/core/Database.php`, `database/*.sql`, `app/views/layouts/`, `app/views/partials/`, `public/css/app.css`, `public/js/theme.js`, `public/js/nav.js`.

### Base de datos
- [ ] Añadir migración idempotente `notifications` (id, user_id, type, message, is_read, action_url, created_at).
- [ ] Añadir migración `team_join_requests` (id, team_id, user_id, captain_id, status, created_at, updated_at).
- [ ] Añadir migración `match_requests` (id, requesting_team_id, requested_team_id, requesting_captain_id, requested_captain_id, status, created_at, updated_at).
- [ ] Añadir migración `subscriptions` (id, user_id, provider, provider_customer_id, provider_subscription_id, status, starts_at, ends_at, created_at, updated_at).
- [ ] Ampliar `users`: `is_premium`, `dorsal`, `height`, `position` (si faltan).
- [ ] Ampliar `teams`: `shield` (si falta).
- [ ] Ampliar `matches`: `local_captain_id`, `visitor_captain_id`, `match_time`, `location`, `status` (`pendiente/jugando/jugado/cancelado`).
- [ ] Ampliar `chat_rooms`: `type` (`team` | `match_negotiation`), `match_request_id`.
- [ ] Ampliar `fields`: `latitude`, `longitude`, `maps_url`, `image`, `description` (si faltan).
- [ ] Replicar todo en [database/fastplay_mysql.sql](database/fastplay_mysql.sql) y [database/fastplay_postgres.sql](database/fastplay_postgres.sql).

### Tema claro/oscuro
- [ ] Definir tokens CSS (`--bg`, `--surface`, `--text`, `--text-muted`, `--accent`, `--border`, `--danger`, `--success`) en `:root` y `[data-theme="light"]`.
- [ ] Crear `public/js/theme.js`: lee `localStorage.theme`, toggle, persiste, aplica al `<html>`.
- [ ] Botón toggle en navbar (icono `bi-sun` / `bi-moon`).
- [ ] Revisar TODOS los componentes: navbar, footer, cards, tablas, formularios, modales, inputs, chats, notificaciones, carta FIFA, mapa campos.

### Navbar y navegación
- [ ] Navbar responsive con hamburguesa (`bi-list`), se cierra al click en link.
- [ ] Móvil: ocultar `logo_palabra`, solo logo principal.
- [ ] Menú condicional según `auth` (visitante / jugador / capitán / admin).
- [ ] Parcial `partials/back-button.php` (botón volver reutilizable).
- [ ] Slot `<?= $navExtras ?? '' ?>` en navbar para que otros agentes inyecten badges.

### Emojis → iconos
- [ ] Grep global de emojis en `app/views/`, `public/`, controladores. Sustituir 1:1 por `<i class="bi bi-...">`.
- [ ] Añadir CDN Bootstrap Icons en `layouts/main.php` (o autohospedar si CSP lo exige).

### Responsive y fondo
- [ ] Media queries con breakpoints 480 / 768 / 1024 / 1280.
- [ ] Fondo estático en `@media (max-width: 768px)` (eliminar parallax/animaciones pesadas).
- [ ] Tablas → scroll horizontal en móvil o cards apiladas.

### Helpers UI compartidos
- [ ] `partials/empty-state.php` con icono + título + descripción + CTA.
- [ ] `partials/loader.php`.
- [ ] `partials/toast.php` con estados success/error/info.
- [ ] Helper `flash($type, $message)` en `Controller.php` si no existe.

---

## Fase 2 — Agente "Usuario, Dashboard & Notificaciones"

**Ámbito exclusivo:** `DashboardController`, `ProfileController`, `NotificationController` (nuevo), `Notification` (modelo nuevo), `NotificationService` (servicio nuevo), `app/views/dashboard/`, `app/views/profile/`, `app/views/notifications/` (nueva), `public/css/fifa-card.css`, `public/js/fifa-card.js`.

### Dashboard
- [ ] Eliminar sección "logros" de la vista (datos siguen en BD).
- [ ] Mantener info principal del usuario.
- [ ] Sustituir tarjeta FIFA vieja por la nueva (ver abajo).
- [ ] Añadir sección "Notificaciones" (preview últimas 5 + link a `/notification`).
- [ ] Si el usuario no tiene equipo: card explicativa con CTA "Crear / Unirse".
- [ ] Si no hay partidos: empty-state con CTA "Solicitar partido".

### Perfil
- [ ] Botón perfil del navbar → `/profile/edit` (no a la vista pública).
- [ ] Vista de edición con todos los campos nuevos (`dorsal`, `height`, `position`, foto).

### Carta FIFA (rediseño completo)
- [ ] Forma poligonal (clip-path) con bordes personalizados.
- [ ] Fondo con degradado moderno (premium / no-premium variantes).
- [ ] Datos: foto, nombre, posición, dorsal, equipo actual, altura, PJ, goles, asistencias.
- [ ] Iconos (no emojis) por estadística.
- [ ] Hover 3D con `transform: perspective(...) rotateX rotateY` calculado por JS según posición del ratón.
- [ ] Animación de entrada (fade + scale).
- [ ] Sombra elegante, microinteracciones en stats al hover.
- [ ] Responsive: en móvil se reduce y desactiva 3D para rendimiento.
- [ ] Buen contraste en modo claro y oscuro.

### Notificaciones (modelo + servicio + UI)
- [ ] Modelo `Notification.php` con queries: `forUser`, `unreadCount`, `markRead`, `markAllRead`, `create`.
- [ ] Servicio `NotificationService::create($userId, $type, $message, $url = null)`.
- [ ] Controller `NotificationController`:
  - [ ] `GET /notification` → listado paginado con filtros (todas / no leídas).
  - [ ] `POST /notification/markRead/:id` → marca leída.
  - [ ] `POST /notification/markAllRead` → marca todas.
  - [ ] `GET /notification/unreadCount` → JSON para badge AJAX.
- [ ] Badge en navbar con contador AJAX (refresca cada 60 s).
- [ ] Tipos soportados: `team_join_request`, `team_join_accepted`, `team_join_rejected`, `match_request`, `match_request_accepted`, `match_created`, `subscription_activated`, `subscription_expired`, `system`.
- [ ] Empty-state "No tienes notificaciones".
- [ ] Tests: `tests/NotificationTest.php` (creación, marcar leída, contador, paginación).

---

## Fase 3 — Agente "Equipos, Partidos & Chats"

**Ámbito exclusivo:** `TeamsController`, `MatchesController`, `ChatController`, `TeamJoinRequestController` (nuevo), `MatchRequestController` (nuevo), modelos `TeamJoinRequest` y `MatchRequest` (nuevos), servicios `TeamJoinService` y `MatchRequestService` (nuevos), `app/views/teams/`, `app/views/matches/`, `app/views/chat/`.

### Equipos — vista condicional
- [ ] Si usuario NO tiene equipo:
  - [ ] Botón "Crear equipo" (gated por premium — ver Fase 4).
  - [ ] Buscador + listado de equipos disponibles.
  - [ ] Botón "Solicitar unirse" en cada tarjeta.
  - [ ] Mensajes claros explicando que el capitán debe aceptar.
- [ ] Si usuario YA tiene equipo:
  - [ ] Mostrar SOLO su equipo (nombre, escudo, capitán, jugadores, puntos, ranking, stats).
  - [ ] Chat interno del equipo (ver "Chat" abajo).
  - [ ] Botones de gestión si es capitán.
  - [ ] Botón "Ver todos los equipos" → `/teams/all`.

### Tabla de todos los equipos
- [ ] Vista `teams/all.php`: tabla con escudo, nombre, capitán, puntos, nº jugadores, estado, botón detalle.
- [ ] Buscador, filtros, ordenación (puntos / nombre / fecha creación).
- [ ] Responsive (scroll horizontal o cards en móvil).

### Solicitudes para unirse a equipo
- [ ] `POST /team-join-request/create` (usuario → capitán) con validaciones:
  - [ ] No pertenece ya a otro equipo.
  - [ ] No tiene solicitud pendiente al mismo equipo.
- [ ] `POST /team-join-request/accept/:id` (solo capitán del equipo).
- [ ] `POST /team-join-request/reject/:id` (solo capitán).
- [ ] Al crear → `NotificationService::create($captainId, 'team_join_request', ...)` + `MailService::send($captain, 'solicitud_equipo', ...)`.
- [ ] Al aceptar → añadir a `team_members` + notificar usuario + correo.
- [ ] Al rechazar → notificar usuario (correo opcional).

### Partidos — solo si pertenece a un equipo
- [ ] Bloquear `/matches/*` si usuario sin equipo → mensaje + CTA.
- [ ] Vista solicitar partido: dropdown + búsqueda de equipos rivales, info básica (escudo, nombre, capitán, puntos, jugadores).
- [ ] Solo capitanes pueden solicitar.

### Flujo solicitud de partido
- [ ] `POST /match-request/create` (capitán → capitán rival):
  - [ ] Crea `match_requests` con `status='pending'`.
  - [ ] Notifica + correo al capitán rival.
- [ ] `POST /match-request/accept/:id`:
  - [ ] Cambia estado.
  - [ ] Crea `chat_rooms` con `type='match_negotiation'` y `match_request_id`.
  - [ ] Notifica + correo ambos.
- [ ] `POST /match-request/reject/:id` → notifica + cierra.
- [ ] `POST /match-request/confirm/:id` → cuando ambos capitanes confirman fecha/hora/lugar → crea `match` con `status='pendiente'`.

### Validaciones (§12.1, §12.2, §12.3)
- [ ] Crear equipo: requiere premium (delegado a Fase 4).
- [ ] Un capitán = un equipo (salvo override).
- [ ] No solicitar unirse si ya tienes equipo.
- [ ] No duplicar solicitudes pendientes (equipo / partido).
- [ ] No solicitar partido contra tu propio equipo.
- [ ] Solo capitanes solicitan partidos.
- [ ] Match no se crea sin fecha + hora + lugar.

### Chat (refactor)
- [ ] Eliminar chat global del navbar y rutas existentes.
- [ ] `ChatController::team($teamId)`: solo miembros del equipo.
- [ ] `ChatController::matchNegotiation($matchRequestId)`: solo los dos capitanes implicados.
- [ ] Vista: mensajes diferenciados por capitán, timestamps, historial.
- [ ] En negociación: botones para proponer fecha/hora/lugar + confirmar.
- [ ] Cerrar chat de negociación cuando `match_request` pasa a `accepted_final` (partido creado) o `rejected/cancelled`.
- [ ] Tests: `tests/TeamJoinRequestTest.php`, `tests/MatchRequestTest.php`, `tests/ChatAccessTest.php`.

---

## Fase 4 — Agente "Premium, Pagos, Campos & Correos"

**Ámbito exclusivo:** `SubscriptionController` (nuevo), `PaymentController` (nuevo), `FieldController` (o ampliar `CamposController`), modelo `Subscription` (nuevo), servicios `MailService`, `StripeService`, `FieldService` (nuevos), `app/views/subscription/`, `app/views/campos/map.php`, `app/views/emails/`, `config/stripe.php`, `config/mail.php`.

### Suscripción premium (Stripe)
- [ ] `config/stripe.php` con keys (lee de env).
- [ ] `StripeService` con métodos `createCheckoutSession`, `retrieveSubscription`, `cancelSubscription`.
- [ ] `SubscriptionController`:
  - [ ] `GET /subscription` → vista de planes con card de upgrade.
  - [ ] `POST /subscription/checkout` → crea sesión Stripe + redirect.
  - [ ] `GET /subscription/success` → confirma y actualiza local.
  - [ ] `GET /subscription/cancel` → vista cancelación.
- [ ] `PaymentController::webhook` → endpoint público (whitelisted en CSRF):
  - [ ] Verifica firma Stripe.
  - [ ] Maneja `customer.subscription.created`, `updated`, `deleted`, `invoice.paid`, `invoice.payment_failed`.
  - [ ] Actualiza `subscriptions.status` + `users.is_premium`.
  - [ ] Dispara notificación + correo (activada / cancelada / expirada).
- [ ] Método `Usuario::isPremium($userId)` que consulta `subscriptions.status='active'`.
- [ ] Vista de upgrade con CTA cuando usuario sin premium intenta crear equipo.

### Gating "crear equipo"
- [ ] En `TeamsController::create` validar `Usuario::isPremium()` (coordinar con Fase 3).
- [ ] Si no es premium → render card de upgrade + redirect a `/subscription`.

### Sección Campos (Ceuta)
- [ ] Ampliar/crear `FieldController`:
  - [ ] `GET /campos` → mapa Leaflet + columna de tarjetas.
  - [ ] `GET /campos/show/:id` → ficha del campo.
- [ ] Vista `campos/map.php`:
  - [ ] Mapa Leaflet (CDN, sin API key) centrado en Ceuta.
  - [ ] Marcadores con popups (foto + nombre + ver más).
  - [ ] Columna lateral con cards: foto, nombre, dirección, info, botón detalle, link Google Maps.
- [ ] Layout: escritorio = mapa al lado de columna; móvil = mapa arriba, cards abajo.
- [ ] Seed inicial: campos reales de Ceuta con coords (en `Database::seed()` o migración separada).

### Correos transaccionales (MailService)
- [ ] `MailService::send($to, $subject, $template, $data)` usando PHPMailer (composer require) o `mail()` fallback.
- [ ] Plantillas HTML en `app/views/emails/`:
  - [ ] `solicitud_equipo.php` — usuario solicita unirse.
  - [ ] `solicitud_equipo_aceptada.php`.
  - [ ] `solicitud_equipo_rechazada.php`.
  - [ ] `solicitud_partido.php`.
  - [ ] `solicitud_partido_aceptada.php`.
  - [ ] `partido_creado.php`.
  - [ ] `premium_activado.php`.
  - [ ] `premium_cancelado.php` / `premium_expirado.php`.
- [ ] Cada plantilla con: asunto, saludo, explicación, botón CTA, firma.
- [ ] `config/mail.php` con SMTP (host, port, user, pass, from).
- [ ] Tests: `tests/SubscriptionTest.php`, `tests/PaymentWebhookTest.php`, `tests/FieldMapTest.php`, `tests/MailServiceTest.php` (mock SMTP).

---

## Orden de merge y QA final

- [ ] Mergear Fase 1 a `main` primero.
- [ ] Fases 2, 3, 4 rebase sobre `main` y mergean en cualquier orden.
- [ ] QA integración: badge de notificaciones en navbar (slot Fase 1 + datos Fase 2).
- [ ] QA integración: gating premium en `TeamsController::create` (Fase 3 invoca check de Fase 4).
- [ ] QA integración: `NotificationService` (Fase 2) usado por Fases 3 y 4.
- [ ] QA integración: `MailService` (Fase 4) usado por Fases 3 y 4.
- [ ] Pasada visual completa en modo claro y modo oscuro de todas las secciones.
- [ ] Pasada responsive en 480 / 768 / 1024 / 1280.
- [ ] Verificar criterios de aceptación de [arreglos.md](arreglos.md) §16 uno por uno.
- [ ] Documentar cambios en `MEMORY.md` y `GUIA_PROYECTO.md`.

---

## Riesgos conocidos

- [ ] `app/views/layouts/main.php` — único punto de fricción real. Fase 1 deja slots; los demás solo inyectan.
- [ ] `app/core/Database.php::migrate()` — Fase 1 lo monopoliza.
- [ ] CSP con `unsafe-inline` aún activo — los nuevos scripts inline (theme toggle, 3D card) deben moverse a archivos externos.
- [ ] PHPMailer trae dependencias — añadir a `composer.json` con Fase 4.
- [ ] Stripe webhook necesita URL pública — documentar Stripe CLI para test local.
