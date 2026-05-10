# FastPlay — Fútbol Amateur Organizado

> Plataforma web para la gestión de ligas, equipos y partidos de fútbol amateur.
> **"Fútbol callejero, organizado"**.

FastPlay es una aplicación web completa desarrollada en PHP (MVC) que permite a jugadores y capitanes organizar partidos, inscribirse en ligas y gestionar sus estadísticas dentro de una estética moderna "glass-and-neon-green" inspirada en un estadio nocturno.

---

## 🚀 Características Principales

- **Gestión de Ligas**: Dos niveles de competición:
    - **Liga Pro**: Tier premium con árbitros, estadísticas completas y premios en metálico.
    - **Liga Amistosa**: Tier gratuito para pachangas y retos rápidos.
- **Partidos y Campos**: Reservas de campos, gestión de convocatorias, resultados en vivo y estados de partido (Pendiente, Confirmado, Finalizado).
- **Equipos y Jugadores**: Perfiles con estadísticas (goles, asistencias, tarjetas), gestión de plantillas y capitanía.
- **Chat en Vivo**: Salas de chat grupales y negociación de partidos amistosos entre capitanes.
- **Sistema de Logros**: Gamificación mediante medallas y trofeos por hitos alcanzados.
- **Panel de Administración**: Gestión total de usuarios, equipos, ligas y campos.
- **Experiencia Inmersiva**: Animación de scroll basada en secuencias de imágenes (canvas) en la landing page.

---

## 🛠️ Stack Tecnológico

- **Backend**: PHP 8.x (Arquitectura MVC personalizada).
- **Base de Datos**: SQLite (Con sistema de auto-migración y seeding).
- **Frontend**: Vanilla CSS 3 (Design System propio) y Vanilla JS.
- **Servidor**: Apache (Optimizado con reglas de reescritura en `.htaccess`).
- **Seguridad**:
    - Protección CSRF integral.
    - Sesiones endurecidas (HttpOnly, Secure, SameSite).
    - Rate-limiting en login.
    - Content Security Policy (CSP) restrictiva.

---

## 📂 Estructura del Proyecto

```text
FastPlay_v3/
├── app/                # Lógica de la aplicación (MVC)
│   ├── controllers/    # Controladores (Admin, Auth, Matches, etc.)
│   ├── core/           # Núcleo: Router, Database, Controller base
│   ├── models/         # Modelos de datos (Usuario, Partido, Equipo, etc.)
│   └── views/          # Vistas (PHP puro con layouts y partials)
├── config/             # Configuración global y seguridad
├── public/             # Punto de entrada y assets públicos
│   ├── css/            # Estilos (app.css y animaciones)
│   ├── js/             # Scripts (scroll-anim.js)
│   └── index.php       # Front Controller
├── storage/            # Base de datos SQLite y logs
├── uploads/            # Archivos subidos por usuarios
├── BUGS.md             # Registro de errores corregidos
└── README.md           # Este archivo
```

---

## ⚙️ Instalación y Configuración

1. **Requisitos**:
    - Servidor Apache con `mod_rewrite` habilitado.
    - PHP 8.0 o superior con extensiones `pdo_sqlite` y `mbstring`.
2. **Despliegue**:
    - Clona o descarga el repositorio en tu servidor (ej. `xampp/htdocs/`).
    - La aplicación detecta automáticamente la `BASE_URL` basándose en el directorio de instalación.
3. **Base de Datos**:
    - No requiere configuración previa. Al acceder por primera vez, el sistema crea automáticamente el archivo `storage/fastplay.sqlite` y ejecuta las migraciones y el seeding de datos demo.
4. **Credenciales Demo**:
    - **Admin**: `admin@fastplay.es` / `admin1234`
    - **Jugador**: `demo@fastplay.es` / `demo1234`

---

## 🎨 Identidad Visual y Diseño

### Concepto de Marca
FastPlay trata cada pantalla como un **partido nocturno bajo focos**. La identidad utiliza un fondo casi negro (`#060d09`), tarjetas de "cristal" translúcidas, un verde neón confiado (`#16a34a`) para acciones y un dorado (`#fbbf24`) reservado exclusivamente para la **Liga Pro**.

### Tipografía y Tono
- **Fuente**: Inter (Cargas variables, pesos Black para titulares).
- **Voz**: Informal ("Tú"), directa y deportiva. Los botones siempre terminan en ` →`.
- **Iconografía**: Uso deliberado de **Emojis** como iconografía de marca y **SVG inline** para utilidades funcionales.

### Superficies (Glassmorphism)
```css
.glass {
  background: rgba(255, 255, 255, 0.04);
  backdrop-filter: blur(16px);
  border: 1px solid rgba(255, 255, 255, 0.08);
}
```

---

## 📝 Notas de Versión (v3)
Esta versión incluye una refactorización profunda del sistema de rutas, correcciones críticas en la seguridad de las sesiones y la extracción de lógicas de animación a archivos externos para optimizar el rendimiento. Consulta `BUGS.md` para ver el historial detallado de cambios recientes.
