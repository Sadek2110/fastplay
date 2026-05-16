# 🔧 FastPlay v3 — Arreglos pendientes

> Estado al 2026-05-16. Última sesión de corrección aplicada sobre el commit `56de7fd`.
>
> Leyenda: 🔴 crítico · 🟠 importante · 🟡 menor · 🔵 documentación / cosmético · ✅ resuelto

---

## 0. Resumen ejecutivo

Tras la última sesión de corrección se han cerrado los puntos críticos de **deploy** y **seguridad** (`.dockerignore`, `APP_ENV` en Docker, logout CSRF, borrado de liga/campo con histórico, `ON DELETE RESTRICT` para `teams.captain_id`) y la mayoría del Sprint de UX y documentación (CSS/JS inline extraídos de `home/index.php`, chat con polling cada 5 s, botones de partido restringidos a managers, README actualizado).

Quedan abiertos ítems que requieren nuevas tablas o endpoints de mayor alcance: **transferencia de capitanía** (3.2.7), **salas de chat con lista de miembros** (3.2.1b) y **negociación por partido** (3.2.12). También persisten mejoras cosméticas y de documentación pendientes.

---

## 1. Resueltos en esta sesión

| Ítem | Cambio que lo resuelve |
|---|---|
| ✅ 2.1 | Bloque `<style>` de 148 líneas de `home/index.php` movido a `public/css/scroll-anim.css`. |
| ✅ 2.2 | Slot `$head` en `layouts/main.php` + `HomeController` inyecta `<link>` de `scroll-anim.css` en `<head>`. |
| ✅ 2.3 | `Usuario::register()` ahora persiste `city` y `position` si vienen en POST. |
| ✅ 2.6 | Chat con polling: `chat/room.php` refresca cada 5 s vía `fetch` al nuevo endpoint `ChatController::messages`. |
| ✅ 2.8 | `MatchesController::create` ya no redirige al usuario sin equipo; renderiza CTA in-place en la vista. |
| ✅ 2.10 | `security_headers()` envía explícito `Content-Type: text/html; charset=UTF-8` ([config.php](config/config.php)). |
| ✅ 2.11 | Eliminado fallback `'mayo de 2026'` en `terms.php`; la fecha siempre viene del controlador. |
| ✅ 3.1.7 | Creado `.dockerignore` raíz para excluir `php/`, `.git/`, SQLite, uploads y archivos de desarrollo. |
| ✅ 3.1.8 | Eliminada extensión `fileinfo` del `Dockerfile` (no se usa en código actual). |
| ✅ 3.1.9 | `Dockerfile` exporta `ENV APP_ENV=production` por defecto. |
| ✅ 3.2.6b | FK `teams.captain_id` migrada a `ON DELETE RESTRICT` con recreación condicional de tabla en SQLite. |
| ✅ 3.2.10 | `AuthController::logout` exige POST + CSRF (`$this->requirePost()`). |
| ✅ 3.2.11 | `AdminController::deleteLeague` y `deleteField` rechazan borrado si hay partidos `finished` asociados. |
| ✅ 3.2.13 | `matches/show.php` solo muestra botones de gestión a admin o capitanes (`$isManager`). |
| ✅ 3.2.9 | `.htaccess` endurecido: bloquea `json`, `lock`, `yml`, `yaml`, `dockerfile`. |
| ✅ 3.3.1 | README añade sección **Despliegue con Docker** con comandos de ejemplo. |
| ✅ 3.3.2 | README documenta `php -S localhost:8000 router.php`. |
| ✅ 3.3.3 | `fileinfo` reetiquetado como requisito opcional en README. |
| ✅ 3.3.4 | `LegalController::privacy` y `cookies` ahora pasan `$lastUpdated`; vistas lo usan. |
| ✅ 3.3.5 | README indica que `public/frames/` es ignorado por git, no contenido versionado. |
| ✅ 3.3.6 | README enlaza a `arreglos.md` como fuente viva y ya no declara auditoría "completa". |

---

## 2. Pendientes reales

### 🟠 UX y seguridad

**2.4 — CSP sigue con `'unsafe-inline'` en `script-src` y `style-src`**
- Hoy `home/index.php` ya no tiene bloques inline críticos (2.1 ✅), pero quedan atributos `style="…"` inline en varias vistas y el `scroll-anim.js` usa callbacks inline. Para retirar `'unsafe-inline'` hace falta:
  1. Eliminar todos los atributos `style="…"` de las vistas (mover a clases CSS).
  2. Usar `nonce` o hashes para los pocos scripts que queden.
  3. Revisar que `scroll-anim.js` no rompa sin `unsafe-inline`.

**2.5 — Footer con iconos de redes no clicables**
- [partials/footer.php:31-35](app/views/partials/footer.php#L31-L35) renderiza tres `<span aria-disabled="true">` (`𝕏`, `in`, `ig`) con estilo de botón. Aunque tienen `title="Próximamente"`, visualmente parecen interactivos.
- **Fix:** convertir a `<a>` con `href` real si existen cuentas, o a `<button disabled>` para que el lector de pantallas anuncie correctamente el estado.

**2.7 — Router rechaza guiones medios en URLs**
- [Router.php:15](app/core/Router.php#L15) sigue con `^[a-zA-Z0-9_]+$`. Decisión consciente; documentada en README:237. Sin impacto funcional hoy.

**2.9 — `config.php` sigue siendo "todo en uno" (~194 líneas)**
- Refactor opcional. El código funciona. Separar en `config/security.php`, `config/session.php`, `helpers/` si crece.

### 🟡 Funcionalidad futura

**3.2.1b — Salas de chat tipo `group`/`team` siguen sin lista de miembros**
- [Chat::canAccessRoom](app/models/Chat.php#L62-L71) restringe `match_negotiation` a capitanes, pero para cualquier otro tipo devuelve `true`. Eso significa que cualquier usuario autenticado puede leer/escribir en una futura sala `team` o `group` privada.
- **Fix:** introducir tabla `chat_room_members(room_id, user_id)` (o derivar dinámicamente desde `team_members`/`league_teams` para los tipos correspondientes) y comprobarla en `canAccessRoom` para `group`/`team`.

**3.2.7 — `Equipo::leave` cierra al capitán sin ofrecer salida**
- [Equipo.php:88-96](app/models/Equipo.php#L88-L96) devuelve `false` si el usuario es capitán, pero **no existe** endpoint de transferencia de capitanía. Ni jugador ni admin pueden continuar sin transferir primero.
- **Fix:** añadir `Equipo::transferCaptaincy(int $teamId, int $fromUserId, int $toUserId)` con verificación de pertenencia, y exponer `POST /teams/transferCaptaincy/{id}`.

**3.2.12 — `match_negotiation` es una única sala global**
- [Chat::canAccessRoom:67-69](app/models/Chat.php#L67-L69) admite a "cualquier capitán de cualquier equipo" en la sala `match_negotiation`. El seed sólo crea una sala compartida ([Database.php:317](app/core/Database.php#L317)).
- **Fix:** convertir la sala en `match_negotiation` _por partido_, creada al programar el partido en `Partido::create` y restringida a los capitanes de `home_team_id`/`away_team_id`.

### 🔵 Documentación / backlog

**3.1.2 — El `.git/` sigue pesando**
- Aunque los blobs nuevos ya no se incorporan, los blobs ya commiteados en el historial siguen ahí. Si el repo se hace público (portfolio), purgar con `git filter-repo` o BFG tras coordinar con cualquier colaborador.

**3.1.6 — Faltan `LICENSE`, `SECURITY.md`, `CHANGELOG.md`**
- README cita "uso académico", pero sin archivo `LICENSE` el código no tiene licencia explícita. Tampoco hay canal documentado para reportar vulnerabilidades.

**3.2.8 — Demo credentials públicas en el README**
- [README.md:286-287](README.md#L286-L287) publica `admin@fastplay.es / Admin1234!`. El seed está protegido por `APP_ENV !== 'production'`; conviene mantener el aviso visible en README.

---

## 3. Prioridad sugerida

1. **Sprint 1 — Seguridad funcional:** 3.2.1b (chat room members), 3.2.7 (transferCaptaincy), 3.2.12 (negociación por partido).
2. **Sprint 2 — UX pulido:** 2.4 (retirar unsafe-inline de CSP), 2.5 (footer redes interactivo), 2.7 (router guiones medios — documentar o ampliar).
3. **Sprint 3 — Documentación y legal:** 3.1.2 (purga git), 3.1.6 (LICENSE/SECURITY), 3.2.8 (revisar demo creds), 2.9 (refactor config.php).

---

_Generado tras re-auditoría y sesión de corrección. Para reproducir, comparar con `git log -- arreglos.md`._
