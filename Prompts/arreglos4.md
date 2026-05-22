Aquí tienes el prompt desarrollado y ordenado para pasárselo a un desarrollador o a una IA de código:

---

# Prompt para rediseñar y corregir interfaz de FastPlay

Necesito realizar varios arreglos y mejoras visuales en la plataforma **FastPlay**, manteniendo una estética moderna, deportiva, dinámica y coherente en todas las páginas.

El diseño general debe seguir una línea visual similar al navbar actual: estilo **glassmorphism**, iluminación suave, bordes redondeados, sombras, transiciones fluidas, animaciones ligeras y colores vivos.
En modo claro debe predominar el contraste entre **verde, blanco y tonos neutros**.
En modo oscuro debe potenciarse más el uso del **amarillo/dorado**, manteniendo una estética deportiva y elegante.

---

## 1. Arreglos en la Landing Page

En la landing actual hay que corregir los siguientes puntos:

### Navbar

El navbar debe aparecer en la parte superior de la página, como un navbar tradicional, no como sidebar lateral.

Debe incluir únicamente:

* Logo de FastPlay.
* Opción de iniciar sesión.
* Opción de registrarse.

El diseño del navbar debe ser limpio, moderno y responsive, manteniendo el estilo glass con fondo translúcido, blur, bordes suaves y efecto de iluminación.

### Barra de progreso

Eliminar completamente la barra de progreso que aparece actualmente en la parte superior de la landing.

No debe mostrarse en ningún estado ni resolución.

---

## 2. Página de Campos

URL: `https://fastplay.dksaa.com/campos`

Rediseñar la página de campos para que sea más visual y útil.

### Mapa

El mapa actual debe reducirse de tamaño.

Debe seguir siendo visible y funcional, pero no debe ocupar demasiado espacio en la pantalla.

El diseño debe estar mejor integrado con las tarjetas de campos.

### Tarjetas de campos

Cada tarjeta de campo debe mostrar:

* Foto del campo.
* Nombre del campo.
* Ubicación.
* Información relevante disponible.
* Botón o acción para ver más detalles o reservar, si ya existe esa funcionalidad.

Si actualmente no existen fotos de los campos en la base de datos o en el backend, el sistema debe avisar claramente al usuario/desarrollador de que hacen falta imágenes para completar las tarjetas.

Mientras no haya fotos, usar una imagen placeholder elegante relacionada con fútbol/césped/campo deportivo.

---

## 3. Página de Equipos

URL: `https://fastplay.dksaa.com/teams`

Hay que rediseñar completamente la interfaz de la página de equipos.

La estructura debe ser la siguiente:

### Encabezado del equipo

En la parte superior de la página debe aparecer un encabezado visual con:

* Nombre del equipo.
* Escudo del equipo.
* Número total de jugadores.
* Información básica del equipo, si existe.
* Estilo destacado con fondo glass, sombras e iluminación.

El nombre del equipo debe tener jerarquía visual clara.

### Distribución principal

La página debe dividirse en dos zonas principales:

#### Parte izquierda: listado de jugadores

Mostrar todos los jugadores del equipo en una lista visual.

Cada jugador debe aparecer con:

* Nombre.
* Foto o avatar.
* Posición.
* Número, si existe.
* Indicador si es capitán.

Al hacer click sobre un jugador de la lista, debe mostrarse su carta de jugador y su información detallada.

#### Parte derecha: campo dibujado

En la parte derecha debe aparecer un campo de fútbol dibujado visualmente.

El campo debe tener:

* Líneas del campo.
* Posiciones de los jugadores.
* Iconos o avatares de los jugadores colocados sobre el campo.
* Animaciones suaves al pasar el ratón o hacer click.

Al hacer click sobre un jugador dentro del campo, debe abrirse o mostrarse la misma carta de jugador que si se pulsara en la lista de la izquierda.

### Capitán

En la parte superior del dibujo del campo debe aparecer destacado el capitán del equipo.

Debe mostrarse con un estilo especial, por ejemplo:

* Icono de brazalete.
* Corona.
* Borde dorado.
* Etiqueta “Capitán”.

### Carta del jugador

Cuando se seleccione un jugador, debe mostrarse una carta visual con:

* Foto/avatar.
* Nombre.
* Posición.
* Número.
* Equipo.
* Estadísticas disponibles.
* Información adicional del jugador.

La carta debe tener estilo deportivo, con animación de entrada, efecto hover y diseño responsive.

### Estadísticas del equipo

En la parte inferior de la página deben aparecer las estadísticas generales del equipo:

* Partidos jugados.
* Puntos.
* Liga en la que participa, si existe.
* Posición en la clasificación, si existe.
* Victorias.
* Empates.
* Derrotas.
* Goles a favor.
* Goles en contra.
* Otras estadísticas disponibles.

Estas estadísticas deben mostrarse en cards visuales, modernas y fáciles de leer.

---

## 4. Página de Partidos

URL: `https://fastplay.dksaa.com/matches`

Rediseñar completamente la página de partidos para que sea más visual, dinámica y animada.

La estructura debe ser la siguiente:

### Distribución principal

La página debe dividirse en dos columnas:

* Izquierda: listado de partidos.
* Derecha: calendario.

En versión móvil debe adaptarse correctamente, mostrando primero el calendario o los partidos según mejor experiencia de usuario.

---

### Calendario

El calendario debe aparecer en la parte derecha de la página.

Debe cumplir lo siguiente:

* El día actual debe aparecer destacado.
* Los días en los que haya partidos deben aparecer subrayados o marcados visualmente.
* Al pulsar sobre un día, si hay partidos programados, deben mostrarse en un resumen dentro del propio bloque del calendario.
* Si no hay partidos ese día, mostrar un mensaje elegante tipo: “No hay partidos programados para este día”.

El calendario debe tener un diseño moderno, con colores vivos, transiciones suaves y buena integración visual con el resto de la página.

---

### Listado de partidos

En la parte izquierda deben aparecer los partidos listados en divs/cards.

Cada div de partido debe tener tres partes claramente diferenciadas:

#### 1. Fecha

La fecha debe aparecer destacada visualmente.

Debe tener un color diferente, fondo resaltado o etiqueta llamativa.

#### 2. Equipos

Mostrar los dos equipos enfrentados.

Debe incluir:

* Logo del equipo local.
* Nombre del equipo local.
* `VS` si el partido aún no se ha jugado.
* Resultado si el partido ya se ha jugado.
* Logo del equipo visitante.
* Nombre del equipo visitante.

El diseño debe parecer un marcador deportivo moderno.

#### 3. Estado y campo

Mostrar en un único bloque:

* Estado del partido: pendiente, jugado, cancelado, en curso, etc.
* Campo donde se juega.
* Hora, si existe.

Este bloque debe tener un diseño compacto y claro.

---

### Animaciones y estilo

Esta página debe tener más vida visual.

Añadir:

* Transiciones suaves.
* Hover effects en las cards.
* Animaciones de entrada.
* Colores deportivos vivos.
* Microinteracciones al seleccionar días o partidos.
* Efectos glass, sombras e iluminación.

El objetivo es que la página parezca una sección deportiva moderna, no una tabla básica.

---

## 5. Página de Inicio / Dashboard

URL: `https://fastplay.dksaa.com/dashboard`

Mejorar el diseño y contenido de la página principal del usuario.

### H1 de bienvenida

El H1 de bienvenida debe mantenerse, pero el nombre del usuario debe aparecer con un estilo diferente.

Aplicar al nombre del usuario:

* `linear-gradient` verde y dorado.
* Efecto de brillo.
* Sombra suave.
* Estilo destacado dentro del texto.

Ejemplo visual esperado:

```text
Bienvenido, [Nombre del usuario]
```

Donde el nombre tenga un efecto visual premium/deportivo.

---

### Carta del jugador

La carta del jugador debe ser clicable.

Al hacer click en ella, debe redirigir a:

```text
https://fastplay.dksaa.com/profile/edit
```

Debe tener hover effect para que el usuario entienda que es interactiva.

---

### Botones para volver atrás o al dashboard

En todas las acciones, formularios, páginas secundarias o vistas internas debe existir un botón claro para:

* Volver atrás.
* Volver al dashboard principal.

El botón debe tener un diseño coherente con la interfaz.

---

### Sección izquierda del dashboard

Actualmente hay divs feos o poco útiles en la parte izquierda.

Hay que eliminarlos y sustituirlos por información real y útil para el usuario.

Debe mostrarse:

* Equipo al que pertenece el usuario.
* Próximos partidos de su equipo.
* Si el usuario es capitán o no.
* Estado del jugador dentro del equipo.
* Otra información relevante disponible.

La información debe venir de los datos reales del backend/API.
No utilizar datos falsos salvo placeholders temporales claramente identificados.

---

### Sección destacada para buscar partido

Añadir una sección visualmente destacada para buscar partido.

Debe tener un estilo diferente al resto de la página para llamar la atención.

Debe incluir:

* Título atractivo.
* Texto breve explicando que puede buscar o crear un partido.
* Botón principal.

El botón debe redirigir a:

```text
https://fastplay.dksaa.com/matches/create
```

Debe tener un diseño llamativo, con gradiente, brillo o animación sutil.

---

### Notificaciones

Las notificaciones deben aparecer más abajo en el dashboard.

Hay que mejorar su diseño para que estén adaptadas al estilo general de la página.

Deben mostrarse como cards o bloques modernos, con:

* Icono.
* Título.
* Descripción.
* Fecha, si existe.
* Estado leído/no leído, si existe.

No deben ocupar la parte superior de forma agresiva.

---

## 6. Diseño general de todas las páginas

Aplicar una mejora visual global a la plataforma.

### Estilo visual

El estilo general debe ser:

* Deportivo.
* Moderno.
* Dinámico.
* Glassmorphism.
* Con iluminación.
* Con animaciones suaves.
* Con transiciones.
* Con tarjetas más limpias.
* Con mejor jerarquía visual.

### Modo claro

En modo claro usar principalmente:

* Verde.
* Blanco.
* Gris suave.
* Detalles dorados puntuales.
* Alto contraste sin perder limpieza.

### Modo oscuro

En modo oscuro implementar más presencia de:

* Amarillo.
* Dorado.
* Verde oscuro.
* Negro/azul muy oscuro.
* Brillos suaves.

El modo oscuro debe sentirse más premium y deportivo.

### Componentes

Unificar el diseño de:

* Navbar.
* Cards.
* Botones.
* Inputs.
* Calendarios.
* Modales.
* Listados.
* Estados.
* Badges.
* Avatares.
* Tablas o secciones estadísticas.

### Animaciones

Añadir animaciones ligeras, sin sobrecargar la página:

* Entrada de cards.
* Hover effects.
* Transiciones entre estados.
* Brillos suaves.
* Movimiento ligero en botones importantes.
* Feedback visual al seleccionar elementos.

### Responsive

Todas las páginas deben funcionar correctamente en:

* Desktop.
* Tablet.
* Móvil.

En móvil, las columnas deben reorganizarse de forma limpia, evitando scroll horizontal o elementos cortados.

---

## 7. Requisitos técnicos

Antes de implementar, revisar la estructura actual del proyecto y reutilizar los componentes existentes siempre que sea posible.

No romper la lógica actual de autenticación, rutas, llamadas al backend ni datos existentes.

Si falta información necesaria, como fotos de campos, escudos de equipos, avatares de jugadores o estadísticas, dejar placeholders elegantes y avisos claros para completar esos datos.

El código debe quedar limpio, ordenado y reutilizable.

Crear o mejorar componentes reutilizables como:

* `GlassCard`
* `TeamHeader`
* `PlayerCard`
* `FootballField`
* `MatchCard`
* `MatchCalendar`
* `BackButton`
* `DashboardStats`
* `NotificationCard`

Mantener nombres claros y consistentes.

---

## 8. Resultado esperado

El resultado final debe ser una interfaz mucho más moderna, visual y deportiva.

La plataforma debe transmitir mejor la idea de app deportiva de fútbol, con una experiencia más atractiva para jugadores, equipos y usuarios.

Priorizar:

* Claridad visual.
* Buena experiencia de usuario.
* Diseño responsive.
* Animaciones suaves.
* Información real.
* Estética coherente en todas las páginas.
* Diseño premium tipo glass con iluminación.
