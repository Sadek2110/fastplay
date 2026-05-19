# Google Maps API en FastPlay — Campos de Ceuta

Esta guía te lleva desde cero hasta tener el mapa de Google funcionando en `/campos`,
con los marcadores de los campos de Ceuta y el fallback automático a Leaflet si la
clave no está configurada.

> El código ya está cableado: `CamposController` mira la variable de entorno
> `GOOGLE_MAPS_API_KEY`. Si existe, carga el script de Google Maps y
> `public/js/campos-map.js` la usa. Si no, vuelve a Leaflet/OpenStreetMap.

---

## 1. Crear el proyecto en Google Cloud

1. Entra en <https://console.cloud.google.com/>.
2. Pulsa el selector de proyecto (arriba a la izquierda) → **Nuevo proyecto**.
3. Nombre sugerido: `FastPlay Ceuta`.
4. (Opcional) Asocia tu cuenta de facturación. **Maps JavaScript API** tiene capa
   gratuita generosa (~28.000 cargas/mes con el crédito mensual de $200), pero
   requiere tener facturación habilitada para emitir la clave.

## 2. Habilitar las APIs necesarias

Menú lateral → **APIs y servicios → Biblioteca**. Habilita:

| API | Obligatoria | Para qué |
|---|---|---|
| **Maps JavaScript API** | Sí | Renderiza el mapa y marcadores en `/campos`. |
| **Places API** | Opcional | Autocompletado de direcciones cuando se crea un campo. |
| **Geocoding API** | Opcional | Convertir `address` → `lat/lng` automáticamente. |

## 3. Crear y restringir la clave

1. **APIs y servicios → Credenciales → Crear credenciales → Clave de API**.
2. Copia la clave que aparece (algo tipo `AIza...`).
3. Pulsa **Restringir clave** y configura:
   - **Restricciones de aplicación → Sitios web (HTTP referrers)**.
     Añade los orígenes desde donde se cargará el mapa, por ejemplo:
     ```
     http://localhost/*
     http://localhost:8000/*
     https://tu-dominio-easypanel.app/*
     https://www.tu-dominio.com/*
     ```
   - **Restricciones de API → Restringir clave** y marca solo:
     - Maps JavaScript API
     - (si las activaste) Places API, Geocoding API
4. Guarda. Sin restricciones la clave puede aparecer en cualquier sitio que
   inspeccione el HTML — restringirla es **obligatorio** en producción.

## 4. Configurar la clave en FastPlay

FastPlay carga variables de entorno desde un archivo `.env` (en la raíz del
proyecto) o desde las variables del sistema. Las del sistema mandan, así que en
producción puedes definirla en Easypanel/Docker.

### 4.1 Local (XAMPP, `php -S`, etc.)

1. Copia `.env.example` a `.env`:
   ```bash
   copy .env.example .env       # Windows
   cp .env.example .env          # macOS / Linux
   ```
2. Edita `.env` y pega tu clave:
   ```env
   GOOGLE_MAPS_API_KEY=AIzaSyD...tu_clave
   ```
3. Recarga `/campos`. Si la clave está bien, el mapa será de Google (verás el
   logo "Google" abajo a la izquierda). Si está vacía o falla, ves OpenStreetMap.

> `.env` está bloqueado en `public/.htaccess` (`FilesMatch \.(env|...)$`) y vive
> fuera de `public/`, así que nunca se sirve por web.

### 4.2 Easypanel / Docker

En el panel del servicio, sección **Environment** (o `docker run -e ...`):

```
GOOGLE_MAPS_API_KEY=AIzaSyD...tu_clave
APP_ENV=production
```

Reinicia el servicio. No hace falta tocar el Dockerfile: el loader de `.env`
respeta cualquier variable que ya exista en el entorno del proceso.

### 4.3 Apache (XAMPP) con `SetEnv`

Si no quieres usar `.env`, añade en `public/.htaccess` o en el VirtualHost:

```apache
SetEnv GOOGLE_MAPS_API_KEY AIzaSyD...tu_clave
```

## 5. Cómo el código usa la clave

- `app/controllers/CamposController.php`
  ```php
  $googleMapsKey = getenv('GOOGLE_MAPS_API_KEY') ?: '';
  ```
  Si no está vacía, inyecta el script `https://maps.googleapis.com/maps/api/js?key=...`
  y pasa `googleMapsEnabled = true` a la vista.

- `app/views/campos/index.php` renderiza el contenedor `#ceuta-map` con
  `data-map-provider="google"` o `"leaflet"`.

- `public/js/campos-map.js` lee el atributo y elige proveedor. En ambos casos
  centra el mapa en `35.8894, -5.3198` (centro de Ceuta) y pinta los marcadores
  con la `lat`/`lng` de cada campo de la base de datos.

- El CSP en `config/config.php` ya permite `maps.googleapis.com`,
  `maps.gstatic.com` y `*.googleusercontent.com`, así que no hay que tocar nada.

## 6. Datos de los campos

Los campos viven en la tabla `fields`. Las columnas relevantes para el mapa:

| Columna | Tipo | Notas |
|---|---|---|
| `name` | TEXT | Nombre visible en el popup |
| `address` | TEXT | Aparece debajo del nombre en el popup |
| `city` | TEXT | Siempre `Ceuta` |
| `latitude` | REAL | Coordenada en formato decimal (ej. `35.8883`) |
| `longitude` | REAL | Coordenada en formato decimal (ej. `-5.3162`) |
| `maps_url` | TEXT | Enlace "Abrir en Google Maps" (opcional) |
| `description` | TEXT | Texto descriptivo del popup |

El seeder ya carga 6 campos reales de Ceuta:

1. Estadio Municipal Alfonso Murube
2. Campo Jose Martinez Pirri
3. Campo Federativo Jose Benoliel
4. Campo de futbol del Principe
5. Complejo Deportivo Diaz-Flor
6. Polideportivo La Libertad

Para añadir uno nuevo desde la UI: inicia sesión como admin (`admin@fastplay.es`
/ `Admin1234!`) y entra en `/campos/create`. O insértalo directo en la BD con
SQL.

## 7. Validar que funciona

1. `php -S localhost:8000 router.php` (o tu servidor habitual).
2. Abre `http://localhost:8000/campos`.
3. Comprueba en DevTools → Network que se carga
   `https://maps.googleapis.com/maps/api/js?key=AIza...`.
4. Si ves un cartel "Para usar las funciones de Google Maps Platform debes
   habilitar la facturación" o "RefererNotAllowedMapError", revisa el paso 3
   (restricciones) y vuelve a probar tras 1-2 minutos.

## 8. Costes — guía rápida

- Cada carga del mapa cuenta como una "Map Load" (~$7 / 1000 cargas).
- El crédito gratuito mensual de Google ($200) cubre ~28.500 cargas. Para una
  app amateur estás muy lejos del tope.
- Si te preocupa: en **Cuotas** del panel puedes poner un límite duro de
  llamadas/día para evitar sorpresas.

## 9. Troubleshooting

| Síntoma | Causa probable | Fix |
|---|---|---|
| Mapa gris con marca de agua "for development purposes only" | Clave sin facturación habilitada | Activa facturación en GCP |
| `RefererNotAllowedMapError` en consola | El dominio no está en las restricciones | Añade el dominio en Credenciales |
| Carga Leaflet en vez de Google | `GOOGLE_MAPS_API_KEY` vacía | Revisa `.env` o variables del entorno |
| Marcadores no aparecen | Algún campo sin `latitude`/`longitude` | Edita el campo en `/campos/create` o SQL |
