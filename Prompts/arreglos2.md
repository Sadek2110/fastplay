Aquí tienes el prompt desarrollado, más técnico, ordenado y listo para copiar y pegar en una IA de desarrollo:

````markdown
# Prompt técnico para corregir errores, mejorar UI y adaptar la plataforma deportiva a Ceuta

Actúa como un desarrollador full-stack senior especializado en PHP, arquitectura MVC, UI/UX, diseño responsive, integración de APIs externas y plataformas deportivas.

Necesito que revises y mejores el proyecto web deportivo actual. La plataforma debe estar enfocada completamente en Ceuta, por lo que toda la información, campos, equipos, partidos, textos, ubicación, mapas y referencias deben adaptarse a esta ciudad.

Antes de modificar nada, analiza la estructura actual del proyecto, detecta los errores existentes y respeta la arquitectura ya implementada.

---

# 1. Corrección crítica: Dashboard roto con Error 500

Actualmente el dashboard está roto y devuelve un Error 500.

## Objetivo

Corregir completamente el error del dashboard para que vuelva a cargar correctamente.

## Tareas obligatorias

1. Revisar los logs del servidor o del framework.
2. Localizar el origen exacto del Error 500.
3. Revisar:
   - Controlador del dashboard.
   - Modelo de usuario.
   - Relaciones con equipo.
   - Relaciones con partidos.
   - Relaciones con notificaciones.
   - Vista del dashboard.
   - Variables que se envían a la vista.
   - Consultas SQL o llamadas a base de datos.
4. Comprobar si el error viene de:
   - Variable no definida.
   - Relación inexistente.
   - Campo que no existe en base de datos.
   - Tabla no creada.
   - Método inexistente.
   - Error de sintaxis.
   - Error de permisos.
   - Error en rutas.
5. Corregir el error sin romper otras secciones.
6. Añadir validaciones defensivas para evitar que el dashboard falle si el usuario todavía no tiene:
   - Equipo.
   - Partidos.
   - Estadísticas.
   - Notificaciones.
   - Foto de perfil.

## Resultado esperado

El dashboard debe cargar correctamente tanto si el usuario tiene equipo como si no tiene equipo.

Si faltan datos, debe mostrarse un estado vacío elegante en lugar de romper la página.

Ejemplo:

```text
Todavía no perteneces a ningún equipo.
Únete a uno o crea tu propio equipo para empezar a competir en Ceuta.
````

---

# 2. Sección de equipo

Actualmente la sección de equipo debe simplificarse y mostrar directamente la información principal del equipo del usuario.

## Objetivo

Cuando el usuario entre en la sección de equipo, debe ver directamente la información de su equipo actual.

## Información obligatoria que debe aparecer

La sección debe mostrar:

* Nombre del equipo.
* Escudo o imagen del equipo.
* Capitán del equipo.
* Partidos jugados.
* Jugadores del equipo.
* Puntos del equipo, si existen.
* Fecha de creación, si existe.
* Estadísticas básicas del equipo.

## Comportamiento según el usuario

### Si el usuario pertenece a un equipo

Mostrar directamente una vista principal del equipo con:

```text
Nombre del equipo
Capitán
Partidos jugados
Jugadores
Puntos
Escudo
```

También debe incluir botones claros:

* Ver jugadores.
* Ver partidos del equipo.
* Editar equipo, solo si el usuario es capitán.
* Buscar rival o solicitar partido, solo si el usuario es capitán.
* Volver al dashboard.

### Si el usuario no pertenece a ningún equipo

Mostrar un estado vacío con opciones claras:

* Solicitar unirse a un equipo.
* Crear equipo, solo si tiene suscripción premium.
* Ver equipos disponibles.

## Requisitos técnicos

* No deben aparecer directamente todos los equipos si el usuario ya pertenece a uno.
* Si se quiere ver el listado completo de equipos, debe existir un botón separado.
* Evitar errores si el usuario no tiene equipo asignado.
* Usar consultas seguras y comprobar siempre que los datos existen antes de mostrarlos.

---

# 3. Mejora del modo claro

La parte clara del proyecto está demasiado blanca y visualmente plana.

## Objetivo

Rediseñar el modo claro para que sea más agradable, deportivo, moderno y menos agresivo visualmente.

## Nueva dirección visual

El modo claro debe usar:

* Blanco roto en lugar de blanco puro.
* Verde claro como color de apoyo.
* Verde deportivo suave.
* Dorado para botones de llamada a la acción.
* Sombras suaves.
* Degradados sutiles.
* Bordes redondeados.
* Cards con profundidad visual.

## Paleta recomendada

Usar una paleta similar a esta:

```css
:root {
  --color-bg-light: #f5f3ea;
  --color-surface-light: #fffdf6;
  --color-surface-alt-light: #edf7ed;

  --color-primary: #2f7d46;
  --color-primary-light: #7bcf8a;
  --color-primary-soft: #dff3e2;

  --color-gold: #d6a93c;
  --color-gold-hover: #c49326;

  --color-text-main: #1f2a24;
  --color-text-muted: #68736d;

  --color-border-light: #d8ddcf;

  --shadow-soft: 0 8px 24px rgba(31, 42, 36, 0.08);
  --shadow-card: 0 14px 36px rgba(31, 42, 36, 0.12);
}
```

## Cambios visuales obligatorios

Aplicar mejoras en:

* Fondo general.
* Navbar.
* Cards.
* Dashboard.
* Sección de equipo.
* Sección de partidos.
* Botones.
* Formularios.
* Inputs.
* Tablas.
* Calendario.
* Sección de campos.
* Footer.

## Botones CTA

Los botones importantes deben usar dorado.

Ejemplos de botones CTA:

* Crear equipo.
* Hacerse premium.
* Solicitar partido.
* Confirmar partido.
* Guardar cambios.
* Ver campos.
* Unirse a equipo.

Ejemplo visual:

```css
.btn-cta {
  background: linear-gradient(135deg, #d6a93c, #f1d27a);
  color: #1f2a24;
  border: none;
  border-radius: 14px;
  box-shadow: 0 10px 24px rgba(214, 169, 60, 0.25);
  font-weight: 700;
}

.btn-cta:hover {
  background: linear-gradient(135deg, #c49326, #e6bf58);
  transform: translateY(-2px);
}
```

---

# 4. Landing page

La landing debe simplificarse.

## Cambios obligatorios

En la landing page:

* Quitar las secciones de equipos.
* Quitar las secciones de partidos.
* Quitar bloques innecesarios que hagan la página demasiado larga.
* Quitar el selector de modo claro y modo oscuro de la landing.
* Mantener una presentación limpia, directa y orientada a Ceuta.
* Enfocar el mensaje en fútbol local, equipos de Ceuta, partidos y comunidad deportiva.

## Estructura recomendada de landing

La landing debería quedar con estas secciones:

1. Hero principal.
2. Breve explicación de la plataforma.
3. Beneficios principales.
4. Campos destacados de Ceuta.
5. Llamada a la acción.
6. Footer.

## Hero recomendado

Debe tener:

* Título claro.
* Subtítulo.
* Botón principal.
* Botón secundario.
* Imagen o fondo deportivo relacionado con Ceuta.
* Diseño moderno.

Ejemplo de copy:

```text
Organiza partidos, crea tu equipo y compite en Ceuta

Una plataforma deportiva para jugadores, capitanes y equipos locales.
Encuentra campos, reta a otros equipos y gestiona tus partidos desde un solo lugar.
```

Botones:

```text
Crear cuenta
Ver campos de Ceuta
```

---

# 5. API de Google Maps con campos de Ceuta

Necesito que añadas una guía paso a paso dentro del proyecto o documentación para implementar Google Maps API con puntos señalados de los campos deportivos de Ceuta.

## Objetivo

En la sección de campos debe aparecer un mapa de Google Maps con marcadores sobre los campos de Ceuta.

## Pasos para implementar Google Maps API

### Paso 1: Crear proyecto en Google Cloud

1. Entrar en Google Cloud Console.
2. Crear un nuevo proyecto.
3. Ponerle un nombre relacionado con la plataforma, por ejemplo:

```text
Plataforma Deportiva Ceuta
```

### Paso 2: Activar APIs necesarias

Activar como mínimo:

* Maps JavaScript API.
* Places API, opcional si se quieren buscar lugares.
* Geocoding API, opcional si se quieren convertir direcciones en coordenadas.

### Paso 3: Crear API Key

1. Ir a Credenciales.
2. Crear una nueva clave de API.
3. Restringir la clave por dominio.
4. Restringir la clave solo a las APIs necesarias.
5. No dejar la API Key abierta públicamente sin restricciones.

### Paso 4: Guardar la API Key

Guardar la API Key en variables de entorno.

Ejemplo:

```env
GOOGLE_MAPS_API_KEY=tu_api_key_aqui
```

No escribir la API Key directamente en el código si se puede evitar.

### Paso 5: Cargar Google Maps en la vista

Añadir el script de Google Maps en la vista de campos.

Ejemplo:

```html
<script async defer
  src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY&callback=initMap">
</script>
```

Si el proyecto usa PHP, la API Key debe imprimirse desde configuración o variable de entorno.

### Paso 6: Crear contenedor del mapa

```html
<div id="ceuta-map" class="ceuta-map"></div>
```

CSS recomendado:

```css
.ceuta-map {
  width: 100%;
  height: 520px;
  border-radius: 24px;
  overflow: hidden;
  box-shadow: var(--shadow-card);
}
```

### Paso 7: Crear listado de campos con coordenadas

Crear un array de campos deportivos de Ceuta.

Ejemplo:

```js
const camposCeuta = [
  {
    name: "Estadio Municipal Alfonso Murube",
    address: "Ceuta",
    lat: 35.8883,
    lng: -5.3162,
    image: "/assets/img/campos/alfonso-murube.jpg",
    description: "Campo de fútbol principal de Ceuta."
  },
  {
    name: "Campo José Martínez Pirri",
    address: "Ceuta",
    lat: 35.8890,
    lng: -5.3070,
    image: "/assets/img/campos/jose-martinez-pirri.jpg",
    description: "Instalación deportiva para fútbol local."
  }
];
```

Importante: revisar y ajustar las coordenadas reales antes de producción.

### Paso 8: Inicializar el mapa

```js
function initMap() {
  const ceutaCenter = { lat: 35.8894, lng: -5.3198 };

  const map = new google.maps.Map(document.getElementById("ceuta-map"), {
    zoom: 13,
    center: ceutaCenter,
    mapTypeControl: false,
    streetViewControl: false,
    fullscreenControl: true
  });

  camposCeuta.forEach((campo) => {
    const marker = new google.maps.Marker({
      position: { lat: campo.lat, lng: campo.lng },
      map,
      title: campo.name
    });

    const infoWindow = new google.maps.InfoWindow({
      content: `
        <div class="map-info-window">
          <strong>${campo.name}</strong>
          <p>${campo.address}</p>
          <p>${campo.description}</p>
        </div>
      `
    });

    marker.addListener("click", () => {
      infoWindow.open(map, marker);
    });
  });
}
```

### Paso 9: Mostrar columna lateral de campos

A la derecha del mapa debe aparecer una columna con tarjetas.

Cada tarjeta debe mostrar:

* Foto del campo.
* Nombre.
* Dirección.
* Descripción breve.
* Botón para ver en Google Maps.

### Paso 10: Conectar tarjetas con mapa

Al hacer clic en una tarjeta:

* El mapa debe centrarse en ese campo.
* Debe abrirse su marcador.
* La tarjeta seleccionada debe resaltarse visualmente.

---

# 6. Calendario en sección de partidos

En la sección de partidos debe aparecer un calendario actualizado en la parte derecha.

## Objetivo

Mostrar un calendario visual donde se puedan consultar los partidos programados.

## Ubicación

En escritorio:

* La sección principal de partidos debe estar a la izquierda.
* El calendario debe estar a la derecha.

En móvil:

* Primero aparece la sección principal de partidos.
* Después aparece el calendario debajo.

## Funcionalidad del calendario

El calendario debe mostrar:

* Mes actual.
* Días del mes.
* Día actual resaltado.
* Días con partidos marcados.
* Lista de partidos del día seleccionado.
* Navegación entre meses.

## Datos que debe mostrar cada partido

* Equipo local.
* Equipo visitante.
* Hora.
* Campo o lugar.
* Estado del partido.

Estados posibles:

* Pendiente.
* Jugando.
* Jugado.
* Cancelado.

## Requisitos técnicos

* El calendario debe actualizarse con los partidos reales de la base de datos.
* Si no hay partidos para un día, mostrar un mensaje limpio.
* Evitar que el calendario rompa si no hay partidos.
* Debe adaptarse al modo claro rediseñado.
* Debe ser responsive.

## Librerías permitidas

Puedes usar una de estas opciones:

* FullCalendar.
* Flatpickr.
* Calendario personalizado en JavaScript.
* Componente propio si el proyecto no usa librerías externas.

## Diseño recomendado

```css
.matches-calendar {
  background: var(--color-surface-light);
  border: 1px solid var(--color-border-light);
  border-radius: 24px;
  padding: 24px;
  box-shadow: var(--shadow-soft);
}

.calendar-day.has-match {
  background: var(--color-primary-soft);
  border-color: var(--color-primary);
}

.calendar-day.today {
  background: linear-gradient(135deg, #d6a93c, #f1d27a);
  color: #1f2a24;
}
```

---

# 7. Separación visual y espaciado

Actualmente los elementos están demasiado juntos.

## Objetivo

Aumentar el gap general entre componentes para mejorar la legibilidad y la sensación visual de calidad.

## Cambios obligatorios

Revisar y mejorar el espaciado en:

* Landing.
* Dashboard.
* Cards.
* Formularios.
* Sección de equipos.
* Sección de partidos.
* Calendario.
* Sección de campos.
* Navbar.
* Footer.
* Tablas.
* Chats.
* Botones.
* Listados.

## Reglas recomendadas de espaciado

Usar variables CSS:

```css
:root {
  --space-xs: 6px;
  --space-sm: 12px;
  --space-md: 20px;
  --space-lg: 32px;
  --space-xl: 48px;
  --space-xxl: 72px;
}
```

Aplicar:

```css
.section {
  padding: var(--space-xl) 0;
}

.card {
  padding: var(--space-lg);
}

.grid {
  gap: var(--space-lg);
}

.form-group {
  margin-bottom: var(--space-md);
}

.btn-group {
  gap: var(--space-sm);
}
```

## Resultado esperado

La página debe respirar más.

No deben verse:

* Cards pegadas.
* Botones demasiado juntos.
* Inputs pegados al texto.
* Secciones sin separación.
* Tablas visualmente saturadas.

---

# 8. Adaptación completa a Ceuta

Toda la información de la página debe estar enfocada en Ceuta.

## Cambios obligatorios

Modificar textos, datos, campos, ejemplos y referencias para que todo pertenezca a Ceuta.

## Elementos a adaptar

* Landing.
* Sección de campos.
* Equipos.
* Partidos.
* Textos de ayuda.
* Estados vacíos.
* Correos.
* Notificaciones.
* Footer.
* Datos de ejemplo.
* Metadatos SEO.
* Títulos y descripciones.

## Ejemplos de textos adaptados

```text
Encuentra campos de fútbol en Ceuta.
Crea tu equipo local y compite contra otros equipos de la ciudad.
Organiza partidos en los campos deportivos de Ceuta.
Consulta los próximos encuentros de la comunidad futbolera ceutí.
```

## Campos deportivos iniciales recomendados

Añadir campos deportivos de Ceuta como datos iniciales.

Ejemplos:

* Estadio Municipal Alfonso Murube.
* Campo José Martínez Pirri.
* Campo Federativo José Benoliel.
* Campo de fútbol del Príncipe.
* Campo de fútbol de la Marina, si existe en la base de datos local.
* Otros campos deportivos reales de Ceuta que se puedan confirmar.

Importante: validar nombres, direcciones y coordenadas antes de producción.

---

# 9. Responsive

Revisar que todos estos cambios funcionen correctamente en móvil.

## Cambios obligatorios

* El calendario debe ponerse debajo del contenido principal en móvil.
* El mapa debe adaptarse al ancho completo.
* Las tarjetas de campos deben mostrarse en una sola columna.
* La sección de equipo debe verse limpia en móvil.
* El dashboard debe evitar columnas demasiado estrechas.
* Las cards deben tener suficiente separación.
* Los botones deben ocupar el ancho necesario sin romper el diseño.
* La landing debe ser más corta y directa.
* El navbar responsive debe seguir funcionando correctamente.

## Media queries recomendadas

```css
@media (max-width: 1024px) {
  .matches-layout,
  .fields-layout {
    grid-template-columns: 1fr;
  }

  .matches-calendar {
    order: 2;
  }
}

@media (max-width: 768px) {
  .section {
    padding: 40px 0;
  }

  .card {
    padding: 20px;
  }

  .ceuta-map {
    height: 380px;
  }
}

@media (max-width: 480px) {
  .ceuta-map {
    height: 320px;
  }

  .btn,
  .btn-cta {
    width: 100%;
  }
}
```

---

# 10. Revisión de rutas y vistas

Revisar que las rutas estén correctamente conectadas.

## Rutas importantes

Comprobar:

* `/dashboard`
* `/equipo`
* `/equipos`
* `/partidos`
* `/campos`
* `/perfil/editar`
* `/premium`
* `/login`
* `/registro`

## Objetivo

Asegurar que:

* Ninguna ruta devuelve Error 500.
* Las vistas reciben todos los datos necesarios.
* Los usuarios no autenticados son redirigidos correctamente.
* Los usuarios sin equipo ven estados vacíos.
* Los usuarios con equipo ven la información correspondiente.
* El capitán ve opciones de gestión.
* Un jugador normal no ve acciones reservadas al capitán.

---

# 11. Pruebas necesarias

Realizar pruebas manuales después de implementar.

## Casos de prueba

### Dashboard

* Usuario sin equipo entra al dashboard.
* Usuario con equipo entra al dashboard.
* Capitán entra al dashboard.
* Usuario sin notificaciones entra al dashboard.
* Usuario con notificaciones entra al dashboard.

### Equipo

* Usuario sin equipo entra en sección equipo.
* Usuario con equipo entra en sección equipo.
* Capitán entra en sección equipo.
* Jugador normal entra en sección equipo.

### Landing

* La landing no muestra secciones de equipos ni partidos.
* La landing no muestra selector de modo claro/oscuro.
* La landing mantiene un CTA claro.

### Mapa

* El mapa carga correctamente.
* Aparecen marcadores de campos de Ceuta.
* Al hacer clic en un marcador se abre la información.
* Al hacer clic en una tarjeta se centra el mapa.

### Partidos

* La sección de partidos carga correctamente.
* El calendario aparece a la derecha.
* El calendario muestra el mes actual.
* Los días con partidos aparecen marcados.
* En móvil el calendario baja debajo del contenido principal.

### UI

* Hay más separación entre elementos.
* El modo claro ya no parece blanco puro.
* Los botones CTA usan dorado.
* Las cards tienen sombras y degradados suaves.
* La interfaz se ve coherente y moderna.

---

# 12. Resultado final esperado

Al finalizar, devuélveme:

1. Qué causaba el Error 500 del dashboard.
2. Qué archivos se han modificado.
3. Qué archivos se han creado.
4. Qué cambios se hicieron en la sección de equipo.
5. Qué cambios se hicieron en la landing.
6. Qué cambios se hicieron en el modo claro.
7. Cómo se implementó o preparó Google Maps.
8. Cómo se añadieron los campos de Ceuta.
9. Cómo funciona el calendario de partidos.
10. Qué mejoras responsive se aplicaron.
11. Qué pruebas se realizaron.
12. Qué queda pendiente o recomendable para una siguiente fase.

---

# 13. Prioridad de implementación

Trabaja en este orden:

1. Corregir Error 500 del dashboard.
2. Blindar dashboard para usuarios sin datos.
3. Mejorar sección de equipo.
4. Simplificar landing.
5. Rediseñar modo claro.
6. Aumentar gaps y separación visual.
7. Añadir calendario en partidos.
8. Preparar integración de Google Maps.
9. Añadir campos de Ceuta.
10. Revisar responsive.
11. Probar rutas principales.

No implementes cambios de forma desordenada. Primero arregla los errores críticos y luego mejora diseño y funcionalidades.

```
```
