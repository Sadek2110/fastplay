# Instrucciones para cumplir la rubrica DWEC en Fastplay

## Objetivo

Fastplay debe poder demostrar, en codigo y en presentacion, que cumple todos los criterios de ejecucion y exposicion de la rubrica DWEC. La prioridad maxima es que el profesor pueda localizar rapidamente el codigo JavaScript propio, ver una interaccion real entre DOM, eventos y AJAX/JSON, y comprobar que la aplicacion mantiene su arquitectura MVC, sesiones, cookies, CRUD y requisitos funcionales.

Fuentes revisadas:
- `rubrica/apartadosProyectoFinalDWEC.pdf`
- `rubrica/rubricaProyectoFinal_DWEC.xlsx`
- `rubrica/Captura de pantalla 2026-05-28 200817.png`

## Prioridad 1: Evidencia obligatoria DOM + eventos + AJAX/JSON

La rubrica indica que DOM, eventos y AJAX deben interactuar en algun punto de la aplicacion a partir de un dato enviado por el usuario al servidor. La interfaz debe transformarse segun el estado, identificacion o rol recibido.

Implementar una funcionalidad demostrable y facil de encontrar:

1. Crear un endpoint JSON en PHP, por ejemplo `DashboardController::context()` o `ProfileController::context()`.
2. El endpoint debe devolver el contexto real del usuario autenticado:
   - `role`: `admin`, `captain`, `player` o `guest`.
   - `isPremium`.
   - `team`.
   - `unreadNotifications`.
   - acciones permitidas.
3. Crear `public/js/dwec-context-panel.js`.
4. En el dashboard o perfil, anadir un panel con atributos `data-*`, sin logica pesada inline.
5. Al pulsar un boton como "Actualizar contexto", el JavaScript debe:
   - lanzar `fetch()` al endpoint PHP;
   - procesar JSON;
   - usar `try/catch`;
   - modificar DOM, clases, atributos ARIA y textos;
   - mostrar interfaz distinta para admin, capitan, jugador y visitante;
   - mostrar errores userFriendly si falla.

Esta pieza sera la demostracion principal para los criterios de DOM, eventos, AJAX, JSON, excepciones y transformacion de interfaz.

## Prioridad 2: JavaScript separado y documentado

Mover el JavaScript inline actual a archivos externos en `public/js/`:

| Codigo actual | Nuevo archivo recomendado | Accion |
|---|---|---|
| `app/views/chat/room.php` | `public/js/chat-room.js` | Convertir el chat en envio/recarga AJAX usando `/chat/messages/{id}` y respuesta JSON. |
| `app/views/matches/create.php` | `public/js/match-request-form.js` | Extraer busqueda de rival, validacion y eventos. |
| `app/views/teams/show.php` | `public/js/team-detail.js` | Quitar `onclick`, usar listeners/delegacion y crear nodos DOM seguros. |
| `public/js/nav.js` | mantener | Mejorar `fetch()` con `try/catch`, mensajes y ruta basada en `FP_BASE_URL`. |
| `public/js/campos-map.js` | mantener | Anadir JSDoc en espanol y documentar Leaflet/Google Maps como API/libreria. |
| `public/js/matches-calendar.js` | mantener | Reducir `innerHTML` cuando sea posible o justificarlo y escapar datos. |

Reglas para todo archivo JS propio:

- Incluir `'use strict';` dentro del IIFE o modulo.
- Usar nombres claros en espanol o ingles consistente.
- Anadir JSDoc en espanol antes de funciones relevantes:

```javascript
/**
 * Actualiza el panel contextual segun el rol devuelto por el servidor.
 *
 * @param {HTMLElement} panel Panel principal del dashboard.
 * @param {Object} context Datos JSON devueltos por PHP.
 * @returns {void}
 */
function renderContextPanel(panel, context) {
  // ...
}
```

- Usar estructuras avanzadas de forma natural: `Map`, `Set`, desestructuracion, arrow functions y pares clave/valor cuando aporten claridad.
- Evitar inyectar HTML completo desde JavaScript. Preferir `createElement`, `textContent`, `classList`, `dataset` y plantillas pequenas justificadas.
- Si se usa `innerHTML`, solo con contenido controlado o escapado.

## Prioridad 3: Cookies frontEnd diferenciadas del backEnd

La rubrica pide cookies frontEnd con aviso al usuario y diferenciadas de las cookies de sesion PHP.

Implementar:

1. `public/js/cookie-consent.js`.
2. Banner o modal discreto de consentimiento.
3. Cookies propias con prefijo `fp_client_`, por ejemplo:
   - `fp_client_cookie_consent=true`
   - `fp_client_last_field=ID_CAMPO`
   - `fp_client_calendar_view=month`
   - `fp_client_last_team_filter=...`
4. No guardar datos sensibles, emails, tokens ni contrasenas.
5. Documentar que la cookie PHP de sesion es backEnd y las `fp_client_*` son frontEnd.
6. Enlazar o mencionar `legal/cookies`.

## Prioridad 4: Validacion de formularios y expresiones regulares

Crear `public/js/form-validation.js` y conectarlo a formularios clave:

| Pantalla | Archivo de vista | Validaciones recomendadas |
|---|---|---|
| Registro | `app/views/auth/register.php` | Email, nombre, password fuerte, confirmacion. |
| Login | `app/views/auth/login.php` | Email valido y password no vacia. |
| Perfil | `app/views/profile/edit.php` | Nombre, ciudad, dorsal, posicion. |
| Crear equipo | `app/views/teams/create.php` | Nombre, ciudad, siglas/badge. |
| Solicitar partido | `app/views/matches/create.php` | Equipo rival seleccionado. |
| Chat | `app/views/chat/room.php` | Mensaje entre 1 y 800 caracteres. |

Reglas:

- Mantener siempre validacion PHP en servidor.
- La validacion JS solo mejora UX y evidencia DWEC.
- Usar expresiones regulares claras y comentadas:

```javascript
const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;
const teamNamePattern = /^[A-Za-z\u00C0-\u017F0-9 .'-]{3,60}$/;
```

- Mostrar mensajes cerca del campo, usar `aria-invalid`, `aria-describedby` y clases CSS de error.
- Impedir el envio solo si hay errores reales.

## Prioridad 5: AJAX, promesas y errores userFriendly

Estandarizar las peticiones AJAX en `public/js/http.js` o dentro de cada modulo si se prefiere no crear abstraccion.

Todas las peticiones deben:

- Usar `fetch()` con `async/await` o `.then().catch()` de forma consistente.
- Enviar y recibir JSON donde corresponda.
- Comprobar `response.ok`.
- Capturar errores con `try/catch`.
- Mostrar mensajes claros al usuario, no errores tecnicos.
- Mantener estados de carga y botones deshabilitados durante la peticion.

Endpoints recomendados:

| Endpoint | Uso |
|---|---|
| `/dashboard/context` | Demostracion principal DOM + eventos + AJAX + rol. |
| `/chat/messages/{id}` | Recargar mensajes del chat. |
| `/chat/send/{id}` o `/chat/send-json/{id}` | Enviar mensajes sin recargar pagina. |
| `/notification/unreadCount` | Actualizar contador de notificaciones. |
| `/teams/filter` opcional | Busqueda AJAX de equipos si se quiere reforzar aun mas. |

## Prioridad 6: APIs, librerias y autoaprendizaje

Evidencias actuales y mejoras:

- `public/js/campos-map.js` ya usa Leaflet/OpenStreetMap y soporta Google Maps como alternativa.
- Bootstrap Icons se usa como libreria visual.
- Documentar claramente estas decisiones en la guia de rubrica.

Para maximizar puntuacion:

- Preparar una seccion de documentacion explicando por que se usa Leaflet/OpenStreetMap y que aporta.
- Si el profesor exige jQuery de forma expresa, anadir una mejora aislada y no critica con jQuery, por ejemplo una animacion o filtro visual en una pantalla secundaria. Si no lo exige, no introducir jQuery solo por cumplir, porque el proyecto esta construido con JavaScript vanilla.

## Prioridad 7: CRUD y mantenimiento de tablas

La captura de criterios de ejecucion asigna peso alto al mantenimiento de tablas: insercion, borrado, actualizacion y consultas.

Preparar una matriz de CRUD real:

| Tabla / entidad | Insertar | Consultar | Actualizar | Borrar / cancelar | Pantallas |
|---|---|---|---|---|---|
| `users` | registro | login, admin, perfil | editar perfil, rol | eliminar usuario admin | auth, profile, admin |
| `teams` | crear equipo | listado/detalle | pendiente si aplica | eliminar/dejar equipo | teams, admin |
| `team_members` | aceptar solicitud | plantilla | rol/capitan si aplica | dejar equipo | teams |
| `matches` | confirmar/crear partido | calendario/detalle | finalizar/cancelar | cancelar/eliminar | matches |
| `fields` | crear/admin | campos/mapa | admin | admin | campos, admin |
| `leagues` | crear liga | listado/clasificacion | admin | admin | leagues, admin |
| `chat_messages` | enviar mensaje | sala/chat AJAX | no aplica | moderacion futura | chat |
| `notifications` | servicios PHP | campana/listado | marcar leida | limpiar si aplica | notifications |

Si alguna entidad no tiene actualizacion o borrado real, documentar el motivo o implementarlo antes de entregar.

## Prioridad 8: Sesiones, cookies y seguridad

Evidencias que deben estar documentadas:

- Sesiones PHP endurecidas en `config/config.php`.
- `HttpOnly`, `SameSite`, `Secure` cuando aplique.
- Regeneracion de ID tras login.
- CSRF en formularios POST.
- Cookies frontEnd `fp_client_*` separadas de la sesion PHP.
- Aviso de cookies visible y pagina legal.

## Prioridad 9: Programacion orientada a objetos y MVC

Dejar claro en documentacion y presentacion:

- Controladores en `app/controllers`.
- Modelos en `app/models`.
- Servicios en `app/services`.
- Nucleo MVC en `app/core`.
- Vistas en `app/views`.
- Separacion de responsabilidades:
  - Controller: valida flujo HTTP y permisos.
  - Model: datos y consultas.
  - Service: reglas transversales como notificaciones, correo, solicitudes.
  - View: render HTML sin logica de negocio pesada.

No mezclar SQL directo en vistas. No meter logica de negocio en JavaScript si debe vivir en backend.

## Prioridad 10: Tabla de localizacion para la presentacion

Anadir esta tabla al README o a una guia especifica de entrega:

| Conocimiento a evaluar | Archivo/s donde localizarlo |
|---|---|
| Validacion de formularios y expresiones regulares | `public/js/form-validation.js`, vistas de auth/profile/teams/matches/chat |
| Cookies frontEnd | `public/js/cookie-consent.js`, `app/views/legal/cookies.php` |
| DOM | `public/js/dwec-context-panel.js`, `public/js/team-detail.js`, `public/js/matches-calendar.js`, `public/js/campos-map.js` |
| Eventos | `public/js/dwec-context-panel.js`, `public/js/match-request-form.js`, `public/js/nav.js`, `public/js/team-detail.js` |
| AJAX/JSON | `public/js/dwec-context-panel.js`, `public/js/chat-room.js`, `public/js/nav.js`, `DashboardController::context`, `ChatController::messages` |
| Control de excepciones `try/catch` sin contar AJAX | `public/js/form-validation.js`, `public/js/cookie-consent.js`, `public/js/campos-map.js`, `public/js/matches-calendar.js` |
| APIs/librerias | `public/js/campos-map.js`, `CamposController`, Leaflet/OpenStreetMap, Google Maps opcional, Bootstrap Icons |
| JavaScript propio adicional | `public/js/theme.js`, `public/js/home-init.js`, `public/js/scroll-anim.js`, `public/js/fifa-card.js` |

## Criterios de exposicion

La captura separa la nota en ejecucion y presentacion. Para cubrir la exposicion:

| Criterio | Instruccion practica |
|---|---|
| Fluidez de comunicacion | Preparar un guion de 8-10 minutos con demo real, sin leer parrafos largos. |
| Empleo de recursos TIC | Usar GitHub, la aplicacion local, una presentacion corta, capturas y una tabla de archivos evaluables. |
| Organizacion y secuenciacion | Seguir este orden: problema, arquitectura, demo funcional, demo DWEC, seguridad/CRUD, cierre. |
| Dominio del contenido | Saber explicar por que cada archivo existe y que criterio de la rubrica demuestra. |
| Claridad del proyecto | Explicar Fastplay como plataforma de futbol amateur: usuarios, equipos, partidos, campos, chat y notificaciones. |
| Parte especifica del modulo | Dedicar una seccion solo a JavaScript: DOM, eventos, AJAX/JSON, cookies, regex, APIs, errores y JSDoc. |

Estructura recomendada de la presentacion:

1. Presentar Fastplay y sus roles: visitante, jugador, capitan y admin.
2. Mostrar arquitectura MVC y OOP: controladores, modelos, servicios y vistas.
3. Demostrar CRUD: usuarios/equipos/partidos/campos/notificaciones.
4. Demostrar sesiones, CSRF y cookies.
5. Demostrar la pieza DWEC principal: boton/evento -> AJAX -> JSON PHP -> transformacion DOM por rol.
6. Mostrar validaciones JS con regex y mensajes de error.
7. Mostrar APIs/librerias: Leaflet/OpenStreetMap, Google Maps opcional, Bootstrap Icons.
8. Abrir la tabla de localizacion de codigo para que el profesor vea donde revisar cada criterio.

## Checklist final antes de presentar

- [ ] No queda JavaScript funcional importante inline en vistas.
- [ ] Todos los JS propios tienen `'use strict';`.
- [ ] Las funciones principales tienen JSDoc en espanol.
- [ ] Hay una demo clara de DOM + eventos + AJAX/JSON + rol de usuario.
- [ ] Hay cookies frontEnd con aviso y prefijo propio.
- [ ] Hay validacion JS con expresiones regulares en formularios importantes.
- [ ] Los errores AJAX y no AJAX muestran mensajes userFriendly.
- [ ] La integracion de Leaflet/OpenStreetMap queda documentada como API/libreria.
- [ ] La tabla de localizacion de codigo propio esta en la documentacion.
- [ ] CRUD de entidades principales comprobado.
- [ ] Sesiones, CSRF y cookies documentadas.
- [ ] Tests PHP pasan.
- [ ] Se prueba visualmente login, dashboard, equipos, partidos, campos, chat y notificaciones.
- [ ] La exposicion tiene guion, demo y tabla de localizacion de codigo.

## Orden recomendado de implementacion

1. Crear `dwec-context-panel.js` y endpoint JSON de contexto.
2. Extraer JS inline de chat, partidos y equipo a archivos externos.
3. Crear validacion comun de formularios.
4. Crear cookies frontEnd y aviso.
5. Mejorar errores AJAX y estados de carga.
6. Anadir JSDoc y `'use strict';` a todos los JS propios.
7. Actualizar README/guia con tabla de localizacion.
8. Ejecutar pruebas y hacer revision visual completa.
