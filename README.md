<div align="center">

# FastPlay - Futbol Amateur Organizado

**"Futbol callejero, organizado."**

Plataforma web para la gestion de ligas, equipos y partidos de futbol amateur.

![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-3-003B57?logo=sqlite&logoColor=white)
![Apache](https://img.shields.io/badge/Apache-2.4-D22128?logo=apache&logoColor=white)
![Architecture](https://img.shields.io/badge/Arquitectura-MVC-success)
![Status](https://img.shields.io/badge/estado-v3-16a34a)
![License](https://img.shields.io/badge/licencia-uso%20academico-lightgrey)

</div>

---

> Guia ampliada del proyecto: [GUIA_PROYECTO.md](GUIA_PROYECTO.md)

---

## Tabla de Contenidos

1. [Descripcion](#descripcion)
2. [Caracteristicas Principales](#caracteristicas-principales)
3. [Stack Tecnologico](#stack-tecnologico)
4. [Arquitectura](#arquitectura)
5. [Estructura del Proyecto](#estructura-del-proyecto)
6. [Modelo de Datos](#modelo-de-datos)
7. [Roles y Permisos](#roles-y-permisos)
8. [Mapa de Rutas](#mapa-de-rutas)
9. [Instalacion y Configuracion](#instalacion-y-configuracion)
10. [Credenciales Demo](#credenciales-demo)
11. [Seguridad](#seguridad)
12. [Identidad Visual y Diseno](#identidad-visual-y-diseno)
13. [Convenciones de Codigo](#convenciones-de-codigo)
14. [Tests](#tests)
15. [Roadmap](#roadmap)
16. [Notas de Version](#notas-de-version-v3)
17. [Licencia y Creditos](#licencia-y-creditos)

---

## Descripcion

**FastPlay** es una aplicacion web completa desarrollada en **PHP 8 (MVC)** que permite a jugadores y capitanes organizar partidos, inscribirse en ligas y gestionar sus estadisticas. Esta pensada como un puente entre la pachanga improvisada de barrio y las ligas semi-profesionales: rapida de usar, sin friccion y con todas las herramientas de gestion que un capitan necesita.

La plataforma combina una experiencia inmersiva (animaciones de scroll, estetica _glass-and-neon-green_ inspirada en un estadio nocturno) con un nucleo robusto: router propio, capa de datos PDO, sesiones endurecidas, proteccion CSRF y migraciones automaticas en SQLite.

---

## Caracteristicas Principales

### Gestion de Ligas
Dos niveles de competicion claramente diferenciados:
- **Liga Pro** - Tier premium con arbitros, estadisticas completas, calendario oficial y premios en metalico.
- **Liga Amistosa** - Tier gratuito para pachangas, retos rapidos y partidos negociados entre capitanes.

### Partidos y Campos
- Reservas de campos por franja horaria.
- Gestion de convocatorias (titulares, suplentes, bajas).
- Resultados en vivo y estados de partido: **Pendiente**, **Confirmado**, **En Curso**, **Finalizado**.
- Historico de partidos por equipo y por jugador.

### Equipos y Jugadores
- Perfiles publicos con avatar, biografia y estadisticas (goles, asistencias, tarjetas amarillas/rojas).
- Plantillas con rol (capitan, titular, suplente).
- Sistema de invitaciones y solicitudes de ingreso.

### Chat en Vivo
- Salas de chat por equipo y por liga.
- Negociacion de partidos amistosos entre capitanes (chat 1:1).

### Sistema de Logros
Gamificacion mediante medallas y trofeos por hitos alcanzados (primer gol, hat-trick, asistencia perfecta, ligas ganadas, etc.).

### Panel de Administracion
Gestion total de la plataforma: usuarios, equipos, ligas, campos, sanciones y moderacion de chats.

### Experiencia Inmersiva
Animacion de scroll basada en video en la _landing page_, con paralaje y revelado progresivo de bloques.

---

## Stack Tecnologico

| Capa | Tecnologia | Detalles |
|---|---|---|
| **Backend** | PHP 8.x | Arquitectura MVC personalizada, sin framework externo |
| **Base de Datos** | SQLite 3 | Auto-migracion + seeding de datos demo |
| **Acceso a Datos** | PDO | Prepared statements, bindings tipados |
| **Frontend** | Vanilla CSS 3 + JS | Design System propio, sin dependencias |
| **Tipografia** | Inter (variable) | Cargada localmente |
| **Servidor** | Apache 2.4 | mod_rewrite + .htaccess por carpeta |
| **Animaciones** | Video + JS | Experiencia inmersiva en scroll |

---

## Arquitectura

FastPlay sigue un patron **MVC clasico** con un unico punto de entrada (Front Controller):

```text
Browser ---> .htaccess ---> public/index.php ---> Router ---> Controller ---> Model ---> Database
                                                    |              |
                                                    +------------> View (PHP + Layout + Partials)
```

- **`public/index.php`** es el unico archivo accesible desde la web. Todo el resto del proyecto vive fuera del document root logico (protegido por `.htaccess`).
- **`Router`** mapea verbos HTTP + rutas a `Controller@accion`.
- **`Controller`** orquesta la peticion, valida CSRF y delega en los `Model`s.
- **`Model`** encapsula consultas SQL preparadas y reglas de negocio.
- **`View`** se renderiza dentro de un `layout` con `partials` reutilizables (header, footer, flash messages).

---

## Estructura del Proyecto

```text
FastPlay_v3/
├── app/                          # Logica de la aplicacion (MVC)
│   ├── .htaccess                 # Bloquea acceso directo
│   ├── controllers/              # Controladores HTTP
│   │   ├── AdminController.php       # Panel de administracion
│   │   ├── AuthController.php        # Login, registro, logout
│   │   ├── CamposController.php      # Gestion de campos
│   │   ├── ChatController.php        # Mensajeria
│   │   ├── DashboardController.php   # Home autenticado
│   │   ├── HomeController.php        # Landing publica
│   │   ├── LeaguesController.php     # Ligas Pro / Amistosa
│   │   ├── LegalController.php       # Avisos legales y privacidad
│   │   ├── MatchesController.php     # Partidos
│   │   ├── ProfileController.php     # Perfil de usuario
│   │   └── TeamsController.php       # Equipos y plantillas
│   ├── core/                     # Nucleo del framework
│   │   ├── Controller.php            # Controlador base + render()
│   │   ├── Database.php              # PDO + migraciones + seeder
│   │   └── Router.php                # Despachador HTTP
│   ├── models/                   # Modelos de dominio
│   │   ├── Campo.php
│   │   ├── Chat.php
│   │   ├── Equipo.php
│   │   ├── Liga.php
│   │   ├── Partido.php
│   │   └── Usuario.php
│   └── views/                    # Vistas (PHP puro)
│       ├── layouts/                  # Layouts main / auth
│       ├── partials/                 # navbar, flash, footer
│       ├── home/                     # Landing
│       ├── auth/                     # Login / registro
│       ├── dashboard/                # Panel privado
│       └── teams/  leagues/  matches/  campos/  chat/  profile/  admin/  legal/  errors/
├── config/
│   ├── .htaccess                 # Bloquea acceso directo
│   └── config.php                # Constantes globales, BASE_URL, sesion, CSP
├── public/                       # UNICO directorio expuesto a la web
│   ├── .htaccess                 # Front controller + cabeceras
│   ├── css/                      # app.css + scroll-anim.css
│   ├── js/                       # scroll-anim.js
│   ├── images/                   # Assets graficos (hero-pitch, etc.)
│   ├── video/                    # Videos de la landing
│   └── index.php                 # Front Controller
├── database/                     # SQL auxiliar de referencia
│   └── fastplay_postgres.sql     # Esquema + seed opcional para PostgreSQL
├── storage/                      # SQLite (ignorado por git)
│   ├── sessions/                 # Sesiones PHP locales (ignorado por git)
│   └── .htaccess
├── tests/                        # PHPUnit
├── uploads/                      # Archivos subidos por usuarios (futuro)
│   └── .htaccess                 # Bloquea ejecucion de scripts
├── .htaccess                     # Reescritura raiz -> public/
├── .gitignore                    # Ignora SQLite, uploads de usuario, IDE
├── composer.json                 # Dependencias de desarrollo y autoload
├── phpunit.xml                   # Configuracion de tests
└── README.md                     # Este archivo
```

---

## Modelo de Datos

Entidades principales y sus relaciones:

| Entidad | Descripcion | Relaciones clave |
|---|---|---|
| **Usuario** | Jugador, capitan o admin. Guarda credenciales, perfil y stats. | `1:N` con Equipo (como miembro), `1:N` con Partido (como participante) |
| **Equipo** | Plantilla con capitan y miembros. | `N:1` con Usuario (capitan), `N:M` con Liga |
| **Liga** | Competicion Pro o Amistosa. | `1:N` con Equipo (inscripciones), `1:N` con Partido |
| **Partido** | Encuentro entre dos equipos en un campo. | `N:1` con Liga, `N:1` con Campo, `N:M` con Usuario (convocatoria) |
| **Campo** | Recinto fisico reservable. | `1:N` con Partido |
| **Chat** | Mensajes y salas. | `N:1` con Usuario (autor), agrupados por sala |
| **Logro** | Medallas desbloqueadas por el jugador. | `N:1` con Usuario |

Las migraciones y el seeding inicial se ejecutan automaticamente en el primer arranque desde [`app/core/Database.php`](app/core/Database.php).

---

## Roles y Permisos

| Rol | Permisos |
|---|---|
| **Visitante** | Ver landing, ligas publicas, equipos y resultados finalizados. |
| **Jugador** | Todo lo anterior + perfil propio, unirse a equipo, chatear, ver convocatorias. |
| **Capitan** | Todo lo anterior + crear/gestionar equipo, inscribir en ligas, aceptar retos, gestionar plantilla. |
| **Admin** | Acceso total: CRUD de usuarios, ligas, campos, equipos, moderacion de chats y sanciones. |

---

## Mapa de Rutas

El router resuelve siempre `/{controlador}/{accion}/{parametros...}`. La ruta raiz `/` apunta a `HomeController@index`.

| Metodo | Ruta | Controlador@accion | Descripcion |
|---|---|---|---|
| `GET` | `/` | `HomeController@index` | Landing publica |
| `GET/POST` | `/auth/login` | `AuthController@login` | Inicio de sesion |
| `GET/POST` | `/auth/register` | `AuthController@register` | Registro |
| `POST` | `/auth/logout` | `AuthController@logout` | Cierre de sesion |
| `GET` | `/dashboard` | `DashboardController@index` | Panel del usuario |
| `GET` | `/leagues` | `LeaguesController@index` | Listado de ligas |
| `GET` | `/leagues/show/{id}` | `LeaguesController@show` | Detalle de liga |
| `POST` | `/leagues/register/{id}` | `LeaguesController@register` | Inscribir equipo en liga |
| `GET` | `/teams` | `TeamsController@index` | Listado de equipos |
| `GET/POST` | `/teams/create` | `TeamsController@create` | Crear equipo (capitan) |
| `GET` | `/teams/show/{id}` | `TeamsController@show` | Detalle de equipo |
| `GET` | `/matches` | `MatchesController@index` | Listado de partidos |
| `GET` | `/matches/show/{id}` | `MatchesController@show` | Detalle de partido |
| `GET/POST` | `/matches/create` | `MatchesController@create` | Crear partido (capitan) |
| `POST` | `/matches/confirm/{id}` | `MatchesController@confirm` | Confirmar partido |
| `POST` | `/matches/cancel/{id}` | `MatchesController@cancel` | Cancelar partido |
| `POST` | `/matches/finish/{id}` | `MatchesController@finish` | Finalizar partido |
| `GET` | `/campos` | `CamposController@index` | Listado de campos |
| `GET` | `/chat` | `ChatController@index` | Listado de salas |
| `GET/POST` | `/chat/room/{id}` | `ChatController@room` | Sala de chat |
| `GET` | `/profile` | `ProfileController@index` | Perfil propio |
| `GET/POST` | `/profile/edit` | `ProfileController@edit` | Editar perfil |
| `GET/POST` | `/profile/password` | `ProfileController@password` | Cambiar contrasena |
| `GET` | `/admin` | `AdminController@index` | Dashboard admin |
| `GET` | `/admin/users` | `AdminController@users` | Gestion de usuarios |
| `POST` | `/admin/setRole/{id}` | `AdminController@setRole` | Asignar rol |
| `POST` | `/admin/deleteUser/{id}` | `AdminController@deleteUser` | Eliminar usuario |
| `GET` | `/legal/terms` | `LegalController@terms` | Terminos |
| `GET` | `/legal/privacy` | `LegalController@privacy` | Privacidad |
| `GET` | `/legal/cookies` | `LegalController@cookies` | Cookies |

> Las rutas se resuelven en [`app/core/Router.php`](app/core/Router.php). El despachador valida `^[a-zA-Z0-9_]+$` en controlador y accion, asi que las acciones multi-palabra usan `camelCase` en la URL (ej. `setRole`, `deleteUser`).

---

## Instalacion y Configuracion

### Requisitos

- **Apache 2.4+** con `mod_rewrite` habilitado.
- **PHP 8.0** o superior con las extensiones:
  - `pdo_sqlite`
  - `mbstring`
  - `fileinfo` (opcional, para futura validacion de uploads)
- Permisos de escritura en `storage/` y `uploads/`.

### Despliegue local con XAMPP

```bash
# 1. Clonar dentro de htdocs
cd /c/xampp/htdocs/
git clone <repo-url> FastPlay_v3

# 2. Asegurar permisos en directorios escribibles (Linux/Mac)
chmod -R 775 FastPlay_v3/storage FastPlay_v3/uploads

# 3. Arrancar Apache desde el panel de XAMPP

# 4. Visitar
#    http://localhost/FastPlay_v3/
```

> La aplicacion detecta automaticamente la `BASE_URL` en funcion del subdirectorio donde este instalada - no necesita configuracion manual.

### Despliegue con Docker

```bash
docker build -t fastplay .
docker run -p 8080:80 -e APP_ENV=production fastplay
```

### Servidor embebido de PHP (sin XAMPP)

```bash
php -S localhost:8000 router.php
```

### Primer arranque

En el primer acceso, [`app/core/Database.php`](app/core/Database.php):

1. Crea el archivo `storage/fastplay.sqlite` si no existe.
2. Ejecuta todas las migraciones (creacion de tablas).
3. Inserta datos demo (seeding): usuarios, equipos, ligas y partidos de ejemplo.

Para resetear la base de datos basta con borrar el archivo `storage/fastplay.sqlite` y refrescar.

### PostgreSQL opcional

La aplicacion funciona por defecto con SQLite. El archivo `database/fastplay_postgres.sql` es un script auxiliar para portar el esquema y los datos demo a PostgreSQL si se decide migrar mas adelante; no se carga automaticamente por la app actual.

El script mantiene las mismas reglas de dominio que el codigo PHP: roles `player/admin`, estados de partido `pending/confirmed/cancelled/finished`, superficies admitidas y equipos inscritos de forma coherente con sus partidos de liga.

---

## Credenciales Demo

| Rol | Email | Contrasena |
|---|---|---|
| **Admin** | `admin@fastplay.es` | `Admin1234!` |
| **Jugador** | `demo@fastplay.es` | `Demo1234!` |

> El seeder solo siembra datos demo si `APP_ENV` es distinto de `production`. En produccion, define `SetEnv APP_ENV production` (o variable de entorno equivalente) para garantizar que estas cuentas no se creen.

---

## Seguridad

FastPlay aplica una capa de seguridad defensiva en profundidad:

- **CSRF** - Token por sesion validado en todos los formularios `POST`.
- **Sesiones endurecidas** - Cookies `HttpOnly`, `Secure` y `SameSite=Lax`; regeneracion de ID tras login; almacenamiento local en `storage/sessions/` para evitar permisos rotos de XAMPP.
- **Hashing** - Contrasenas con `password_hash()` (bcrypt) + verificacion con `password_verify()`.
- **Rate limiting** - Limitacion de intentos de login para prevenir brute force.
- **SQL Injection** - 100% prepared statements via PDO; sin concatenacion de SQL.
- **XSS** - Escape sistematico con `htmlspecialchars()` en vistas + **CSP restrictiva**.
- **Headers de seguridad** - `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Content-Security-Policy`.
- **Aislamiento del document root** - Solo `public/` es accesible; `app/`, `config/`, `storage/` estan bloqueadas por `.htaccess`. La carpeta `uploads/` esta expuesta pero **no permite ejecutar scripts** (PHP/CGI/etc. se bloquean por `<FilesMatch>`).
- **Subida de archivos** - Pendiente de implementacion. Cuando se implemente (avatars, logos), debe validar MIME real con `finfo`, extension por whitelist y guardarse fuera de `public/` o con nombre regenerado.

> Las cabeceras de seguridad se envian desde [`config/config.php`](config/config.php) (funcion `security_headers()`). Las reglas de Apache viven en los `.htaccess` por carpeta.

---

## Identidad Visual y Diseno

### Concepto de Marca
FastPlay trata cada pantalla como un **partido nocturno bajo focos**. La identidad utiliza un fondo casi negro (`#060d09`), tarjetas de "cristal" translucidas, un verde neon confiado (`#16a34a`) para acciones primarias y un dorado (`#fbbf24`) reservado **exclusivamente para la Liga Pro**.

### Paleta

| Color | Hex | Uso |
|---|---|---|
| Estadio | `#060d09` | Fondo principal |
| Neon | `#16a34a` | Acciones primarias, enfasis |
| Dorado | `#fbbf24` | Liga Pro, premios |
| Glass | `rgba(255,255,255,0.04)` | Tarjetas y superficies |
| Texto | `#e5e7eb` | Texto principal |

### Tipografia y Tono
- **Fuente**: Inter (cargas variables, peso `Black 900` para titulares).
- **Voz**: Informal ("Tu"), directa y deportiva. Los botones siempre terminan en ` ->`.
- **Iconografia**: Uso de texto limpio y SVG inline para utilidades funcionales, evitando el uso de emojis y caracteres que puedan romperse.

### Superficies (Glassmorphism)

```css
.glass {
  background: rgba(255, 255, 255, 0.04);
  backdrop-filter: blur(16px);
  border: 1px solid rgba(255, 255, 255, 0.08);
  border-radius: 16px;
}
```

---

## Convenciones de Codigo

- **PHP** - `PSR-12` flexible, strict types donde aplica. Por simplicidad **no se usan namespaces**: las clases (`Controller`, `Router`, `Database`, modelos y controladores) viven en el espacio global y se cargan con `require_once`.
- **Nombres** - Controladores y modelos en `PascalCase`; metodos en `camelCase`; variables y vistas en `snake_case`.
- **Vistas** - Solo PHP + HTML; **nunca** logica de negocio. Cualquier dato debe llegar pre-procesado desde el controlador.
- **CSS** - Variables CSS (`--neon`, `--glass-bg`...) en `:root`. Componentes prefijados (`.btn-`, `.card-`, `.lg-` para liga).
- **JS** - Vanilla, sin bundlers. Un archivo por feature (`scroll-anim.js`).
- **Commits** - Mensajes en espanol, modo imperativo (`Arregla`, `Anade`, `Refactoriza`).

---

## Tests

El proyecto incluye una configuracion basica de PHPUnit para proteger helpers, configuracion y consistencia de datos.

```bash
composer install
vendor/bin/phpunit --configuration phpunit.xml
```

La suite usa `storage/fastplay_test.sqlite` y se reinicia desde `tests/bootstrap.php`; no toca la base demo `storage/fastplay.sqlite`.

---

## Roadmap

### v3 (actual)
- Refactor profundo del router.
- Endurecimiento de sesiones y CSP.
- Extraccion de animaciones de scroll a archivos externos.
- Auditoria de seguridad en curso.

### v4 (planeado)
- [ ] API REST para cliente movil.
- [ ] Notificaciones push via service workers.
- [ ] Estadisticas avanzadas (xG, mapas de calor).
- [ ] Sistema de pagos para Liga Pro (Stripe).
- [ ] Live scoring via WebSockets.
- [ ] PWA instalable.

---

## Notas de Version (v3)

Esta version incluye:

- **Refactorizacion profunda del sistema de rutas** - Router mas expresivo y mantenible.
- **Correcciones criticas en seguridad de sesiones** - Regeneracion de ID, cookies endurecidas, path acotado a la sub-instalacion y `session.save_path` propio dentro de `storage/sessions/`.
- **Extraccion de animaciones a archivos externos** - `scroll-anim.css`, `scroll-anim.js` y `home-init.js` viven en `public/`. Ya no quedan bloques `<style>`/`<script>` inline criticos en `home/index.php`.
- **Autorizacion en partidos** - Crear, confirmar, cancelar y finalizar partidos exige ser capitan de uno de los equipos (o admin).
- **Endurecimiento del panel admin** - No se puede eliminar ni degradar al ultimo administrador.
- **Limpieza de assets** - La animacion de scroll usa `public/video/hero.webm`; archivos huerfanos eliminados.
- **Reparacion de consistencia de datos** - Los equipos que aparecen en partidos de liga quedan inscritos automaticamente en esa liga.
- **Tests de regresion** - PHPUnit cubre helpers, configuracion, credenciales demo y coherencia de liga/partidos.
- **Rate limit corregido** - Las ventanas temporales usan UTC (`gmdate`) para casar con `datetime('now')` de SQLite.
- **`.gitignore` anadido** - Excluye SQLite, journal, logs, sesiones, caches, `vendor/` y subidas de usuario.

> El historial completo de cambios vive en `git log`.

---

## Licencia y Creditos

**Proyecto academico** desarrollado como parte del trabajo de fin de ciclo / portfolio personal.

- **Autor**: [Sadek2110](https://github.com/Sadek2110)
- **Tipografia**: [Inter](https://rsms.me/inter/) - Rasmus Andersson
- **Inspiracion visual**: Estetica glass-and-neon de UIs deportivas modernas

> Si reutilizas el codigo, una mencion al repositorio original es bienvenida.

---

<div align="center">

**FastPlay** - _Futbol callejero, organizado_ ->

</div>
