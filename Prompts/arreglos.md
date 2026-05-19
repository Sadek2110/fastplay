Aquí tienes un **prompt técnico completo, detallado y bien redactado** para dárselo a una IA de desarrollo, a Codex, Claude Code, Cursor, OpenCode o cualquier agente que vaya a modificar tu proyecto.

````markdown
# Prompt técnico para mejorar la lógica, diseño, responsive y UX de la plataforma deportiva

Actúa como un desarrollador full-stack senior especializado en aplicaciones web deportivas, diseño UI/UX moderno, arquitectura MVC, sistemas de usuarios, suscripciones, notificaciones, pagos y responsive design.

Necesito que revises, refactorices y mejores la página web deportiva existente, implementando toda la lógica funcional, el diseño visual y el comportamiento responsive descrito a continuación.

El objetivo es convertir la plataforma en una aplicación intuitiva, moderna, funcional y preparada para gestionar usuarios, equipos, partidos, campos, suscripciones premium, solicitudes, notificaciones y comunicación entre capitanes.

---

# 1. Lógica general de la aplicación

## 1.1 Dashboard del usuario

En el dashboard del usuario deben mostrarse únicamente secciones útiles y funcionales.

### Cambios obligatorios

- Eliminar completamente la sección de “logros”, ya que actualmente no tiene funcionalidad real.
- Mantener la información principal del usuario.
- Mantener la tarjeta estilo “carta FIFA”, pero rediseñarla visualmente.
- Añadir un nuevo apartado llamado “Notificaciones”.
- Si el usuario está registrado, debe habilitarse automáticamente el acceso a la sección de dashboard.
- El botón de perfil situado en la parte derecha de la página debe redirigir directamente a la edición del perfil del usuario.

### Nueva sección: Notificaciones

Debe existir una sección de notificaciones en el dashboard del usuario.

Las notificaciones deben utilizarse para:

- Avisar al capitán cuando un usuario solicite unirse a su equipo.
- Avisar al usuario cuando su solicitud para unirse a un equipo sea aceptada o rechazada.
- Avisar al capitán cuando otro equipo solicite jugar un partido.
- Avisar a ambos capitanes cuando se acepte una solicitud de partido.
- Avisar a los usuarios por correo electrónico cuando reciban una notificación importante.

Cada notificación debe tener, como mínimo:

- ID
- Usuario destinatario
- Tipo de notificación
- Mensaje
- Estado: leída / no leída
- Fecha de creación
- Enlace o acción relacionada, si procede

Ejemplos de tipos de notificación:

- `team_join_request`
- `team_join_accepted`
- `team_join_rejected`
- `match_request`
- `match_request_accepted`
- `match_created`
- `system`

---

# 2. Gestión de equipos

## 2.1 Unirse a un equipo

Actualmente un usuario puede unirse a un equipo directamente. Esto debe cambiar.

Nueva lógica obligatoria:

- Para unirse a un equipo, el usuario debe enviar primero una solicitud al capitán.
- El capitán recibe la solicitud en su dashboard, dentro del apartado “Notificaciones”.
- El capitán también debe recibir un correo electrónico avisando de la nueva solicitud.
- El capitán debe poder aceptar o rechazar la solicitud.
- Si el capitán acepta:
  - El usuario pasa a formar parte del equipo.
  - El usuario recibe una notificación en su dashboard.
  - El usuario recibe un correo electrónico avisando de que ha sido aceptado.
- Si el capitán rechaza:
  - El usuario recibe una notificación.
  - Opcionalmente, también puede recibir un correo.
- Un usuario no puede pertenecer a más de un equipo al mismo tiempo, salvo que se defina explícitamente lo contrario en el futuro.

## 2.2 Vista de la sección “Equipo”

La sección de equipo debe comportarse de forma diferente según el estado del usuario.

### Si el usuario NO está en ningún equipo

Debe mostrarse:

- Opción para crear equipo.
- Opción para unirse a equipo.
- Buscador de equipos.
- Desplegable/listado de equipos disponibles.
- Botón “Solicitar unirse” en cada equipo.
- Mensajes claros explicando que la solicitud debe ser aceptada por el capitán.

### Si el usuario YA está en un equipo

No deben aparecer directamente otros equipos.

Debe mostrarse únicamente la información del equipo actual:

- Nombre del equipo
- Escudo
- Capitán
- Jugadores
- Puntos
- Posición en ranking, si existe
- Estadísticas básicas
- Chat interno del equipo
- Botones de gestión si el usuario es capitán

Además, debe existir un botón separado:

```text
Ver todos los equipos
````

Este botón debe llevar a una página o vista donde se muestren todos los equipos en forma de tabla.

## 2.3 Tabla de todos los equipos

Crear una vista para ver todos los equipos registrados.

La tabla debe incluir:

* Escudo
* Nombre
* Capitán
* Puntos
* Número de jugadores
* Estado
* Botón para ver detalles

Debe incluir:

* Buscador
* Filtros
* Ordenación por puntos, nombre o fecha de creación
* Diseño responsive

---

# 3. Creación de equipos y suscripción premium

## 3.1 Restricción para crear equipo

Crear un equipo debe ser una funcionalidad premium.

Si el usuario no tiene suscripción premium:

* Debe mostrarse un mensaje claro indicando que crear un equipo requiere suscripción.
* Debe mostrarse un botón para mejorar a premium.
* El usuario debe ser redirigido a la pasarela de pago.

Si el usuario sí tiene suscripción activa:

* Puede crear un equipo.
* Al crear el equipo, el usuario se convierte automáticamente en capitán.
* Se registra la fecha de creación del equipo.
* El equipo queda vinculado al usuario capitán.

## 3.2 Pasarela de pago

Implementar una pasarela de pago para la suscripción premium.

Puede utilizarse Stripe, PayPal u otra plataforma compatible, pero la integración debe quedar bien separada y documentada.

La suscripción premium debe permitir:

* Crear equipo.
* Acceder a funcionalidades premium futuras.
* Marcar al usuario como premium mientras la suscripción esté activa.

Datos mínimos para controlar la suscripción:

* ID del usuario
* ID de cliente en la pasarela
* ID de suscripción
* Estado: activa, cancelada, pendiente, expirada
* Fecha de inicio
* Fecha de finalización o renovación
* Fecha de creación

Debe contemplarse el uso de webhooks para actualizar el estado de la suscripción automáticamente.

---

# 4. Sistema de partidos

## 4.1 Sección de partidos

La sección de partidos solo debe estar disponible para usuarios que pertenezcan a un equipo.

Si el usuario no está en un equipo:

* No debe poder solicitar partidos.
* Debe mostrarse un mensaje indicando que primero debe unirse o crear un equipo.
* Las opciones relacionadas con partidos deben estar deshabilitadas o no mostrarse.

## 4.2 Solicitar partido

En la sección de partidos debe existir un sistema para solicitar partidos contra otros equipos.

Debe incluir:

* Desplegable de equipos.
* Input para buscar equipos.
* Listado de resultados filtrados.
* Información básica del equipo rival.
* Botón “Solicitar partido”.

La información del equipo rival debe mostrar:

* Escudo
* Nombre del equipo
* Capitán
* Puntos
* Número de jugadores, si está disponible

## 4.3 Flujo de solicitud de partido

Cuando un capitán solicita un partido contra otro equipo:

1. Se crea una solicitud de partido en estado pendiente.
2. El capitán del equipo rival recibe una notificación en su dashboard.
3. El capitán del equipo rival recibe un correo electrónico.
4. El capitán rival puede aceptar o rechazar la solicitud.
5. Si acepta:

   * Se habilita un chat privado entre los dos capitanes.
   * El chat servirá únicamente para acordar hora y lugar.
6. Cuando ambos capitanes estén de acuerdo:

   * Se crea el partido oficialmente.
   * Se registran todos los datos del partido.
   * El estado inicial será `pendiente`.

## 4.4 Datos del partido

Cada partido debe guardar como mínimo:

* ID
* Equipo local
* Equipo visitante
* Capitán del equipo local
* Capitán del equipo visitante
* Fecha del partido
* Hora del partido
* Lugar
* Fecha de creación
* Estado del partido

Estados posibles:

* `pendiente`
* `jugando`
* `jugado`
* `cancelado`

## 4.5 Chat entre capitanes

No debe existir una sección global de chat.

El chat solo debe estar disponible en dos contextos concretos:

1. Chat interno del equipo.
2. Chat entre capitanes para acordar un partido.

El chat entre capitanes solo se habilita cuando una solicitud de partido ha sido aceptada.

El chat debe permitir:

* Enviar mensajes.
* Ver historial de conversación.
* Mostrar fecha y hora de cada mensaje.
* Diferenciar visualmente los mensajes de cada capitán.
* Proponer fecha, hora y lugar.
* Confirmar acuerdo.
* Crear el partido cuando ambos capitanes confirmen.

El chat debe quedar deshabilitado o cerrado cuando:

* El partido ya ha sido creado.
* La solicitud ha sido rechazada.
* La solicitud ha sido cancelada.

---

# 5. Sección de campos

## 5.1 Campos de Ceuta

Implementar una sección de campos deportivos de Ceuta.

La sección debe incluir:

* Un mapa interactivo con los campos de Ceuta.
* Una columna lateral con tarjetas de los campos.
* Cada tarjeta debe incluir:

  * Foto del campo
  * Nombre del campo
  * Dirección
  * Información básica
  * Botón para ver más detalles
  * Posible enlace a Google Maps

## 5.2 Diseño de la sección

La sección debe tener una distribución clara:

En escritorio:

* Mapa a un lado.
* Columna de campos al lado contrario.

En móvil:

* Primero debe aparecer el mapa.
* Después las tarjetas de campos en columna.
* La interfaz debe ser completamente responsive.

Se puede utilizar Leaflet, Google Maps API u otra librería de mapas, pero debe estar bien integrada y documentada.

---

# 6. Carta estilo FIFA del usuario

Rediseñar completamente la tarjeta del usuario inspirada en una carta deportiva estilo FIFA, pero con un diseño propio y moderno.

## 6.1 Contenido de la tarjeta

Debe incluir:

* Foto del usuario
* Nombre
* Posición
* Dorsal
* Equipo actual
* Altura
* Partidos jugados
* Goles
* Asistencias

## 6.2 Mejoras visuales obligatorias

La carta debe tener:

* Forma más atractiva y menos rectangular.
* Bordes personalizados.
* Fondo con degradado moderno.
* Iconos en lugar de emojis.
* Efecto 3D al pasar el ratón.
* Animación suave de entrada.
* Sombra elegante.
* Microinteracciones en estadísticas.
* Diseño premium y deportivo.

## 6.3 Interacciones

La carta debe tener:

* Hover con ligera rotación 3D.
* Transición suave.
* Estadísticas con iconos.
* Adaptación responsive.
* Buen contraste en modo oscuro y modo claro.

---

# 7. Diseño general de la página

## 7.1 Modo oscuro y modo claro

Implementar un botón para cambiar entre modo oscuro y modo claro.

Debe cumplir:

* El usuario puede cambiar el tema manualmente.
* El tema elegido se guarda en localStorage o en la configuración del usuario.
* Al recargar la página, se mantiene el tema elegido.
* El modo claro debe estar completamente implementado, no solo parcialmente.
* Todos los componentes deben verse bien en ambos modos.

Elementos que deben adaptarse:

* Fondo
* Cards
* Navbar
* Footer
* Formularios
* Tablas
* Botones
* Modales
* Inputs
* Chats
* Notificaciones
* Carta del usuario
* Sección de campos
* Sección de partidos
* Sección de equipos

## 7.2 Eliminar emojis

Eliminar todos los emojis de la página web completa.

Sustituir emojis por iconos profesionales.

Se pueden usar:

* Bootstrap Icons
* Google Material Icons
* Font Awesome
* Lucide Icons
* Heroicons

Debe evitarse completamente el uso de emojis en:

* Botones
* Títulos
* Cards
* Alertas
* Menús
* Notificaciones
* Estadísticas
* Dashboard
* Footer
* Navbar

## 7.3 UI/UX general

Mejorar toda la experiencia de usuario.

La página debe ser:

* Más intuitiva.
* Más clara.
* Más profesional.
* Más fácil de navegar.
* Más coherente visualmente.
* Más moderna.
* Más deportiva.

Añadir:

* Botones claros.
* Estados vacíos bien diseñados.
* Mensajes de ayuda.
* Botones de volver.
* Breadcrumbs si tiene sentido.
* Confirmaciones antes de acciones importantes.
* Feedback visual al enviar formularios.
* Estados de carga.
* Estados de error.
* Estados de éxito.
* Tooltips o textos de ayuda cuando sean necesarios.

Ejemplos:

* Si un usuario no tiene equipo, mostrar una card explicativa con acciones claras.
* Si no hay partidos, mostrar un estado vacío con botón para solicitar partido.
* Si no hay notificaciones, mostrar un mensaje limpio.
* Si una acción requiere premium, mostrar una card de upgrade bien diseñada.

---

# 8. Navbar, header y navegación

## 8.1 Usuario registrado

Si el usuario está registrado:

* Debe aparecer acceso al dashboard.
* El botón de perfil debe llevar a la edición del perfil.
* El menú debe mostrar opciones relacionadas con el usuario.

## 8.2 Usuario no registrado

Si el usuario no está registrado:

* No debe poder acceder a dashboard.
* Deben mostrarse botones de login y registro.
* Las secciones restringidas deben redirigir al login o mostrar mensaje informativo.

## 8.3 Botones de navegación

Añadir botones de volver en páginas internas importantes:

* Detalle de equipo
* Tabla de equipos
* Edición de perfil
* Solicitud de partido
* Detalle de partido
* Campos
* Suscripción premium

---

# 9. Responsive design

La página debe funcionar correctamente en escritorio, tablet y móvil.

## 9.1 Navbar responsive

En versión móvil:

* El navbar debe convertirse en menú hamburguesa.
* El menú debe abrirse y cerrarse correctamente.
* Debe ser accesible.
* Debe cerrar al pulsar un enlace.
* Debe mostrar las opciones según si el usuario está logueado o no.

## 9.2 Logos responsive

En móvil:

* Quitar el logo secundario o `logo_palabra`.
* Dejar únicamente el logo principal.
* El logo debe verse limpio y proporcionado.

En escritorio:

* Se puede mostrar el logo principal junto al logo de palabra si el diseño lo permite.

## 9.3 Fondo responsive

En responsive:

* El fondo debe ser una imagen estática.
* No debe tener efecto de scroll complejo.
* Evitar parallax o animaciones pesadas en móvil.
* Optimizar el fondo para rendimiento.

## 9.4 Media queries

Añadir media queries específicas para cada sección importante:

* Landing
* Dashboard
* Carta del usuario
* Equipo
* Tabla de equipos
* Partidos
* Chat
* Campos
* Perfil
* Suscripción
* Navbar
* Footer

Breakpoints recomendados:

```css
/* Móvil pequeño */
@media (max-width: 480px) {}

/* Móvil grande */
@media (max-width: 768px) {}

/* Tablet */
@media (max-width: 1024px) {}

/* Escritorio medio */
@media (max-width: 1280px) {}
```

---

# 10. Modelo de datos recomendado

Revisar y adaptar la base de datos para soportar correctamente la nueva lógica.

## 10.1 Usuarios

Campos recomendados:

* id
* name
* email
* password
* profile_photo
* position
* dorsal
* height
* current_team_id
* is_premium
* created_at
* updated_at

## 10.2 Equipos

Campos recomendados:

* id
* name
* shield
* captain_id
* points
* created_at
* updated_at

## 10.3 Miembros de equipo

Campos recomendados:

* id
* team_id
* user_id
* role: captain / player
* joined_at

## 10.4 Solicitudes para unirse a equipo

Campos recomendados:

* id
* team_id
* user_id
* captain_id
* status: pending / accepted / rejected
* created_at
* updated_at

## 10.5 Solicitudes de partido

Campos recomendados:

* id
* requesting_team_id
* requested_team_id
* requesting_captain_id
* requested_captain_id
* status: pending / accepted / rejected / cancelled
* created_at
* updated_at

## 10.6 Partidos

Campos recomendados:

* id
* local_team_id
* visitor_team_id
* local_captain_id
* visitor_captain_id
* match_date
* match_time
* location
* status: pending / playing / played / cancelled
* created_at
* updated_at

## 10.7 Chats

Campos recomendados:

* id
* type: team / match_negotiation
* team_id
* match_request_id
* created_at
* updated_at

## 10.8 Mensajes de chat

Campos recomendados:

* id
* chat_id
* sender_id
* message
* created_at

## 10.9 Notificaciones

Campos recomendados:

* id
* user_id
* type
* message
* is_read
* action_url
* created_at

## 10.10 Suscripciones

Campos recomendados:

* id
* user_id
* provider
* provider_customer_id
* provider_subscription_id
* status
* starts_at
* ends_at
* created_at
* updated_at

## 10.11 Campos deportivos

Campos recomendados:

* id
* name
* image
* address
* description
* latitude
* longitude
* maps_url
* created_at
* updated_at

---

# 11. Correos electrónicos

Implementar envío de correos electrónicos para eventos importantes.

## 11.1 Correos necesarios

Enviar correo cuando:

* Un usuario solicita unirse a un equipo.
* El capitán acepta una solicitud.
* El capitán rechaza una solicitud.
* Un equipo solicita un partido.
* Se acepta una solicitud de partido.
* Se crea un partido.
* Se activa una suscripción premium.
* Se cancela o expira una suscripción.

## 11.2 Plantillas

Las plantillas de correo deben ser claras, profesionales y coherentes con la identidad de la plataforma.

Cada correo debe incluir:

* Asunto claro.
* Saludo.
* Explicación breve.
* Botón o enlace de acción.
* Firma de la plataforma.

---

# 12. Seguridad y validaciones

Implementar validaciones en frontend y backend.

## 12.1 Validaciones de equipo

* No permitir crear equipo si el usuario no es premium.
* No permitir crear más de un equipo por usuario capitán, salvo que se defina lo contrario.
* No permitir unirse directamente a un equipo.
* No permitir solicitar unirse a un equipo si ya se pertenece a otro.
* No permitir duplicar solicitudes pendientes.

## 12.2 Validaciones de partido

* Solo capitanes pueden solicitar partidos.
* No permitir solicitar partido contra el mismo equipo.
* No permitir duplicar solicitudes pendientes entre los mismos equipos.
* No permitir crear partido sin fecha, hora y lugar.
* No permitir chat de partido si la solicitud no ha sido aceptada.

## 12.3 Validaciones de chat

* Solo miembros del equipo pueden acceder al chat de equipo.
* Solo los dos capitanes implicados pueden acceder al chat de negociación del partido.
* El chat de negociación debe cerrarse cuando el partido se crea o la solicitud se cancela.

## 12.4 Validaciones de suscripción

* Verificar siempre el estado premium en backend.
* No confiar únicamente en el frontend.
* Usar webhooks de la pasarela de pago.
* Registrar cambios de estado de la suscripción.

---

# 13. Requisitos técnicos de implementación

## 13.1 Código

El código debe quedar:

* Limpio.
* Modular.
* Comentado donde sea necesario.
* Separado por responsabilidades.
* Sin duplicación innecesaria.
* Preparado para mantenimiento.

## 13.2 Arquitectura

Respetar la arquitectura actual del proyecto.

Si el proyecto usa MVC, organizar la lógica en:

* Models
* Views
* Controllers
* Services
* Middlewares
* Helpers
* Assets
* Components, si aplica

## 13.3 Servicios recomendados

Crear servicios separados para:

* Notificaciones
* Correos electrónicos
* Suscripciones
* Pagos
* Solicitudes de equipo
* Solicitudes de partido
* Chats
* Campos deportivos

## 13.4 Controladores recomendados

Crear o revisar controladores para:

* DashboardController
* TeamController
* TeamJoinRequestController
* MatchController
* MatchRequestController
* ChatController
* NotificationController
* SubscriptionController
* PaymentController
* FieldController
* ProfileController

---

# 14. Sugerencias UI/UX a aplicar durante la revisión

Al revisar la página, aplicar mejoras de UI/UX como:

* Reducir pasos innecesarios.
* Mejorar jerarquía visual.
* Usar títulos claros.
* Usar botones primarios y secundarios de forma coherente.
* Evitar saturar las pantallas.
* Añadir estados vacíos atractivos.
* Añadir loaders.
* Añadir mensajes de confirmación.
* Usar cards limpias.
* Usar tablas responsive.
* Mejorar contraste de textos.
* Mejorar separación entre secciones.
* Evitar formularios demasiado largos.
* Añadir iconos consistentes.
* Mantener coherencia entre modo claro y oscuro.
* Mejorar navegación en móvil.
* Añadir feedback visual después de cada acción.

---

# 15. Tareas concretas a realizar

Realiza las siguientes tareas en orden:

1. Revisar la estructura actual del proyecto.
2. Identificar las páginas, controladores, modelos y estilos existentes.
3. Eliminar emojis y sustituirlos por iconos.
4. Implementar modo claro y oscuro completo.
5. Mejorar el responsive general.
6. Modificar el dashboard del usuario.
7. Eliminar la sección de logros.
8. Añadir sección de notificaciones.
9. Implementar solicitudes para unirse a equipo.
10. Implementar correos para solicitudes de equipo.
11. Modificar la sección de equipo según si el usuario tiene equipo o no.
12. Crear vista de todos los equipos en tabla.
13. Añadir lógica premium para crear equipo.
14. Integrar pasarela de pago.
15. Implementar sistema de solicitud de partidos.
16. Implementar notificaciones y correos para solicitudes de partido.
17. Habilitar chat entre capitanes solo tras aceptar solicitud.
18. Crear lógica para confirmar fecha, hora y lugar.
19. Crear partido oficial con estado inicial pendiente.
20. Implementar chat interno de equipo.
21. Crear sección de campos con mapa de Ceuta.
22. Añadir tarjetas con foto e información de cada campo.
23. Rediseñar carta deportiva del usuario con efecto 3D.
24. Añadir navbar responsive con menú hamburguesa.
25. Ocultar logo secundario en móvil.
26. Añadir media queries por sección.
27. Revisar seguridad y permisos.
28. Probar todos los flujos principales.
29. Documentar los cambios realizados.

---

# 16. Criterios de aceptación

La tarea se considerará completada cuando:

* Un usuario pueda solicitar unirse a un equipo.
* El capitán reciba notificación y correo.
* El capitán pueda aceptar o rechazar.
* El usuario reciba notificación y correo con la respuesta.
* Un usuario en equipo vea solo su equipo en la sección de equipo.
* Exista un botón para ver todos los equipos.
* Crear equipo requiera suscripción premium.
* La pasarela de pago funcione o quede preparada correctamente.
* Los partidos solo puedan solicitarse si el usuario pertenece a un equipo.
* Solo los capitanes puedan solicitar partidos.
* El capitán rival reciba notificación y correo.
* Al aceptar una solicitud de partido se habilite chat entre capitanes.
* El partido se cree con fecha, lugar, equipos, capitanes y estado.
* El chat no exista como sección global.
* El dashboard no tenga sección de logros.
* La sección de campos muestre mapa y campos de Ceuta.
* El perfil sea accesible desde el botón de perfil.
* La carta tipo FIFA tenga nuevo diseño con 3D, iconos y animaciones.
* Exista modo claro y oscuro.
* No haya emojis en la página.
* El navbar sea responsive y tenga menú hamburguesa.
* En móvil solo aparezca el logo principal.
* El fondo responsive sea estático.
* Cada sección tenga media queries adecuadas.
* La experiencia de usuario sea clara, intuitiva y moderna.

---

# 17. Resultado esperado

Devuélveme al final:

1. Resumen de los cambios realizados.
2. Archivos modificados.
3. Archivos creados.
4. Migraciones necesarias.
5. Nuevas rutas añadidas.
6. Nuevos modelos añadidos.
7. Nuevos controladores añadidos.
8. Nuevos servicios añadidos.
9. Cómo probar cada flujo.
10. Posibles mejoras futuras.

No hagas cambios a ciegas. Primero analiza el proyecto, luego implementa los cambios respetando la arquitectura existente.

```
```
