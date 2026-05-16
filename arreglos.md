# 🔧 FastPlay v3 — Arreglos pendientes

> Re-auditoría del proyecto al 2026-05-16 (post commit `56de7fd` _“Arreglos del proyecto”_). Compara el estado actual con la auditoría previa (`ebee7fa`) y añade los fallos nuevos detectados.
>
> Leyenda: 🔴 crítico · 🟠 importante · 🟡 menor · 🔵 documentación / cosmético · ✅ resuelto desde la auditoría previa

---

## 0. Resumen ejecutivo

Esta tanda **cierra el Sprint 1** de la auditoría anterior: el runtime de PHP y los 192 PNG ya no se versionan, las transiciones de estado del partido se validan, el borrado de equipo bloquea si hay histórico y `MatchesController::finish` acota el marcador. El `ChatController` también pasa por una capa de control de acceso (parcial), y `AdminController::deleteUser` añade defensa en profundidad para los borrados en cascada.

Tras esta sesión se cierran los puntos críticos de **deploy** y **seguridad** (`.dockerignore`, `APP_ENV` en Docker, logout CSRF, borrado de liga/campo con histórico, `ON DELETE RESTRICT` para `teams.captain_id`) y la mayoría del **Sprint 2/3** de UX y documentación (CSS/JS inline extraídos, chat con polling, botones de partido restringidos a managers, README actualizado). Quedan abiertos ítems que requieren nuevas tablas o endpoints: transferencia de capitanía (3.2.7), salas de chat con lista de miembros (3.2.1b) y negociación por partido (3.2.12).

---

## 1. Resueltos desde la auditoría previa

Solo se listan para cerrar la trazabilidad. No requieren acción.

| Ítem | Cambio que lo resuelve |
|---|---|
| ✅ 3.1.1 | `php/` movido a `.gitignore` (`/.gitignore:11`). `git ls-files` ya no devuelve ninguno de los 82 archivos del runtime. |
| ✅ 3.1.3 | `public/frames/` movido a `.gitignore` (`/.gitignore:12`); ningún PNG queda rastreado. |
| ✅ 3.1.4 | `php/php.ini` deja de distribuirse como consecuencia de 3.1.1. |
| ✅ 3.1.5 | `.gitignore` ya no incluye `!uploads/frames/`; sólo conserva la excepción `!uploads/.htaccess`. |
| ✅ 3.2.2 | `Partido::setStatus` valida transiciones: rechaza salir de `finished`/`cancelled`, exige `pending→confirmed` y `confirmed→finished` ([app/models/Partido.php:95-123](app/models/Partido.php#L95-L123)). |
| ✅ 3.2.3 | `Partido::create` valida que `field_id` y `league_id` existan antes de insertar ([app/models/Partido.php:71-76](app/models/Partido.php#L71-L76)). |
| ✅ 3.2.4 | `MatchesController::finish` recorta con `min(99, max(0, …))` y rechaza marcadores 0–0 ([app/controllers/MatchesController.php:134-142](app/controllers/MatchesController.php#L134-L142)). |
| ✅ 3.2.5 | `TeamsController::delete` consulta `Equipo::deletionBlocker` y aborta si hay partidos o ligas activas ([app/models/Equipo.php:103-123](app/models/Equipo.php#L103-L123)). |
| ✅ 3.2.1 _(parcial)_ | `ChatController::send` ahora pasa por `Chat::send`, que invoca `Chat::canAccessRoom`. La sala `match_negotiation` queda restringida a capitanes. Las salas de tipo `group`/`general`/`team` siguen abiertas a cualquier usuario autenticado — ver nuevo punto 3.2.1b abajo. |
| ✅ 3.2.6 _(parcial)_ | `AdminController::deleteUser` bloquea si el usuario capitanea equipos ([app/controllers/AdminController.php:83-88](app/controllers/AdminController.php#L83-L88)). Defensa en profundidad — la FK `teams.captain_id ON DELETE CASCADE` sigue activa en [Database.php:77](app/core/Database.php#L77), por lo que cualquier borrado directo en DB o vía otro path seguiría arrasando equipos. |

---

## 2. Pendientes de la auditoría previa que siguen abiertos

✅ **2.1 — Inline CSS/JS masivo en `home/index.php`**
- [home/index.php:2-149](app/views/home/index.php#L2-L149) sigue conteniendo un bloque `<style>` de **148 líneas** con todas las clases `.scroll-*` específicas de la landing.
- [home/index.php:363-428](app/views/home/index.php#L363-L428) tiene un bloque `<script>` de 66 líneas con el inicializador de `FastPlayScrollAnim`.
- Mientras existan, la CSP debe seguir permitiendo `'unsafe-inline'` para `style-src` y `script-src` ([config.php:64-71](config/config.php#L64-L71)).
- **Fix:** mover las reglas `.scroll-*` a `public/css/scroll-anim.css` (ya existe) y el inicializador a `public/js/scroll-anim.js`. Los datos dinámicos (lista de ligas, stats) pueden viajar vía atributos `data-*` para no romper la CSP.

✅ **2.2 — `<link rel="stylesheet">` cargado dentro de `<body>`**
- [home/index.php:1](app/views/home/index.php#L1) emite `<link rel="stylesheet" href="…/scroll-anim.css">` como primera línea de la vista, pero la vista se inyecta en [layouts/main.php:19](app/views/layouts/main.php#L19), debajo de `</head>` (línea 11). Resultado: la etiqueta `<link>` queda dentro de `<body>` → FOUC y HTML inválido.
- **Fix:** exponer un slot `$head` en `main.php` (`<?= $head ?? '' ?>` antes de `</head>`) y mover el `<link>` ahí, o fusionar `scroll-anim.css` dentro de `app.css`.

✅ **2.3 — `Usuario::register()` sigue ignorando `city` y `position`**
- [app/models/Usuario.php:61-64](app/models/Usuario.php#L61-L64) hace `INSERT INTO users (name,email,phone,age,password_hash,role)` y omite las columnas `city` y `position`, aunque el esquema las define ([Database.php:62-63](app/core/Database.php#L62-L63)) y el README promete perfil con esos campos.
- **Fix:** o se piden en el formulario de alta y se persisten, o se documenta que sólo se rellenan vía `profile/edit`.

🟠 **2.4 — CSP sigue con `'unsafe-inline'` en `script-src` y `style-src`**
- Consecuencia directa de 2.1. El propio `config.php:55-56` ya lo asume.

🟡 **2.5 — Footer con iconos de redes no clicables**
- [partials/footer.php:31-35](app/views/partials/footer.php#L31-L35) renderiza tres `<span aria-disabled="true">` (`𝕏`, `in`, `ig`) con estilo de botón. Aunque tienen `title="Próximamente"`, visualmente parecen interactivos.

✅ **2.6 — Chat sin auto-refresh**
- [chat/room.php:31-36](app/views/chat/room.php#L31-L36) sólo hace `scrollTop = scrollHeight` en carga; no hay polling ni WebSocket. El README sigue anunciando “chat en vivo” en línea 67.
- **Fix corto:** `fetch('chat/messages/{id}?after={lastId}')` cada 5–10 s devolviendo JSON. Si v3 es final, retitular como “chat asíncrono” en el README.

🟡 **2.7 — Router rechaza guiones medios en URLs**
- [Router.php:15](app/core/Router.php#L15) sigue con `^[a-zA-Z0-9_]+$`. Decisión consciente; documentada en README:237. Sin impacto funcional hoy.

✅ **2.8 — `MatchesController::create` redirige al usuario sin equipo**
- [MatchesController.php:41-45](app/controllers/MatchesController.php#L41-L45) rompe el ciclo POST. Mejor render in-place con CTA “Crea un equipo →”.

🟡 **2.9 — `config.php` sigue siendo “todo en uno” (~194 líneas)**
- Refactor opcional. El código funciona.

✅ **2.10 — `Content-Type` UTF-8 dependiente del default de Apache**
- `security_headers()` ([config.php:57-72](config/config.php#L57-L72)) no envía `Content-Type: text/html; charset=UTF-8`. Conviene hacerlo explícito para no depender de la config del host.

✅ **2.11 — Fallback `'mayo de 2026'` en `terms.php:16`**
- `LegalController::terms` ya inyecta `LEGAL_LAST_UPDATED = '2026-05-10'` ([LegalController.php:6,12](app/controllers/LegalController.php#L6)), así que el `<?= e($lastUpdated ?? 'mayo de 2026') ?>` de [terms.php:16](app/views/legal/terms.php#L16) es código muerto.

🔵 **3.1.2 — El `.git/` sigue pesando**
- Aunque los blobs nuevos del runtime PHP y los frames ya no se incorporan, los blobs ya commiteados en el historial siguen ahí. Si el repo se hace público (portfolio), purgar con `git filter-repo` o BFG tras coordinar con cualquier colaborador.

🔵 **3.1.6 — Faltan `LICENSE`, `SECURITY.md`, `CHANGELOG.md`**
- README cita “uso académico”, pero sin archivo `LICENSE` el código no tiene licencia explícita y nadie puede reutilizarlo legalmente. Tampoco hay canal documentado para reportar vulnerabilidades.

✅ **3.2.6b — La FK `teams.captain_id ON DELETE CASCADE` sigue activa**
- Aunque `AdminController::deleteUser` ahora protege la ruta admin (3.2.6 ✅ parcial), [Database.php:77](app/core/Database.php#L77) sigue definiendo `FOREIGN KEY (captain_id) REFERENCES users(id) ON DELETE CASCADE`. Cualquier borrado vía SQL directo, futura migración mal hecha o nuevo endpoint que llame a `Usuario::delete()` sin el guard arrastra los equipos enteros.
- **Fix:** cambiar a `ON DELETE RESTRICT` (o `SET NULL` si se introduce un capitán “orfan”). Implica regenerar la tabla en SQLite (`CREATE TABLE … _new`, copiar, `DROP/RENAME`) o aceptar el coste y borrar el SQLite en dev — los datos los regenera el seeder.

🟡 **3.2.7 — `Equipo::leave` cierra al capitán sin ofrecer salida**
- [Equipo.php:88-96](app/models/Equipo.php#L88-L96) devuelve `false` si el usuario es capitán y `TeamsController` le pide _“transfiere la capitanía o elimina el equipo”_, pero **no existe** ningún endpoint de transferencia. Y desde la auditoría anterior `AdminController::deleteUser` también bloquea si el usuario capitanea equipos (3.2.6 ✅) — la UX queda atrapada: ni jugador ni admin pueden continuar sin transfer.
- **Fix:** añadir `Equipo::transferCaptaincy(int $teamId, int $fromUserId, int $toUserId)` con verificación de pertenencia, y exponer un `POST /teams/transferCaptaincy/{id}`.

🟡 **3.2.8 — Demo credentials públicas en el README**
- [README.md:286-287](README.md#L286-L287) sigue publicando `admin@fastplay.es / Admin1234!`. El seed está protegido por `APP_ENV !== 'production'` ([Database.php:197-199](app/core/Database.php#L197-L199)); si se despliega sin definir `APP_ENV=production`, el atacante tiene admin instantáneo. Para portfolio aceptable; mantener vigilado el aviso de [README.md:289](README.md#L289).

✅ **3.2.9 — `public/.htaccess` no bloquea `json`, `lock`, `yml`, `Dockerfile`**
- [public/.htaccess:14](public/.htaccess#L14) sólo cubre `\.(env|md|log|sqlite|sqlite-journal|ini|sql)$`. Hoy ningún `composer.json`/`package.json`/`Dockerfile` vive bajo `public/`, pero conviene endurecer: `…|json|lock|yml|yaml|dockerfile`.

✅ **3.3.1 — `Dockerfile` presente pero sin instrucciones en README**
- Existe un [`Dockerfile`](Dockerfile) funcional (php:8.2-apache + pdo_sqlite + mod_rewrite). El README sólo describe XAMPP. Añadir una sección **Despliegue con Docker**:
  ```bash
  docker build -t fastplay .
  docker run -p 8080:80 -e APP_ENV=production fastplay
  ```

✅ **3.3.2 — `router.php` para `php -S` no está documentado**
- [`router.php`](router.php) emula `public/.htaccess` para el servidor embebido. Sin mención en el README, los devs sin XAMPP no lo encuentran:
  ```bash
  php -S localhost:8000 router.php
  ```

✅ **3.3.3 — `fileinfo` listado como requisito sin uso**
- [README.md:249](README.md#L249) pide la extensión `fileinfo` _“para validar uploads”_, pero el propio README reconoce en línea 305 que la subida está pendiente. Quitar o reetiquetar.

✅ **3.3.4 — `LegalController::cookies` y `privacy` no pasan `$lastUpdated`**
- [LegalController.php:17-31](app/controllers/LegalController.php#L17-L31) sólo pasa la fecha a `terms`. Las tres páginas legales deberían publicar fecha de última actualización por coherencia y trazabilidad GDPR.

---

## 3. Fallos nuevos detectados en esta auditoría

### 3.1 Deploy y empaquetado

✅ **3.1.7 — `Dockerfile` empaqueta el repositorio completo sin `.dockerignore`**
- El [`Dockerfile`](Dockerfile) hace `COPY . .` y el repo **no tiene** `.dockerignore`. El contexto incluye:
  - `php/` con DLLs Windows (inservibles en Linux, ~85 MB).
  - `storage/fastplay.sqlite` si el desarrollador tiene una DB local poblada (con hashes bcrypt reales y datos demo). Aunque `.gitignore` lo excluye, Docker copia desde el filesystem, no desde el índice git.
  - `arreglos.md`, `.claude/`, `.git/` (a menos que esté `*/dockerfile*` excluido por defecto, lo cual no es el caso para `.git/`).
- **Riesgo:** imagen final con datos de desarrollo, runtime irrelevante y secretos potenciales en `.git/config`. Tamaño multiplicado.
- **Fix:**
  ```dockerfile
  # .dockerignore (raíz)
  .git
  .gitignore
  .claude
  arreglos.md
  README.md
  Dockerfile
  .dockerignore
  php/
  public/frames/
  storage/*.sqlite
  storage/*.sqlite-journal
  storage/*.log
  uploads/*
  !uploads/.htaccess
  ```

✅ **3.1.8 — `Dockerfile` instala `fileinfo` que el código no usa**
- [Dockerfile:6](Dockerfile#L6) hace `docker-php-ext-install pdo pdo_sqlite mbstring fileinfo`. Ningún punto del código llama a `finfo_*` (la subida de archivos sigue pendiente — README:305). Mantenerlo está bien si se planifica el feature; si no, eliminar para reducir capa.

✅ **3.1.9 — `Dockerfile` no fija `APP_ENV=production`**
- [Dockerfile](Dockerfile) no exporta `ENV APP_ENV=production`, así que la imagen ejecuta en `development` por defecto: el seeder corre y crea `admin@fastplay.es / Admin1234!` en cada arranque que detecte DB vacía. El README documenta el override con `-e APP_ENV=production`, pero conviene blindar la imagen por defecto (`ENV APP_ENV=production` en el `Dockerfile`) y forzar a desactivarlo explícitamente en dev.

### 3.2 Seguridad y autorización

✅ **3.2.10 — `AuthController::logout` admite GET sin CSRF**
- [AuthController.php:56-64](app/controllers/AuthController.php#L56-L64):
  ```php
  public function logout(): void
  {
      if ($_SERVER['REQUEST_METHOD'] === 'POST') {
          require_csrf();
      }
      logout_user();
      // …
  }
  ```
  Una petición `GET /auth/logout` cierra la sesión **sin** validar token. Permite logout-CSRF (un atacante incrusta `<img src="/auth/logout">` en cualquier sitio y desloguea a la víctima al cargar la página).
- **Fix:** exigir POST siempre (`$this->requirePost()`) y siempre `require_csrf()`. El README:209 ya documenta la ruta como `POST`.

✅ **3.2.11 — Borrado de liga/campo en admin sin chequeo de histórico**
- [AdminController::deleteLeague](app/controllers/AdminController.php#L125-L132) y [AdminController::deleteField](app/controllers/AdminController.php#L134-L141) llaman directamente a `delete(...)`. Por las FKs:
  - `leagues.id` cae en cascada a `league_teams` (`Database.php:114`), arrastrando la clasificación; y en `matches.league_id` con `SET NULL` (`Database.php:142`), dejando partidos huérfanos sin liga.
  - `fields.id` con `SET NULL` (`Database.php:143`), eliminando el campo histórico de partidos finalizados.
- **Fix:** mismo patrón que `Equipo::deletionBlocker`: rechazar si `EXISTS (SELECT 1 FROM matches WHERE league_id=?)` con `status='finished'`, o exponer “archivar” en lugar de “borrar”.

🟡 **3.2.1b — Salas de chat tipo `group`/`team` siguen sin lista de miembros**
- Tras la mejora de 3.2.1, [Chat::canAccessRoom](app/models/Chat.php#L62-L71) restringe `match_negotiation` a capitanes, pero para cualquier otro tipo devuelve `true`. Eso significa que cualquier usuario autenticado puede leer/escribir en una futura sala `team` o `group` privada.
- **Fix:** introducir tabla `chat_room_members(room_id, user_id)` (o derivar dinámicamente desde `team_members`/`league_teams` para los tipos correspondientes) y comprobarla en `canAccessRoom` para `group`/`team`.

🟡 **3.2.12 — `match_negotiation` es una única sala global**
- [Chat::canAccessRoom:67-69](app/models/Chat.php#L67-L69) admite a “cualquier capitán de cualquier equipo” en la sala `match_negotiation`. La auditoría anterior asumía que era “entre capitanes de los dos equipos del partido”, pero el seed sólo crea una sala compartida ([Database.php:317](app/core/Database.php#L317)). Cualquier capitán ve toda la negociación de los demás.
- **Fix:** convertir la sala en `match_negotiation` _por partido_, creada al programar el partido en `Partido::create` y restringida a los capitanes de `home_team_id`/`away_team_id`.

✅ **3.2.13 — `matches/show.php` expone Cancelar/Finalizar a cualquier usuario autenticado**
- [matches/show.php:30-54](app/views/matches/show.php#L30-L54) muestra los formularios de confirmar/cancelar/finalizar a todo usuario logueado, dependiendo sólo del estado del partido. El servidor (`canManageMatch`) rechaza correctamente, pero la UI confunde y produce 302 “No tienes permisos” a usuarios que no debían ver el botón.
- **Fix:** pasar `isManager = is_admin() || $equipo->isCaptain($home) || $equipo->isCaptain($away)` desde el controlador y envolver los formularios con `<?php if ($isManager): ?>`.

### 3.3 Documentación

✅ **3.3.5 — `README.md` sigue describiendo `public/frames/` como parte de la estructura**
- [README.md:158](README.md#L158) lista `│   ├── frames/                      # Secuencia de scroll (192 PNG)`, pero `public/frames/` ya está en `.gitignore` (línea 12) y _no_ se versiona. El árbol promete contenido que un clon nuevo no encuentra. Posibles vías:
  1. Documentar cómo generar los frames a partir de `public/video/hero.webm` (ya presente) con `ffmpeg`.
  2. Indicar que el hero usa el `<video>` y dejar de mencionar los PNG en el árbol.
- Nota: actualmente `home/index.php:169-171` ya usa `<video id="heroVideo">` con `hero.webm`/`hero-poster.jpg`, no `frames/`. Confirmar que `scroll-anim.js` no carga PNG por defecto antes de actualizar el README.

✅ **3.3.6 — Roadmap del README contradice el estado real**
- [README.md:378-379](README.md#L378-L379) dice _“Extracción parcial de animaciones a archivos externos … Quedan bloques `<style>`/`<script>` inline en `home/index.php` por la dependencia de variables PHP.”_ Sigue siendo cierto (ver 2.1/2.2), pero el roadmap también declara la auditoría de seguridad _“completa”_ — y este documento prueba lo contrario. Coherencia: el README puede enlazar a `arreglos.md` como fuente viva.

---

## 4. Prioridad sugerida

1. **Sprint 1 — Seguridad / deploy crítico:** 3.1.7 (`.dockerignore`), 3.1.9 (`APP_ENV` en Dockerfile), 3.2.10 (logout CSRF), 3.2.11 (delete liga/campo), 3.2.6b (`ON DELETE RESTRICT` para `teams.captain_id`).
2. **Sprint 2 — UX y limpieza visible:** 2.1, 2.2, 2.6, 3.2.7 (`transferCaptaincy`), 3.2.13 (UI matches/show), 3.3.1, 3.3.2.
3. **Sprint 3 — Coherencia y pulido:** 2.3, 2.5, 2.8, 2.10, 2.11, 3.1.8, 3.2.1b, 3.2.12, 3.2.9, 3.3.3, 3.3.4, 3.3.5, 3.3.6.
4. **Backlog opcional / refactor:** 2.4 (depende de 2.1), 2.7, 2.9, 3.1.2 (purga histórica si el repo se hace público), 3.1.6.

---

_Generado tras una re-auditoría manual de `/`, `/app`, `/config`, `/public`, `/storage`, `/uploads`, `Dockerfile`, `router.php` y `README.md`. Para reproducir, comparar este documento con `git log -- arreglos.md` para ver el delta respecto a la versión anterior._
