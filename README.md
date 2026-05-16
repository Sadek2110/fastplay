<div align="center">

# ⚽ FastPlay — Fútbol Amateur Organizado

**_"Fútbol callejero, organizado."_**

Plataforma web para la gestión de ligas, equipos y partidos de fútbol amateur.

![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php&logoColor=white)
![SQLite](https://img.shields.io/badge/SQLite-3-003B57?logo=sqlite&logoColor=white)
![Apache](https://img.shields.io/badge/Apache-2.4-D22128?logo=apache&logoColor=white)
![Architecture](https://img.shields.io/badge/Arquitectura-MVC-success)
![Status](https://img.shields.io/badge/estado-v3-16a34a)
![License](https://img.shields.io/badge/licencia-uso%20académico-lightgrey)

</div>

---

## 📑 Tabla de Contenidos

1. [Descripción](#-descripción)
2. [Características Principales](#-características-principales)
3. [Stack Tecnológico](#️-stack-tecnológico)
4. [Arquitectura](#️-arquitectura)
5. [Estructura del Proyecto](#-estructura-del-proyecto)
6. [Modelo de Datos](#-modelo-de-datos)
7. [Roles y Permisos](#-roles-y-permisos)
8. [Mapa de Rutas](#-mapa-de-rutas)
9. [Instalación y Configuración](#️-instalación-y-configuración)
10. [Credenciales Demo](#-credenciales-demo)
11. [Seguridad](#-seguridad)
12. [Identidad Visual y Diseño](#-identidad-visual-y-diseño)
13. [Convenciones de Código](#-convenciones-de-código)
14. [Roadmap](#️-roadmap)
15. [Notas de Versión](#-notas-de-versión-v3)
16. [Licencia y Créditos](#-licencia-y-créditos)

---

## 🎯 Descripción

**FastPlay** es una aplicación web completa desarrollada en **PHP 8 (MVC)** que permite a jugadores y capitanes organizar partidos, inscribirse en ligas y gestionar sus estadísticas. Está pensada como un puente entre la pachanga improvisada de barrio y las ligas semi-profesionales: rápida de usar, sin fricción y con todas las herramientas de gestión que un capitán necesita.

La plataforma combina una experiencia inmersiva (animaciones de scroll, estética _glass-and-neon-green_ inspirada en un estadio nocturno) con un núcleo robusto: router propio, capa de datos PDO, sesiones endurecidas, protección CSRF y migraciones automáticas en SQLite.

---

## 🚀 Características Principales

### 🏆 Gestión de Ligas
Dos niveles de competición claramente diferenciados:
- **Liga Pro** — _Tier_ premium con árbitros, estadísticas completas, calendario oficial y premios en metálico.
- **Liga Amistosa** — _Tier_ gratuito para pachangas, retos rápidos y partidos negociados entre capitanes.

### 📅 Partidos y Campos
- Reservas de campos por franja horaria.
- Gestión de convocatorias (titulares, suplentes, bajas).
- Resultados en vivo y estados de partido: **Pendiente**, **Confirmado**, **En Curso**, **Finalizado**.
- Histórico de partidos por equipo y por jugador.

### 👥 Equipos y Jugadores
- Perfiles públicos con avatar, biografía y estadísticas (goles, asistencias, tarjetas amarillas/rojas).
- Plantillas con rol (capitán, titular, suplente).
- Sistema de invitaciones y solicitudes de ingreso.

### 💬 Chat en Vivo
- Salas de chat por equipo y por liga.
- Negociación de partidos amistosos entre capitanes (chat 1:1).

### 🎖️ Sistema de Logros
Gamificación mediante medallas y trofeos por hitos alcanzados (primer gol, hat-trick, asistencia perfecta, ligas ganadas, etc.).

### 🛠️ Panel de Administración
Gestión total de la plataforma: usuarios, equipos, ligas, campos, sanciones y moderación de chats.

### ✨ Experiencia Inmersiva
Animación de scroll basada en secuencias de imágenes (canvas) en la _landing page_, con paralaje y revelado progresivo de bloques.

---

## 🛠️ Stack Tecnológico

| Capa | Tecnología | Detalles |
|---|---|---|
| **Backend** | PHP 8.x | Arquitectura MVC personalizada, sin _framework_ externo |
| **Base de Datos** | SQLite 3 | Auto-migración + _seeding_ de datos demo |
| **Acceso a Datos** | PDO | _Prepared statements_, _bindings_ tipados |
| **Frontend** | Vanilla CSS 3 + JS | _Design System_ propio, sin dependencias |
| **Tipografía** | Inter (variable) | Cargada localmente |
| **Servidor** | Apache 2.4 | `mod_rewrite` + `.htaccess` por carpeta |
| **Animaciones** | Canvas 2D | Secuencias de imágenes en _scroll_ |

---

## 🏗️ Arquitectura

FastPlay sigue un patrón **MVC clásico** con un único punto de entrada (_Front Controller_):

```
Browser ──► .htaccess ──► public/index.php ──► Router ──► Controller ──► Model ──► Database
                                                  │            │
                                                  └────────────┴──► View (PHP + Layout + Partials)
```

- **`public/index.php`** es el único archivo accesible desde la web. Todo el resto del proyecto vive fuera del _document root_ lógico (protegido por `.htaccess`).
- **`Router`** mapea verbos HTTP + rutas a `Controller@accion`.
- **`Controller`** orquesta la petición, valida CSRF y delega en los `Model`s.
- **`Model`** encapsula consultas SQL preparadas y reglas de negocio.
- **`View`** se renderiza dentro de un `layout` con `partials` reutilizables (header, footer, flash messages).

---

## 📂 Estructura del Proyecto

```text
FastPlay_v3/
├── app/                          # Lógica de la aplicación (MVC)
│   ├── .htaccess                 # Bloquea acceso directo
│   ├── controllers/              # Controladores HTTP
│   │   ├── AdminController.php       # Panel de administración
│   │   ├── AuthController.php        # Login, registro, logout
│   │   ├── CamposController.php      # Gestión de campos
│   │   ├── ChatController.php        # Mensajería
│   │   ├── DashboardController.php   # Home autenticado
│   │   ├── HomeController.php        # Landing pública
│   │   ├── LeaguesController.php     # Ligas Pro / Amistosa
│   │   ├── LegalController.php       # Avisos legales y privacidad
│   │   ├── MatchesController.php     # Partidos
│   │   ├── ProfileController.php     # Perfil de usuario
│   │   └── TeamsController.php       # Equipos y plantillas
│   ├── core/                     # Núcleo del framework
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
│       ├── teams/  leagues/  matches/  campos/  chat/  profile/  admin/  legal/  errors/
├── config/
│   ├── .htaccess                 # Bloquea acceso directo
│   └── config.php                # Constantes globales, BASE_URL, sesión, CSP
├── public/                       # ÚNICO directorio expuesto a la web
│   ├── .htaccess                 # Front controller + cabeceras
│   ├── css/                      # app.css + scroll-anim.css
│   ├── js/                       # scroll-anim.js
│   ├── images/                   # Assets gráficos (hero-pitch, etc.)
│   ├── frames/                   # Ignorado por git (assets generados localmente)
│   └── index.php                 # Front Controller
├── storage/                      # SQLite (ignorado por git)
│   └── .htaccess
├── uploads/                      # Archivos subidos por usuarios (futuro)
│   └── .htaccess                 # Bloquea ejecución de scripts
├── .htaccess                     # Reescritura raíz → public/
├── .gitignore                    # Ignora SQLite, uploads de usuario, IDE
└── README.md                     # Este archivo
```

---

## 🗄️ Modelo de Datos

Entidades principales y sus relaciones:

| Entidad | Descripción | Relaciones clave |
|---|---|---|
| **Usuario** | Jugador, capitán o admin. Guarda credenciales, perfil y stats. | `1:N` con Equipo (como miembro), `1:N` con Partido (como participante) |
| **Equipo** | Plantilla con capitán y miembros. | `N:1` con Usuario (capitán), `N:M` con Liga |
| **Liga** | Competición Pro o Amistosa. | `1:N` con Equipo (inscripciones), `1:N` con Partido |
| **Partido** | Encuentro entre dos equipos en un campo. | `N:1` con Liga, `N:1` con Campo, `N:M` con Usuario (convocatoria) |
| **Campo** | Recinto físico reservable. | `1:N` con Partido |
| **Chat** | Mensajes y salas. | `N:1` con Usuario (autor), agrupados por sala |
| **Logro** | Medallas desbloqueadas por el jugador. | `N:1` con Usuario |

Las migraciones y el _seeding_ inicial se ejecutan automáticamente en el primer arranque desde [`app/core/Database.php`](app/core/Database.php).

---

## 👤 Roles y Permisos

| Rol | Permisos |
|---|---|
| **Visitante** | Ver _landing_, ligas públicas, equipos y resultados finalizados. |
| **Jugador** | Todo lo anterior + perfil propio, unirse a equipo, chatear, ver convocatorias. |
| **Capitán** | Todo lo anterior + crear/gestionar equipo, inscribir en ligas, aceptar retos, gestionar plantilla. |
| **Admin** | Acceso total: CRUD de usuarios, ligas, campos, equipos, moderación de chats y sanciones. |

---

## 🗺️ Mapa de Rutas

El router resuelve siempre `/{controlador}/{acción}/{parametros...}`. La ruta raíz `/` apunta a `HomeController@index`.

| Método | Ruta | Controlador@acción | Descripción |
|---|---|---|---|
| `GET` | `/` | `HomeController@index` | Landing pública |
| `GET/POST` | `/auth/login` | `AuthController@login` | Inicio de sesión |
| `GET/POST` | `/auth/register` | `AuthController@register` | Registro |
| `POST` | `/auth/logout` | `AuthController@logout` | Cierre de sesión |
| `GET` | `/dashboard` | `DashboardController@index` | Panel del usuario |
| `GET` | `/leagues` | `LeaguesController@index` | Listado de ligas |
| `GET` | `/leagues/show/{id}` | `LeaguesController@show` | Detalle de liga |
| `POST` | `/leagues/register/{id}` | `LeaguesController@register` | Inscribir equipo en liga |
| `GET` | `/teams` | `TeamsController@index` | Listado de equipos |
| `GET/POST` | `/teams/create` | `TeamsController@create` | Crear equipo (capitán) |
| `GET` | `/teams/show/{id}` | `TeamsController@show` | Detalle de equipo |
| `GET` | `/matches` | `MatchesController@index` | Listado de partidos |
| `GET` | `/matches/show/{id}` | `MatchesController@show` | Detalle de partido |
| `GET/POST` | `/matches/create` | `MatchesController@create` | Crear partido (capitán) |
| `POST` | `/matches/confirm/{id}` | `MatchesController@confirm` | Confirmar partido |
| `POST` | `/matches/cancel/{id}` | `MatchesController@cancel` | Cancelar partido |
| `POST` | `/matches/finish/{id}` | `MatchesController@finish` | Finalizar partido |
| `GET` | `/campos` | `CamposController@index` | Listado de campos |
| `GET` | `/chat` | `ChatController@index` | Listado de salas |
| `GET/POST` | `/chat/room/{id}` | `ChatController@room` | Sala de chat |
| `GET` | `/profile` | `ProfileController@index` | Perfil propio |
| `GET/POST` | `/profile/edit` | `ProfileController@edit` | Editar perfil |
| `GET/POST` | `/profile/password` | `ProfileController@password` | Cambiar contraseña |
| `GET` | `/admin` | `AdminController@index` | Dashboard admin |
| `GET` | `/admin/users` | `AdminController@users` | Gestión de usuarios |
| `POST` | `/admin/setRole/{id}` | `AdminController@setRole` | Asignar rol |
| `POST` | `/admin/deleteUser/{id}` | `AdminController@deleteUser` | Eliminar usuario |
| `GET` | `/legal/terms` | `LegalController@terms` | Términos |
| `GET` | `/legal/privacy` | `LegalController@privacy` | Privacidad |
| `GET` | `/legal/cookies` | `LegalController@cookies` | Cookies |

> Las rutas se resuelven en [`app/core/Router.php`](app/core/Router.php). El despachador valida `^[a-zA-Z0-9_]+$` en controlador y acción, así que las acciones multi-palabra usan `camelCase` en la URL (ej. `setRole`, `deleteUser`).

---

## ⚙️ Instalación y Configuración

### Requisitos

- **Apache 2.4+** con `mod_rewrite` habilitado.
- **PHP 8.0** o superior con las extensiones:
  - `pdo_sqlite`
  - `mbstring`
  - `fileinfo` (opcional, para futura validación de uploads)
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

> La aplicación detecta automáticamente la `BASE_URL` en función del subdirectorio donde esté instalada — no necesita configuración manual.

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
2. Ejecuta todas las migraciones (creación de tablas).
3. Inserta datos demo (_seeding_): usuarios, equipos, ligas y partidos de ejemplo.

Para resetear la base de datos basta con borrar el archivo `storage/fastplay.sqlite` y refrescar.

---

## 🔑 Credenciales Demo

| Rol | Email | Contraseña |
|---|---|---|
| **Admin** | `admin@fastplay.es` | `Admin1234!` |
| **Jugador** | `demo@fastplay.es` | `Demo1234!` |

> ⚠️ El _seeder_ sólo siembra datos demo si `APP_ENV` ≠ `production`. En producción, define `SetEnv APP_ENV production` (o variable de entorno equivalente) para garantizar que estas cuentas no se creen.

---

## 🔒 Seguridad

FastPlay aplica una capa de seguridad defensiva en profundidad:

- **CSRF** — Token por sesión validado en todos los formularios `POST`.
- **Sesiones endurecidas** — Cookies `HttpOnly`, `Secure` y `SameSite=Lax`; regeneración de ID tras login.
- **Hashing** — Contraseñas con `password_hash()` (bcrypt) + verificación con `password_verify()`.
- **Rate limiting** — Limitación de intentos de login para prevenir _brute force_.
- **SQL Injection** — 100% _prepared statements_ vía PDO; sin concatenación de SQL.
- **XSS** — Escape sistemático con `htmlspecialchars()` en vistas + **CSP restrictiva**.
- **Headers de seguridad** — `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`, `Content-Security-Policy`.
- **Aislamiento del _document root_** — Solo `public/` es accesible; `app/`, `config/`, `storage/` están bloqueadas por `.htaccess`. La carpeta `uploads/` está expuesta pero **no permite ejecutar scripts** (PHP/CGI/etc. se bloquean por `<FilesMatch>`).
- **Subida de archivos** — Pendiente de implementación. Cuando se implemente (avatars, logos), debe validar MIME real con `finfo`, extensión por _whitelist_ y guardarse fuera de `public/` o con nombre regenerado.

> Las cabeceras de seguridad se envían desde [`config/config.php`](config/config.php) (función `security_headers()`). Las reglas de Apache viven en los `.htaccess` por carpeta.

---

## 🎨 Identidad Visual y Diseño

### Concepto de Marca
FastPlay trata cada pantalla como un **partido nocturno bajo focos**. La identidad utiliza un fondo casi negro (`#060d09`), tarjetas de "cristal" translúcidas, un verde neón confiado (`#16a34a`) para acciones primarias y un dorado (`#fbbf24`) reservado **exclusivamente para la Liga Pro**.

### Paleta

| Color | Hex | Uso |
|---|---|---|
| ⬛ Estadio | `#060d09` | Fondo principal |
| 🟢 Neón | `#16a34a` | Acciones primarias, énfasis |
| 🟡 Dorado | `#fbbf24` | Liga Pro, premios |
| ⬜ Glass | `rgba(255,255,255,0.04)` | Tarjetas y superficies |
| 🔘 Texto | `#e5e7eb` | Texto principal |

### Tipografía y Tono
- **Fuente**: Inter (cargas variables, peso `Black 900` para titulares).
- **Voz**: Informal (_"Tú"_), directa y deportiva. Los botones siempre terminan en ` →`.
- **Iconografía**: Uso deliberado de **emojis** como iconografía de marca y **SVG inline** para utilidades funcionales.

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

## 📐 Convenciones de Código

- **PHP** — `PSR-12` flexible, _strict types_ donde aplica. Por simplicidad **no se usan namespaces**: las clases (`Controller`, `Router`, `Database`, modelos y controladores) viven en el espacio global y se cargan con `require_once`.
- **Nombres** — Controladores y modelos en `PascalCase`; métodos en `camelCase`; variables y vistas en `snake_case`.
- **Vistas** — Solo PHP + HTML; **nunca** lógica de negocio. Cualquier dato debe llegar pre-procesado desde el controlador.
- **CSS** — Variables CSS (`--neon`, `--glass-bg`...) en `:root`. Componentes prefijados (`.btn-`, `.card-`, `.lg-` para liga).
- **JS** — Vanilla, sin _bundlers_. Un archivo por _feature_ (`scroll-anim.js`).
- **Commits** — Mensajes en español, modo imperativo (`Arregla`, `Añade`, `Refactoriza`).

---

## 🛣️ Roadmap

### ✅ v3 (actual)
- Refactor profundo del _router_.
- Endurecimiento de sesiones y CSP.
- Extracción de animaciones de scroll a archivos externos.
- Auditoría de seguridad en curso (ver [`arreglos.md`](arreglos.md)).

### 🔜 v4 (planeado)
- [ ] API REST para cliente móvil.
- [ ] Notificaciones _push_ vía _service workers_.
- [ ] Estadísticas avanzadas (xG, mapas de calor).
- [ ] Sistema de pagos para Liga Pro (Stripe).
- [ ] _Live scoring_ vía WebSockets.
- [ ] PWA instalable.

---

## 📝 Notas de Versión (v3)

Esta versión incluye:

- **Refactorización profunda del sistema de rutas** — Router más expresivo y mantenible.
- **Correcciones críticas en seguridad de sesiones** — Regeneración de ID, cookies endurecidas, _path_ acotado a la sub-instalación.
- **Extracción de animaciones a archivos externos** — `scroll-anim.css`, `scroll-anim.js` y `home-init.js` viven en `public/`. Ya no quedan bloques `<style>`/`<script>` _inline_ críticos en `home/index.php`.
- **Autorización en partidos** — Crear, confirmar, cancelar y finalizar partidos exige ser capitán de uno de los equipos (o admin).
- **Endurecimiento del panel admin** — No se puede eliminar ni degradar al último administrador.
- **Limpieza de assets** — Frames del scroll movidos a `public/frames/`; archivos huérfanos eliminados.
- **`.gitignore` añadido** — Excluye SQLite, _journal_, logs y subidas de usuario.

> El historial completo de cambios vive en `git log`. Las correcciones detectadas durante auditoría se enumeran en [`arreglos.md`](arreglos.md).

---

## 📄 Licencia y Créditos

**Proyecto académico** desarrollado como parte del trabajo de fin de ciclo / portfolio personal.

- **Autor**: [Sadek2110](https://github.com/Sadek2110)
- **Tipografía**: [Inter](https://rsms.me/inter/) — Rasmus Andersson
- **Inspiración visual**: Estética _glass-and-neon_ de UIs deportivas modernas

> Si reutilizas el código, una mención al repositorio original es bienvenida. ⚽

---

<div align="center">

**FastPlay** — _Fútbol callejero, organizado_ →

</div>
