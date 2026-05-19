# Guia Google Maps API para campos de Ceuta

## 1. Crear proyecto

1. Entra en Google Cloud Console.
2. Crea un proyecto llamado `Plataforma Deportiva Ceuta`.

## 2. Activar APIs

Activa estas APIs:

- Maps JavaScript API.
- Places API, opcional para busqueda de lugares.
- Geocoding API, opcional para convertir direcciones en coordenadas.

## 3. Crear y restringir API key

1. Ve a Credenciales.
2. Crea una clave de API.
3. Restringe la clave por dominio.
4. Restringe la clave a las APIs usadas.
5. No dejes la clave abierta sin restricciones.

## 4. Guardar la clave

Guarda la clave fuera del codigo:

```env
GOOGLE_MAPS_API_KEY=tu_api_key_aqui
```

En desarrollo local, si no existe `GOOGLE_MAPS_API_KEY`, FastPlay usa Leaflet/OpenStreetMap como fallback para no romper `/campos`.

## 5. Vista y JavaScript

La vista `app/views/campos/index.php` renderiza:

```html
<div id="ceuta-map" class="ceuta-map"></div>
```

`CamposController` carga Google Maps solo si encuentra `GOOGLE_MAPS_API_KEY`. El archivo `public/js/campos-map.js` inicializa el mapa, crea marcadores y conecta cada tarjeta lateral con su marcador.

## 6. Datos de campos

Los campos de Ceuta se guardan en la tabla `fields` con:

- `name`
- `address`
- `latitude`
- `longitude`
- `maps_url`
- `image`
- `description`

Antes de produccion, valida nombres, direcciones y coordenadas contra fuentes oficiales o Google Maps.
