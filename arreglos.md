# 🔧 FastPlay v3 — Arreglos pendientes

> Auditoría del proyecto al 2026-05-10. Lista de fallos, incoherencias y deuda técnica detectada en el código, configuración, assets y documentación.
>
> Leyenda: 🔴 crítico · 🟠 importante · 🟡 menor · 🔵 documentación / cosmético

---

## 1. `.htaccess` — duplicación, incoherencia y reglas mal repartidas

Hay **6 archivos `.htaccess`** en el proyecto (no 4: el README y la sensación general subestiman la cantidad). No están coordinados entre sí y mezclan tres estilos distintos:

| Ruta | Contenido | Observaciones |
|---|---|---|
| `/.htaccess` | Reescribe a `public/` + `Options -Indexes` | OK como front-door |
| `/public/.htaccess` | Reescribe a `index.php` + bloquea `.env\|.md\|.log\|.sqlite\|.sqlite-journal\|.ini\|.sql` + cabeceras de seguridad | OK |
| `/app/.htaccess` | `Require all denied` | Sintaxis Apache 2.4 — **rompe en Apache 2.2** |
| `/config/.htaccess` | `Require all denied` | Idem |
| `/storage/.htaccess` | `Require all denied` | Idem |
| `/uploads/.htaccess` | `Options -Indexes` | **Permite ejecutar PHP** si alguien sube un `.php`. No bloquea acceso por extensión |

🔴 **Fallo 1.1** — `uploads/.htaccess` no impide ejecutar scripts. Sólo desactiva el listado de directorios. Si en el futuro se añade subida de avatares (el campo `avatar` ya existe en `users`), un usuario podría subir un `.php` y ejecutarlo. Añadir:
```apache
Options -Indexes -ExecCGI
RemoveHandler .php .phtml .php3 .php4 .php5 .php7 .phps
<FilesMatch "\.(php|phtml|phar|pl|py|jsp|asp|sh|cgi)$">
    Require all denied
</FilesMatch>
```

🟠 **Fallo 1.2** — `app/.htaccess`, `config/.htaccess`, `storage/.htaccess` usan únicamente sintaxis Apache 2.4. Romperán en hostings con Apache 2.2. Para compatibilidad usar el bloque dual:
```apache
<IfModule mod_authz_core.c>
    Require all denied
</IfModule>
<IfModule !mod_authz_core.c>
    Order allow,deny
    Deny from all
</IfModule>
```

🟡 **Fallo 1.3** — Las cabeceras de seguridad (`X-Frame-Options`, CSP, etc.) están **duplicadas**: se envían tanto desde `public/.htaccess` (mod_headers) como desde `config/config.php` (`security_headers()`). Decidir un único origen para evitar diferencias entre PHP cacheado y respuesta de Apache. Recomendado: dejarlas sólo en PHP (más control) y quitar el bloque `<IfModule mod_headers.c>` de `public/.htaccess`.

🟡 **Fallo 1.4** — `Options -Indexes` se repite en raíz, public y uploads. Bastaría con declararlo una vez en raíz y heredar.

---

## 2. `uploads/` — bloat de assets y mala ubicación

`ls uploads/` muestra:

- `frames/` → **192 PNG** de ~800 KB–1.3 MB cada uno (≈ **170 MB** en total) usados como secuencia de scroll.
- `image(2).png` → 1.754.330 bytes.
- `video_landing_fastplay.mp4` → 5,9 MB.

🔴 **Fallo 2.1** — `uploads/image(2).png` es **byte-idéntico** a `public/images/hero-pitch.png` (md5 `6a984e1b108bfe7da2735fdc3eba15f2`). Es un duplicado puro. Eliminar `uploads/image(2).png`.

🔴 **Fallo 2.2** — `uploads/video_landing_fastplay.mp4` (5,9 MB) **no se referencia desde ningún archivo** del proyecto (`grep -r video_landing` → 0 coincidencias). Asset huérfano: borrar o moverlo bajo `public/` si se va a usar.

🟠 **Fallo 2.3** — `uploads/frames/` rompe el principio que el propio README declara en sus líneas 106-107: *"Sólo `public/` es accesible; `app/`, `config/`, `storage/` están bloqueadas por `.htaccess`"*. Las 192 imágenes se sirven desde `/uploads/frames/...` (ver `app/views/home/index.php:371` y `home/scroll-animation.php:203`). Si `uploads/` está pensado para subidas de usuario, los frames del landing deberían vivir en `public/frames/` o `public/images/frames/`.

🟠 **Fallo 2.4** — Los 192 frames PNG (~170 MB) están **versionados en git**. No hay `.gitignore` en el proyecto. Considerar:
1. Re-codificar la animación a un único `.webm`/`.mp4` (típicamente <5 MB para 192 frames) o a JPEG/WebP de menor peso.
2. Añadir `.gitignore` con al menos `storage/*.sqlite`, `storage/*.log`, `uploads/*` (excepto el contenido de marca como hero-pitch).

🟡 **Fallo 2.5** — El nombre `image(2).png` con paréntesis es un olvido típico de descarga del navegador. Renombrar siempre antes de versionar.

---

## 3. Vistas duplicadas y secciones de UI inconsistentes

🟠 **Fallo 3.1** — Existen **dos landings paralelos**:
- `/` → `app/views/home/index.php` (datos reales: `$leagues`, `$stats`).
- `/home/scrollAnimation` → `app/views/home/scroll-animation.php` (stats hard-coded `2.4K`/`180`/`42`).

Ambos cargan los mismos 192 frames y `scroll-anim.css` pero con CSS y secciones distintas. Duplicación de mantenimiento. Decidir una y borrar la otra (o convertir la "scroll-animation" en variante con flag).

🟠 **Fallo 3.2** — `app/views/home/scroll-animation.php` y `home/index.php` cargan `<link rel="stylesheet" href="...scroll-anim.css">` **dentro del `<body>`**, después de la cabecera HTML del layout. Esto rompe el orden HTML válido y provoca FOUC. El CSS específico de página debe inyectarse en `<head>` (por ejemplo, exponiendo un bloque `$head` desde la vista al layout, o moviendo la importación a `app.css`).

🟠 **Fallo 3.3** — `app/views/partials/tabs.php` declara en su comentario de cabecera: *"Floating preview tabs (réplica del UI Kit) — útil durante desarrollo"*. Duplica la `navbar.php` con otros estilos y se incluye **siempre** desde el layout principal (`layouts/main.php:23`) y `auth.php:17`. Es UI de desarrollo filtrada a producción. Eliminar el partial y su `$this->partial('tabs', ...)` o ocultarlo tras una condición de entorno.

🟡 **Fallo 3.4** — En `home/index.php` (líneas 28, 4-29 del bloque `<style>`) se ocultan `.fp-footer` y `.fp-bg-glow` con `display: none !important`. En `scroll-animation.php` se ocultan además `.fp-navbar` y `.fp-tabs`. El usuario que aterriza en la animación pura **no tiene navegación**: no puede ir a `/auth/login` ni a otras secciones. Mantener al menos la navbar visible.

🟡 **Fallo 3.5** — Los stats hard-coded de `home/scroll-animation.php` (`2.4K`, `180`, `42`) son números inventados que no coinciden con `Liga::stats()` (que sí cuenta usuarios/partidos/ciudades reales). Inconsistencia con la otra landing.

🟡 **Fallo 3.6** — `partials/footer.php` línea 32-34 renderiza "redes sociales" con `<span>` no clicables (𝕏, in, ig). Son decorativos pero parecen enlaces. Convertir a `<a>` con `href` real o quitar.

---

## 4. Autorización y lógica de negocio (huecos)

🔴 **Fallo 4.1** — `MatchesController::confirm()`, `cancel()`, `finish()` exigen `requireAuth()` pero **no verifican** que el usuario pertenezca a uno de los equipos del partido (capitán/miembro). Cualquier usuario logueado puede confirmar, cancelar o cerrar resultado de cualquier partido. Añadir comprobación tipo:
```php
if (!is_admin() && !$equipo->isCaptain($match['home_team_id'], $userId)
                 && !$equipo->isCaptain($match['away_team_id'], $userId)) {
    flash('warn', 'No tienes permisos sobre este partido.');
    redirect('matches/show/'.$id);
}
```

🔴 **Fallo 4.2** — `MatchesController::create()` valida que tengas equipos pero **no comprueba** que el `home_team_id` o `away_team_id` enviados sean tuyos o de tu capitanía. Un atacante puede programar partidos entre equipos ajenos. Validar pertenencia/capitanía en `Partido::create()` o en el controlador.

🟠 **Fallo 4.3** — `Partido::create()` no comprueba que ambos equipos estén inscritos en la liga seleccionada. Si `league_id` se rellena, debería validarse contra `league_teams` para evitar puntuaciones fantasma.

🟠 **Fallo 4.4** — `AdminController::deleteUser()` impide auto-eliminarse pero **no impide eliminar al último admin**, dejando el sistema sin administradores. Añadir guardia:
```php
if (Database::value("SELECT COUNT(*) FROM users WHERE role='admin'") <= 1
    && $usuario->find($id)['role'] === 'admin') { ... }
```

🟡 **Fallo 4.5** — `LeaguesController::register()` redirige sin `return` (líneas 48 y 56-57). Funciona porque `redirect()` hace `exit`, pero estilísticamente añadir `return;` después de cada `redirect` para que sea evidente.

🟡 **Fallo 4.6** — En `app/models/Usuario.php::register()` no se persisten `city` ni `position` aunque están en el esquema. El formulario de registro tampoco los pide; sólo se rellenan en `profile/edit`. Inconsistencia: o se piden en alta o se elimina del esquema inicial. (Coherente con la realidad actual, pero el README sí los promete.)

🟡 **Fallo 4.7** — `Usuario::dashboardStats()` (líneas 115-129 del modelo) devuelve goles/asistencias/tarjetas siempre como `0`. Son placeholders. Si no se piensa implementar, eliminarlas; si sí, crear las tablas `match_events` o equivalentes.

🟡 **Fallo 4.8** — `Partido::delete()` y `Chat::createRoom()` existen en los modelos pero **no se llaman desde ningún controlador**. Código muerto: o se exponen vía `MatchesController::delete` y un panel de chat, o se eliminan.

---

## 5. `config/config.php` y sesiones

🟠 **Fallo 5.1** — `session_set_cookie_params(['path' => '/'])` (línea 30). En XAMPP con la app bajo `/FastPlay_v3/`, la cookie se publica en `/` (raíz del host), pisando otras instalaciones de XAMPP en el mismo dominio. Mejor:
```php
'path' => BASE_URL ?: '/',
```
(definir BASE_URL antes del bloque de sesión).

🟡 **Fallo 5.2** — Las cabeceras de seguridad declaradas en `security_headers()` se envían en TODA petición, incluso 404/500. OK funcional, pero la CSP permite `'unsafe-inline'` para `style-src` y `script-src` porque hay estilos y scripts inline en muchas vistas (la home tiene `<style>` y `<script>` inline). Es una concesión real pero conviene anotarlo y, a medio plazo, mover los inline a archivos `.css`/`.js` para poder retirar `'unsafe-inline'`.

🟡 **Fallo 5.3** — `config.php` mezcla constantes, helpers de URL, helpers de auth, CSRF, validación, flash y old-input. Son ~180 líneas de "todo en uno". Dividir en `bootstrap.php`, `helpers/url.php`, `helpers/auth.php`, `helpers/csrf.php` mejoraría la mantenibilidad. (Refactor opcional, no funcional.)

🟡 **Fallo 5.4** — `if (!is_dir(STORAGE_PATH)) { @mkdir(STORAGE_PATH, 0775, true); }` (líneas 10-12) silencia errores con `@`. Si `storage/` no se puede crear, la BD fallará después con un mensaje opaco. Sustituir por `mkdir(...) or trigger_error(...)`.

---

## 6. `Router.php` y URLs

🟠 **Fallo 6.1** — La regex de validación `^[a-zA-Z0-9_]+$` rechaza guiones medios y puntos. Eso obliga a que la única ruta multi-palabra (`scrollAnimation`) use camelCase en la URL: `/home/scrollAnimation`. El resto de la app usa rutas en minúsculas (`/teams`, `/auth/login`). O se acepta `-` y se convierte a camelCase, o se renombra el método a `scroll` para mantener consistencia.

🟡 **Fallo 6.2** — El array `$blocked = ['view','model','partial','back','requireauth',...]` (línea 38) es redundante: esos métodos están `protected` y `ReflectionMethod::isPublic()` ya los rechaza (línea 50). Mantener uno de los dos para no duplicar mantenimiento.

🟡 **Fallo 6.3** — `Router::serverError()` ya es invocado por el dispatch en caso de excepción, pero **no captura** errores fatales antes del dispatch (p. ej. en `Database::pdo()` el `public/index.php` los maneja a mano). Considerar registrar `set_exception_handler` y `register_shutdown_function` en config para uniformar el manejo.

---

## 7. README.md vs. realidad del código

🔵 **Fallo 7.1** — README línea 159 menciona `BUGS.md` (en la sección "Estructura del Proyecto" y de nuevo en línea 360). **Ese archivo no existe**. Crear el archivo o quitar la referencia.

🔵 **Fallo 7.2** — README línea 198-213 ("Mapa de Rutas") promete rutas que no existen tal cual:
- `GET /login` → real `GET /auth/login`
- `POST /logout` → real `POST /auth/logout`
- `GET /register` → real `GET /auth/register`
- `GET /leagues/{id}` → real `GET /leagues/show/{id}`
- `GET /matches/{id}` → real `GET /matches/show/{id}`

No hay alias ni rewrite que las redirija. Documentar las rutas reales (`controlador/acción/parametro`) o añadir aliases en el `Router`.

🔵 **Fallo 7.3** — README línea 324 *"namespaces `app\controllers`, `app\models`, `app\core`"*. **El código no usa namespaces** (ni `namespace` ni `use` aparecen en ningún archivo PHP). Borrar la frase o introducir namespaces de verdad (afectaría todos los `require_once`).

🔵 **Fallo 7.4** — README línea 283 *"Validación de uploads — Whitelist de MIME types e extensiones para avatars"*. **No hay ningún flujo de subida** de archivos: el campo `users.avatar` está en el esquema (Database.php:67) pero ningún controlador lo procesa. O se implementa o se quita la promesa del README.

🔵 **Fallo 7.5** — README línea 156 dice *"`storage/` … logs (no versionado)"*. No hay sistema de logs propio, sólo `error_log()` al log por defecto del PHP. Y `storage/fastplay.sqlite` **sí está versionado** (no hay `.gitignore`).

🔵 **Fallo 7.6** — README líneas 117-160 ("Estructura del Proyecto") no menciona los `.htaccess` de cada carpeta, ni `uploads/frames/`, ni `uploads/video_landing_fastplay.mp4`. Actualizar el árbol o eliminar lo que no toca.

🔵 **Fallo 7.7** — README línea 285 referencia el commit `684bb7c` para auditoría de seguridad. Mejor enlazar a un `SECURITY.md` o a la sección concreta para no depender del SHA.

🔵 **Fallo 7.8** — README dice *"Esta versión incluye … Extracción de animaciones a archivos externos"* (línea 357), pero `home/index.php` y `home/scroll-animation.php` siguen llevando bloques `<style>` y `<script>` inline grandes. Inconsistencia entre roadmap declarado y código actual.

---

## 8. Otros (varios)

🟡 **Fallo 8.1** — No existe `.gitignore`. Como mínimo añadir:
```
storage/*.sqlite
storage/*.sqlite-journal
storage/*.log
uploads/*
!uploads/.htaccess
.idea/
.vscode/
*.bak
```

🟡 **Fallo 8.2** — Credenciales demo (`admin@fastplay.es / admin1234`, `demo@fastplay.es / demo1234`) están publicadas en README y se siembran en cada arranque de la BD. Bien para dev, pero en `Database::seed()` no hay flag `APP_ENV=production` que las desactive. Añadir guard `if (!defined('APP_ENV') || APP_ENV !== 'production')`.

🟡 **Fallo 8.3** — `login_attempts` no tiene índices. Las consultas filtran por `(ip, email, attempted_at)`. Añadir:
```sql
CREATE INDEX IF NOT EXISTS idx_login_attempts_email ON login_attempts(email, attempted_at);
CREATE INDEX IF NOT EXISTS idx_login_attempts_ip    ON login_attempts(ip,    attempted_at);
```

🟡 **Fallo 8.4** — `Database::seed()` inserta directamente en columnas latinas (`'césped'`, `'sintético'`) — perfectamente válido en SQLite UTF-8, pero conviene asegurarse de que el archivo `.php` se sirve UTF-8 (el `mb_internal_encoding('UTF-8')` ayuda, pero la cabecera `Content-Type` no se fija en HTML; depende del default de Apache).

🟡 **Fallo 8.5** — `app/views/chat/room.php` no recarga mensajes nuevos automáticamente (no hay polling ni WebSocket). El módulo se anuncia como *"Chat en vivo"* en README línea 67. Añadir polling sencillo (`fetch` cada N segundos) o etiquetarlo como "chat asincrónico" hasta v4.

🟡 **Fallo 8.6** — Mensajes de error de `MatchesController::create` filtran `'Necesitas pertenecer a un equipo'` cuando ya estás autenticado pero sin equipos. El mensaje redirige a `teams/create`, lo cual rompe el ciclo POST si el usuario ya estaba editando un partido. Considerar mostrar el aviso en la propia pantalla `matches/create` con un CTA en vez de redirección.

🟡 **Fallo 8.7** — Las contraseñas demo en `Database.php:201,203,216` son ≤ 10 caracteres y tipo `admin1234`. El propio modelo exige `min 8` en alta — están justas. Subir la barra del seed a algo como `Admin1234!` para coherencia con cualquier futura política reforzada.

🔵 **Fallo 8.8** — `app/views/legal/terms.php:16` dice *"Última actualización: mayo de 2026"*. Si el documento es estático, no pasa nada; si se actualiza, mover a un valor configurable.

---

## 9. Prioridad sugerida

1. **Sprint 1 (seguridad / blocker)**: 4.1, 4.2, 1.1, 5.1, 8.1.
2. **Sprint 2 (limpieza de assets)**: 2.1, 2.2, 2.3, 2.4, 3.1, 3.3.
3. **Sprint 3 (consistencia README)**: 7.1, 7.2, 7.3, 7.4, 7.6.
4. **Backlog**: el resto.

---

_Generado tras una auditoría manual de `/`, `/app`, `/config`, `/public`, `/storage`, `/uploads`. Para reproducir, comparar este documento con el repositorio en el commit `3fc0df9`._
