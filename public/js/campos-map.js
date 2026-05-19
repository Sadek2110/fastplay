(function () {
  document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('fp-fields-map');
    if (!el || typeof L === 'undefined') return;
    var fields = [];
    try { fields = JSON.parse(el.getAttribute('data-fields') || '[]'); } catch (e) {}
    var map = L.map(el).setView([35.8894, -5.3213], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap'
    }).addTo(map);
    fields.forEach(function (field) {
      var lat = Number(field.latitude);
      var lng = Number(field.longitude);
      if (!lat || !lng) return;
      L.marker([lat, lng]).addTo(map).bindPopup('<strong>' + field.name + '</strong><br>' + (field.address || field.city || ''));
    });
  });
})();
