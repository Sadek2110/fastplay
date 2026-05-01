# ⚽ FastPlay

Plataforma web para la gestión de **fútbol amateur**: equipos, ligas, partidos, estadísticas y chat entre jugadores.

---

## 📋 Tabla de contenidos

- [Características](#-características)
- [Requisitos](#️-requisitos)
- [Instalación](#-instalación)
- [Estructura del proyecto](#-estructura-del-proyecto)
- [Roles de usuario](#-roles-de-usuario)
- [Base de datos](#-base-de-datos)
- [Rutas disponibles](#-rutas-disponibles)
- [Tecnologías](#-tecnologías)

---

## ✨ Características

- Registro e inicio de sesión (email o teléfono)
- Gestión de equipos (creación, capitanes, plantillas)
- Ligas amistosas y profesionales con clasificación
- Partidos con alineaciones y estadísticas individuales
- Chat en tiempo real entre equipos y jugadores
- Panel de administración completo
- Logros y sistema de reputación
- Sanciones y fair play
- Diseño responsive con tema oscuro

---

## 🛠️ Requisitos

| Software        | Versión mínima |
|-----------------|----------------|
| PHP             | 8.1+           |
| MySQL / MariaDB | 8.0 / 10.5+    |
| Servidor web    | Apache (con mod_rewrite) o Nginx |
| Extensiones PHP | `pdo_mysql`, `mbstring`, `json` |

> Compatible con **XAMPP** y **Laragon** en Windows.

---

## 🚀 Instalación

### 1. Clonar o descargar el proyecto

Coloca la carpeta `FastPlay` dentro de tu directorio de proyectos web:

```
# XAMPP
C:\xampp\htdocs\Proyectos\FastPlay

# Laragon
C:\laragon\www\FastPlay
```

### 2. Configurar la base de datos

Importa el archivo SQL:

```bash
mysql -u root -p < database/fastplay.sql
```

O bien, abre phpMyAdmin y ejecuta el contenido de `database/fastplay.sql`.

### 3. Configurar credenciales

Edita `config/config.php` si tu configuración de base de datos difiere de los valores por defecto:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'fastplay');
define('DB_USER', 'root');
define('DB_PASS', '');
```

### 4. Configurar la URL base

En el mismo archivo, ajusta `APP_URL` a la ruta donde se encuentre el proyecto:

```php
define('APP_URL', 'http://localhost/Proyectos/FastPlay');
```

### 5. Iniciar el servidor

Inicia Apache y MySQL desde el panel de XAMPP/Laragon y accede a:

```
http://localhost/Proyectos/FastPlay
```

---

## 📁 Estructura del proyecto

```
FastPlay/
├── index.php               # Punto de entrada y registro de rutas
├── .htaccess               # Reescritura de URLs para Apache
├── README.md               # Documentación
│
├── config/
│   └── config.php          # Configuración global (BD, URLs, constantes)
│
├── core/
│   ├── Controller.php      # Clase base de controladores
│   ├── Model.php           # Clase base de modelos (CRUD genérico)
│   ├── Database.php        # Singleton de conexión PDO
│   └── Router.php          # Enrutador con soporte de parámetros
│
├── app/
│   ├── controllers/        # Controladores de la aplicación
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── ChatController.php
│   │   ├── HomeController.php
│   │   ├── LeagueController.php
│   │   ├── MatchController.php
│   │   ├── TeamController.php
│   │   └── UserController.php
│   │
│   ├── models/             # Modelos de datos
│   │   ├── Chat.php
│   │   ├── Field.php
│   │   ├── League.php
│   │   ├── MatchModel.php
│   │   ├── Sanction.php
│   │   ├── Team.php
│   │   └── User.php
│   │
│   └── views/              # Vistas (plantillas PHP)
│       ├── admin/          # Panel de administración
│       ├── auth/           # Login y registro
│       ├── chat/           # Salas de chat
│       ├── home/           # Página principal
│       ├── league/         # Ligas y clasificación
│       ├── match/          # Partidos y resultados
│       ├── team/           # Equipos y plantillas
│       ├── user/           # Dashboard y perfil de usuario
│       └── partials/       # Header, navbar y footer reutilizables
│
├── public/
│   ├── images/
│   │   └── uploads/        # Imágenes subidas por usuarios
│   └── js/                 # JavaScript del lado del cliente
│
├── assets/                 # Recursos multimedia (logos, vídeos)
│
└── database/
    └── fastplay.sql        # Script completo de base de datos + datos de ejemplo
```

---

## 👥 Roles de usuario

| Rol        | Descripción                                        |
|------------|----------------------------------------------------|
| `player`   | Jugador. Puede unirse a equipos, ver ligas y chatear |
| `captain`  | Capitán. Crea y gestiona equipos, alineaciones      |
| `admin`    | Administrador. Gestión total desde el panel admin   |

---

## 🗄️ Base de datos

### Tablas principales

| Tabla                  | Descripción                          |
|------------------------|--------------------------------------|
| `users`                | Usuarios registrados                  |
| `teams`                | Equipos con capitán y escudo          |
| `team_players`         | Relación usuarios-equipos             |
| `fields`               | Campos de juego                       |
| `seasons`              | Temporadas                            |
| `leagues`              | Ligas (amistosas y pro)               |
| `league_standings`     | Clasificación de ligas                |
| `matches`              | Partidos y resultados                 |
| `match_lineups`        | Alineaciones por partido              |
| `stats`                | Estadísticas individuales (goles, tarjetas) |
| `sanctions`            | Sanciones a equipos                   |
| `achievements`         | Logros desbloqueables                 |
| `user_achievements`    | Logros obtenidos por usuarios         |
| `chat_rooms`           | Salas de chat                         |
| `chat_room_members`    | Miembros de salas                     |
| `chat_messages`        | Mensajes de chat                      |

---

## 🛣️ Rutas disponibles

### Públicas

| Ruta                  | Método | Descripción                    |
|-----------------------|--------|--------------------------------|
| `/`                   | GET    | Página principal               |
| `/login`              | GET    | Formulario de inicio de sesión |
| `/login`              | POST   | Autenticación                  |
| `/register`           | GET    | Formulario de registro         |
| `/register`           | POST   | Crear cuenta                   |
| `/logout`             | GET    | Cerrar sesión                  |
| `/teams`              | GET    | Listado de equipos             |
| `/teams/{id}`         | GET    | Detalle de equipo              |
| `/matches`            | GET    | Listado de partidos            |
| `/matches/{id}`       | GET    | Detalle de partido             |
| `/leagues`            | GET    | Listado de ligas               |
| `/leagues/{id}`       | GET    | Detalle de liga                |

### Autenticadas

| Ruta                  | Método | Descripción                    |
|-----------------------|--------|--------------------------------|
| `/dashboard`          | GET    | Panel del usuario              |
| `/profile`            | GET    | Ver perfil                     |
| `/profile/update`     | POST   | Actualizar perfil              |
| `/chat`               | GET    | Salas de chat del usuario      |
| `/chat/{id}`          | GET    | Sala de chat específica        |
| `/teams/create`       | GET    | Formulario crear equipo        |
| `/teams/create`       | POST   | Crear equipo                   |

### Administrador

| Ruta                  | Método | Descripción                    |
|-----------------------|--------|--------------------------------|
| `/admin`              | GET    | Dashboard admin                |
| `/admin/users`        | GET    | Gestión de usuarios            |
| `/admin/teams`        | GET    | Gestión de equipos             |
| `/admin/leagues`      | GET    | Gestión de ligas               |
| `/admin/fields`       | GET    | Gestión de campos              |

---

## 🏗️ Arquitectura

FastPlay usa un patrón **MVC** personalizado sin frameworks externos:

```
Request → .htaccess → index.php → Router → Controller → Model → View → Response
```

- **Router**: Mapea rutas a controladores con soporte de parámetros (`{id}`)
- **Controller**: Clase base con `render()`, `redirect()`, `json()`, flash messages y protección por rol
- **Model**: Clase base con CRUD genérico (`find`, `findAll`, `insert`, `update`, `delete`, `count`)
- **Database**: Singleton PDO con prepared statements
- **Views**: PHP puro con Tailwind CSS via CDN

---

## 📦 Tecnologías

| Capa         | Tecnología                    |
|--------------|-------------------------------|
| Backend      | PHP 8.1+                      |
| Base de datos| MySQL 8.0+                    |
| Frontend     | Tailwind CSS (CDN)            |
| Fuente       | Inter (Google Fonts)          |
| Servidor     | Apache (mod_rewrite)          |

---

## 📝 Notas

- Los datos de ejemplo incluyen usuarios con contraseñas placeholder que deben actualizarse
- El directorio `public/images/uploads/` debe tener permisos de escritura
- Las sesiones duran 1 hora por defecto (configurable en `SESSION_LIFETIME`)

---

## 📄 Licencia

Proyecto en desarrollo.
