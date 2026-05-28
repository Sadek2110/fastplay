/**
 * FastPlay · Mapa de campos de futbol
 * -----------------------------------
 * Usa la libreria Leaflet (OpenStreetMap) por defecto. Si el operador
 * ha configurado GOOGLE_MAPS_API_KEY, se cambia al proveedor de Google
 * Maps de forma transparente. Cada campo se pinta con un marcador SVG
 * propio: pin deportivo con mini campo y balon.
 *
 * Demuestra: API/libreria externa (Leaflet/Google Maps), parsing JSON
 * con try/catch, manipulacion DOM y eventos personalizados.
 */
(function () {
  'use strict';
  var state = {
    el: null,
    fields: [],
    markers: {},
    map: null,
    provider: 'leaflet'
  };

  // Sports venue pin: layered emerald marker with a tiny pitch and ball.
  var MARKER_SVG =
    '<svg xmlns="http://www.w3.org/2000/svg" width="42" height="54" viewBox="0 0 42 54" aria-hidden="true">' +
    '<defs>' +
    '<linearGradient id="pinFill" x1="10" y1="4" x2="32" y2="48" gradientUnits="userSpaceOnUse">' +
    '<stop stop-color="#34d399"/><stop offset="0.52" stop-color="#16a34a"/><stop offset="1" stop-color="#065f46"/>' +
    '</linearGradient>' +
    '<radialGradient id="pinGlow" cx="0" cy="0" r="1" gradientTransform="matrix(17 18 -18 17 20 16)" gradientUnits="userSpaceOnUse">' +
    '<stop stop-color="#ffffff" stop-opacity="0.55"/><stop offset="0.58" stop-color="#ffffff" stop-opacity="0.08"/><stop offset="1" stop-color="#ffffff" stop-opacity="0"/>' +
    '</radialGradient>' +
    '</defs>' +
    '<path d="M21 53s16-18.1 16-32C37 9.4 29.8 2 21 2S5 9.4 5 21c0 13.9 16 32 16 32z" fill="#022c22" opacity="0.32"/>' +
    '<path d="M21 50.5S35 34.2 35 21C35 10.7 28.7 4 21 4S7 10.7 7 21c0 13.2 14 29.5 14 29.5z" fill="url(#pinFill)" stroke="#f8fafc" stroke-opacity="0.95" stroke-width="2"/>' +
    '<path d="M21 50.5S35 34.2 35 21C35 10.7 28.7 4 21 4S7 10.7 7 21c0 13.2 14 29.5 14 29.5z" fill="url(#pinGlow)"/>' +
    '<circle cx="21" cy="21" r="12.5" fill="#052e16" stroke="rgba(255,255,255,0.92)" stroke-width="1.4"/>' +
    '<rect x="12.4" y="14.2" width="17.2" height="13.6" rx="2" fill="#0f7a3f" stroke="#dcfce7" stroke-width="1.25"/>' +
    '<line x1="21" y1="14.2" x2="21" y2="27.8" stroke="#bbf7d0" stroke-width="1"/>' +
    '<circle cx="21" cy="21" r="3.6" fill="none" stroke="#dcfce7" stroke-width="1"/>' +
    '<rect x="12.4" y="17.2" width="3.6" height="7.6" fill="none" stroke="#bbf7d0" stroke-width="0.9"/>' +
    '<rect x="26" y="17.2" width="3.6" height="7.6" fill="none" stroke="#bbf7d0" stroke-width="0.9"/>' +
    '<circle cx="27.8" cy="12.8" r="4.3" fill="#f8fafc" stroke="#064e3b" stroke-width="1.1"/>' +
    '<path d="M27.8 10.2l2.1 1.5-.8 2.5h-2.6l-.8-2.5 2.1-1.5z" fill="#111827"/>' +
    '<path d="M24.7 12.4l1.1-.7M30.9 12.4l-1.1-.7M25.7 16l.8-1.8M29.9 16l-.8-1.8" stroke="#111827" stroke-width="0.75" stroke-linecap="round"/>' +
    '</svg>';

  function readFields(el) {
    try {
      return JSON.parse(el.getAttribute('data-fields') || '[]').filter(function (field) {
        return Number(field.latitude) && Number(field.longitude);
      });
    } catch (error) {
      return [];
    }
  }

  function popupHtml(field) {
    return '<div class="map-info-window"><strong>' + field.name + '</strong><p>' +
      (field.address || field.city || 'Ceuta') + '</p><p>' +
      (field.description || 'Campo deportivo de Ceuta.') + '</p></div>';
  }

  function selectCard(id) {
    document.querySelectorAll('[data-field-card]').forEach(function (card) {
      card.classList.toggle('is-selected', Number(card.getAttribute('data-field-card')) === Number(id));
    });
  }

  function initGoogle() {
    if (!state.el || typeof google === 'undefined' || !google.maps) return false;
    state.map = new google.maps.Map(state.el, {
      zoom: 13,
      center: { lat: 35.8894, lng: -5.3198 },
      mapTypeControl: false,
      streetViewControl: false,
      fullscreenControl: true
    });
    var infoWindow = new google.maps.InfoWindow();
    var svgIconUrl = 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(MARKER_SVG);
    state.fields.forEach(function (field) {
      var marker = new google.maps.Marker({
        position: { lat: Number(field.latitude), lng: Number(field.longitude) },
        map: state.map,
        title: field.name,
        icon: {
          url: svgIconUrl,
          scaledSize: new google.maps.Size(42, 54),
          anchor: new google.maps.Point(21, 54)
        }
      });
      state.markers[field.id] = marker;
      marker.addListener('click', function () {
        infoWindow.setContent(popupHtml(field));
        infoWindow.open(state.map, marker);
        selectCard(field.id);
      });
    });
    return true;
  }

  function initLeaflet() {
    if (!state.el || typeof L === 'undefined') return false;
    state.map = L.map(state.el).setView([35.8894, -5.3198], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    }).addTo(state.map);

    var customIcon = L.divIcon({
      className: 'fp-map-marker',
      html: MARKER_SVG,
      iconSize: [42, 54],
      iconAnchor: [21, 54],
      popupAnchor: [0, -56]
    });

    state.fields.forEach(function (field) {
      var marker = L.marker([Number(field.latitude), Number(field.longitude)], { icon: customIcon })
        .addTo(state.map)
        .bindPopup(popupHtml(field));
      marker.on('click', function () { selectCard(field.id); });
      state.markers[field.id] = marker;
    });
    // fp-fade animation lasts 600ms; invalidateSize after it ends so Leaflet
    // recalculates bounds once the CSS transform on <main> has settled.
    setTimeout(function () { state.map.invalidateSize(); }, 700);
    return true;
  }

  function focusField(fieldId) {
    var field = state.fields.find(function (item) { return Number(item.id) === Number(fieldId); });
    if (!field || !state.map) return;
    selectCard(field.id);
    if (state.provider === 'google' && state.markers[field.id]) {
      state.map.panTo({ lat: Number(field.latitude), lng: Number(field.longitude) });
      google.maps.event.trigger(state.markers[field.id], 'click');
    } else if (state.markers[field.id]) {
      state.map.setView([Number(field.latitude), Number(field.longitude)], 15);
      state.markers[field.id].openPopup();
    }
  }

  function bindCards() {
    document.querySelectorAll('[data-field-card]').forEach(function (card) {
      card.addEventListener('click', function (event) {
        if (event.target.closest('a')) return;
        focusField(card.getAttribute('data-field-card'));
      });
    });
  }

  function init() {
    state.el = document.getElementById('ceuta-map');
    if (!state.el) return;
    state.fields = readFields(state.el);
    state.provider = state.el.getAttribute('data-map-provider') || 'leaflet';
    bindCards();
    if (state.provider === 'google' && initGoogle()) return;
    state.provider = 'leaflet';
    initLeaflet();
  }

  window.initCeutaMap = function () {
    if (state.el && state.provider === 'google') {
      initGoogle();
    }
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
