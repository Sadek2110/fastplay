# Plan de Implementación — arreglos4.md
> Generado: 2026-05-22 | Base: `Prompts/arreglos4.md`

---

## Estado general

| # | Página / Área | Estado | Notas |
|---|---|---|---|
| 1 | Landing — navbar topbar | ✅ Hecho | Commit f18dee5 |
| 2 | Landing — quitar barra de progreso | ✅ Hecho | Commit e547b96 |
| 3 | Dashboard — h1 glow verde+dorado | ✅ Hecho | `.fp-name-glow` |
| 4 | Dashboard — carta FIFA clicable | ✅ Hecho | Link a `/profile/edit` |
| 5 | Dashboard — paneles izquierda rediseñados | ✅ Hecho | Equipo, partidos, capitán, CTA |
| 6 | Dashboard — sección buscar partido | ✅ Hecho | `.fp-find-match-cta` |
| 7 | Dashboard — notificaciones mejoradas | ✅ Hecho | `.fp-notif-item` con dot |
| 8 | Partidos — cards con fecha coloreada | ✅ Hecho | `.fp-match-card` |
| 9 | Partidos — badges de equipo + marcador | ✅ Hecho | `.fp-match-team-badge` |
| 10 | Partidos — animaciones de entrada | ✅ Hecho | `fp-match-in` keyframe |
| 11 | Equipos — diagrama de campo CSS | ✅ Hecho | `.fp-pitch` |
| 12 | Equipos — lista roster clicable | ✅ Hecho | `.fp-roster-item` |
| 13 | Equipos — carta jugador al click | ✅ Hecho | `.fp-player-detail` |
| 14 | Campos — mapa más pequeño | ✅ Hecho | `max-height: 580px` |
| 15 | Equipos — stats del equipo (abajo) | ❌ Pendiente | Requiere backend |
| 16 | Equipos — capitán en cima del campo | 🟡 Parcial | Borde dorado, no posición fija arriba |
| 17 | Partidos — calendario mejorado | 🟡 Parcial | JS existente, falta "sin partidos" message |
| 18 | Campos — fotos de campos | ⚠️ Datos | Placeholder OK; faltan imágenes reales |
| 19 | General — modo oscuro más amarillo | ❌ Pendiente | CSS global |
| 20 | General — modo claro verde/blanco | ❌ Pendiente | CSS global |
| 21 | General — botón volver en todas las páginas | 🟡 Parcial | Partial `back-button` existe, no en todas |
| 22 | General — animaciones/glass global | ❌ Pendiente | Hover en cards, inputs, etc. |
| 23 | Responsive — verificar tablet/móvil | ❌ Pendiente | Testing pendiente |

---

## Tareas pendientes detalladas

### PRIORIDAD ALTA

---

#### P1 — Estadísticas del equipo (parte inferior de `/teams/show`)

**Archivo:** `app/views/teams/show.php`  
**Datos necesarios:** `TeamsController` debe pasar `$teamStats` con:
- `matches_played`, `wins`, `draws`, `losses`
- `goals_for`, `goals_against`
- Liga y posición si aplica

**Paso 1 — Backend:** En `TeamsController::show()` añadir:
```php
'teamStats' => $this->safe(fn () => $equipo->stats((int) $team['id']), null),
```

**Paso 2 — Modelo:** Añadir método `Equipo::stats(int $teamId): array` que consulte:
```sql
SELECT
  COUNT(*) AS matches_played,
  SUM(CASE WHEN (home_team_id=? AND home_score > away_score) OR (away_team_id=? AND away_score > home_score) THEN 1 ELSE 0 END) AS wins,
  SUM(CASE WHEN home_score = away_score THEN 1 ELSE 0 END) AS draws,
  SUM(CASE WHEN (home_team_id=? AND home_score < away_score) OR (away_team_id=? AND away_score < home_score) THEN 1 ELSE 0 END) AS losses,
  SUM(CASE WHEN home_team_id=? THEN home_score ELSE away_score END) AS goals_for,
  SUM(CASE WHEN home_team_id=? THEN away_score ELSE home_score END) AS goals_against
FROM matches
WHERE status='finished' AND (home_team_id=? OR away_team_id=?)
```

**Paso 3 — Vista:** Añadir sección `<section class="fp-team-stats-grid">` al final de `show.php` con cards visuales.

---

#### P2 — Capitán posicionado arriba del campo

**Archivo:** `app/views/teams/show.php`  
**Descripción:** El capitán debe aparecer en la fila superior del campo (DEL o zona de ataque) y marcado de forma diferente visualmente.  
**Cambio:** Si el capitán es portero, colocarlo en su zona. Si es cualquier otro rol, añadir una "zona capitán" fija en la cima del campo.  
**CSS:** `.fp-pitch-row--captain` con fondo levemente dorado y el icono ⭐ más grande.

---

#### P3 — Calendario: mensaje "sin partidos" y microinteracciones

**Archivo:** `public/js/nav.js` o `public/js/calendar.js` (verificar dónde está el JS del calendario)  
**Descripción:**
- Cuando se pulse un día sin partidos → mostrar texto elegante: _"No hay partidos programados para este día"_
- Añadir animación de entrada al panel de día seleccionado
- Día actual: outline dorado más visible

---

#### P4 — Modo oscuro: más amarillo/dorado en componentes clave

**Archivo:** `public/css/app.css`  
**Descripción:** Aplicar más presencia del amarillo `#facc15` / dorado `#d6a93c` en modo oscuro:
- Bordes de cards activas / hover
- Iconos secundarios
- `.fp-h2` con acento dorado sutil
- Fondos de stat-cards con tinte dorado
- Botón primario con shimmer dorado en hover

---

#### P5 — Modo claro: refuerzo verde/blanco

**Archivo:** `public/css/app.css`  
**Descripción:**
- `[data-theme="light"] .fp-glass` → fondo blanco puro con borde verde suave
- `[data-theme="light"] .fp-h1, .fp-h2` → color `#1f2a24`
- Cards con sombra verde muy suave
- Status badges más vivos en claro

---

### PRIORIDAD MEDIA

---

#### P6 — Botón "volver" universal

**Partial existente:** `app/views/partials/back-button.php`  
**Páginas que lo necesitan y no lo tienen:**
- `dashboard/index.php` — no aplica (es la home)
- `campos/show.php` → añadir `back-button` a Campos
- `leagues/show.php` → añadir si existe
- `profile/edit.php` → añadir con href a dashboard
- `matches/create.php` → añadir

**Implementación:** Revisar cada vista y añadir `<?php $this->partial('back-button', ['href' => url('...')]); ?>` donde falte.

---

#### P7 — Animaciones hover globales en cards

**Archivo:** `public/css/app.css`  
**Descripción:** Añadir a `.fp-glass:hover` y `.fp-panel:hover` una transición suave de `translateY(-2px)` + `box-shadow` más pronunciada. Ya hay algo para light mode pero no es consistente en dark.

```css
.fp-glass, .fp-panel {
  transition: transform .25s cubic-bezier(.22,1,.36,1), box-shadow .25s;
}
.fp-glass:hover, .fp-panel:hover {
  transform: translateY(-2px);
  box-shadow: 0 16px 40px rgba(0,0,0,.28);
}
```

---

#### P8 — Fotos de campos (datos)

**Estado:** El código ya soporta fotos (`fp-field-img` con `background-image`). Usa `hero-pitch.png` como placeholder si no hay imagen.  
**Pendiente (no es código):** Subir imágenes reales de los campos a `public/images/campos/` y actualizar la columna `image` en la tabla `fields` de la base de datos.  
**Aviso en admin:** Añadir en `campos/create.php` y `campos/edit.php` un hint: _"Sin foto: se usará imagen genérica"_.

---

#### P9 — Responsive: tablet y móvil

**Archivos:** `public/css/app.css`  
**Verificar y corregir:**
- `fp-dashboard-bottom` en tablet (< 1100px) → ya colapsa a 1 col
- `fp-team-pitch-layout` en móvil (< 900px) → ya colapsa a 1 col
- `fp-match-card` en móvil → verificar que fecha+teams+meta no se apilen mal
- Landing topbar en móvil → verificar que logo y botones quepan

---

### PRIORIDAD BAJA

---

#### P10 — Unificación visual de inputs, badges y estados

**Archivo:** `public/css/app.css`  
**Descripción:**
- `.fp-status-pending`, `.fp-status-confirmed`, etc. → colores más vivos y consistentes
- `.fp-input` → añadir efecto glow verde al focus
- `.fp-badge` → usar el mismo dorado/verde según contexto

---

#### P11 — Microinteracciones en botones importantes

**Descripción:** Botones `.fp-btn-primary` con efecto de shimmer/pulso al hover:
```css
.fp-btn-primary { overflow: hidden; position: relative; }
.fp-btn-primary::after {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,.15) 50%, transparent 100%);
  transform: translateX(-100%);
  transition: transform .5s;
}
.fp-btn-primary:hover::after { transform: translateX(100%); }
```

---

## Datos necesarios (no código)

| Dato | Dónde | Estado |
|---|---|---|
| Fotos de campos | Tabla `fields`, columna `image` | ⚠️ Pendiente |
| Avatares de jugadores | Tabla `users`, columna `avatar` | ⚠️ Parcial |
| Escudos de equipos (img, no emoji) | Tabla `teams`, columna `badge` | ⚠️ Solo emoji |
| Estadísticas de partidos por equipo | Tabla `matches` | ✅ Datos existen |

---

## Orden de ejecución sugerido

```
1. P1 (stats equipo backend+vista)     ← impacto visual alto
2. P4 + P5 (dark/light mode CSS)       ← mejora percibida inmediata
3. P6 (back buttons)                   ← UX básica
4. P3 (calendario mejora)              ← completar partidos
5. P7 (hover global)                   ← polish
6. P2 (capitán en cima del campo)      ← detalle visual
7. P9 (responsive testing)             ← QA final
8. P10 + P11 (polish global)           ← refinamiento
```

---

## Archivos clave del proyecto

| Función | Archivo |
|---|---|
| Dashboard | `app/views/dashboard/index.php` + `DashboardController.php` |
| Equipos | `app/views/teams/show.php` + `TeamsController.php` + `Equipo.php` |
| Partidos | `app/views/matches/index.php` + `MatchesController.php` |
| Campos | `app/views/campos/index.php` + `CamposController.php` |
| Landing | `app/views/home/index.php` + `HomeController.php` |
| Navbar | `app/views/partials/navbar.php` |
| CSS principal | `public/css/app.css` (3844 líneas) |
| CSS landing | `public/css/scroll-anim.css` |
| JS navegación | `public/js/nav.js` |