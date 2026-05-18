# Fastplay — Memoria del proyecto

## Identidad
- **Nombre**: Fastplay (`fastplay/fastplay`)
- **Descripción**: Plataforma web para la gestión de ligas, equipos y partidos de fútbol amateur. "Fútbol callejero, organizado."
- **Versión**: v3
- **Idioma**: Español (UI, código, docs)
- **Licencia**: Uso académico / portfolio personal
- **Autor**: [Sadek2110](https://github.com/Sadek2110)

## Stack
| Capa | Tecnología |
|---|---|
| Backend | PHP >= 8.1 (sin framework, MVC propio) |
| Base de datos por defecto | SQLite 3 (`storage/fastplay.sqlite`) |
| Base de datos opcional | MySQL 8.0+ o PostgreSQL 13+ (vía `DB_DRIVER`) |
| Frontend | HTML + CSS + JS vanilla |
| Servidor | Apache 2.4 (.htaccess) o PHP built-in server |
| Tests | PHPUnit 10 (~140 tests, 225 assertions) |
| Contenedor | Docker (`php:8.2-apache`) |

## Despliegue

### Easypanel
El proyecto está desplegado en **Easypanel** usando el `Dockerfile` incluido. Easypanel construye la imagen, expone el puerto 80 y sirve la app con `APP_ENV=production`.

### Dockerfile
- Base: `php:8.2-apache`
- Extensiones: `pdo`, `pdo_sqlite`, `pdo_mysql`, `mbstring`
- Apache: `rewrite` habilitado, document root → `/var/www/html/public`
- `APP_ENV=production`
- `storage/` y `uploads/` con permisos `www-data:775`

### Local
- XAMPP (Apache) con subcarpeta → `.htaccess` redirige a `public/`
- PHP built-in: `php -S localhost:8000 router.php`

## Base de datos
- **Desarrollo local**: SQLite (`storage/fastplay.sqlite`, zero-config, auto-migración)
- **Producción (Easypanel)**: MySQL, gestionado vía phpMyAdmin
- **Migraciones**: Idempotentes (`CREATE TABLE IF NOT EXISTS` en `Database::migrate()`)
- **Seeder**: Datos demo en `development` si la tabla `users` está vacía
- **Credenciales demo**: `admin@fastplay.es / Admin1234!` y `demo@fastplay.es / Demo1234!`
- **12 tablas**: `users`, `teams`, `team_members`, `leagues`, `league_teams`, `fields`, `matches`, `chat_rooms`, `chat_messages`, `achievements`, `user_achievements`, `login_attempts`
- **Scripts de schema**: `database/fastplay_mysql.sql` (usado en producción) y `database/fastplay_postgres.sql` (alternativo)
- **Consistencia**: `repairConsistency()` arregla equipos huérfanos en ligas

## Arquitectura
```
Browser → public/index.php → Router → Controller → Model → Database (SQLite/PDO) → View + Layout
```
- MVC sin framework externo
- Front controller: `public/index.php`
- Router: `app/core/Router.php` — `/controller/action/param`
- Controller base: `app/core/Controller.php` — `view()`, `model()`, `requireAuth()`, `requireAdmin()`, `requirePost()`
- Modelos: Clases PHP con PDO estático vía `Database::all()`, `Database::one()`, etc.

## Seguridad
- CSRF en todos los POST
- Passwords con bcrypt (`password_hash`/`password_verify`)
- Rate limit de login (5 intentos fallidos → bloqueo)
- Sesiones endurecidas: `FPSESSID`, `HttpOnly`, `SameSite=Lax`, `Secure` si HTTPS, regeneración en login
- Headers: CSP, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy
- Escapado de salida con helper `e()`
- `.htaccess` bloquea acceso directo a `.env`, `.sqlite`, `.md`, `.sql`, `.json`
- **Pendiente**: CSP aún usa `unsafe-inline` para estilos/scripts

## Módulos y rutas
| Módulo | Controlador | Modelo |
|---|---|---|
| Home / Landing | `HomeController` | — |
| Autenticación | `AuthController` | `Usuario` |
| Dashboard | `DashboardController` | `Usuario` |
| Perfil | `ProfileController` | `Usuario` |
| Equipos | `TeamsController` | `Equipo` |
| Ligas | `LeaguesController` | `Liga` |
| Partidos | `MatchesController` | `Partido` |
| Campos | `CamposController` | `Campo` |
| Chat | `ChatController` | `Chat` |
| Admin | `AdminController` | Varios |
| Legal | `LegalController` | — |

## Roles
| Rol | Capacidades |
|---|---|
| Visitante | Ver landing, ligas, equipos, registrarse, login |
| Jugador | Dashboard, perfil, crear equipo, unirse, crear partidos, chat |
| Capitán | Todo lo anterior + gestionar equipo, inscribir en ligas, confirmar partidos |
| Admin | Todo + panel admin, crear ligas/campos, gestionar usuarios |

## Configuración
- **Archivo**: `config/config.php`
- **APP_ENV**: `development` (default) o `production`
- **DB_DRIVER**: `sqlite` (default), `mysql`, `pgsql`
- **BASE_URL**: Auto-detectada desde `SCRIPT_NAME`
- **Zona horaria**: `Europe/Madrid`
- **Sesiones**: `storage/sessions/`
- **Uploads**: `uploads/`

## Testing
- PHPUnit 10 con `phpunit.xml`
- Base de datos aislada: `storage/fastplay_test.sqlite`
- Bootstrap: `tests/bootstrap.php`
- 10 archivos de test, ~170 métodos
- Comando: `vendor/bin/phpunit --configuration phpunit.xml`

## Roadmap (próximo — v4)
- API REST para cliente móvil
- Notificaciones push (service workers)
- Estadísticas avanzadas (xG, mapas de calor)
- Pagos Stripe para Liga Pro
- Live scoring vía WebSockets
- PWA instalable
- Migración real a PostgreSQL
- Eliminar `unsafe-inline` del CSP
