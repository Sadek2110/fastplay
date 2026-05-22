(function () {
  var state = {
    el: null,
    fields: [],
    markers: {},
    map: null,
    provider: 'leaflet'
  };

  // Football-pitch pin: green teardrop with top-down pitch lines inside
  var MARKER_SVG =
    '<svg xmlns="http://www.w3.org/2000/svg" width="34" height="44" viewBox="0 0 34 44">' +
    '<path d="M17 0C7.6 0 0 7.6 0 17c0 6 3 11.3 7.6 14.5L17 44l9.4-12.5C30.9 28.3 34 23 34 17 34 7.6 26.4 0 17 0z" fill="#22c55e"/>' +
    '<rect x="8" y="9" width="18" height="16" rx="2" fill="none" stroke="rgba(255,255,255,0.95)" stroke-width="1.5"/>' +
    '<circle cx="17" cy="17" r="4.5" fill="none" stroke="rgba(255,255,255,0.95)" stroke-width="1.5"/>' +
    '<line x1="8" y1="17" x2="12.5" y2="17" stroke="rgba(255,255,255,0.75)" stroke-width="1.2"/>' +
    '<line x1="21.5" y1="17" x2="26" y2="17" stroke="rgba(255,255,255,0.75)" stroke-width="1.2"/>' +
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
          scaledSize: new google.maps.Size(34, 44),
          anchor: new google.maps.Point(17, 44)
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
      iconSize: [34, 44],
      iconAnchor: [17, 44],
      popupAnchor: [0, -46]
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
