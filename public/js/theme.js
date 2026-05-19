(function () {
  var root = document.documentElement;
  var stored = localStorage.getItem('theme');
  var initial = stored || 'dark';
  root.setAttribute('data-theme', initial);

  function syncIcon() {
    var btn = document.querySelector('[data-theme-toggle]');
    if (!btn) return;
    var icon = btn.querySelector('i');
    if (!icon) return;
    icon.className = root.getAttribute('data-theme') === 'light' ? 'bi bi-moon' : 'bi bi-sun';
  }

  document.addEventListener('click', function (event) {
    var btn = event.target.closest('[data-theme-toggle]');
    if (!btn) return;
    var next = root.getAttribute('data-theme') === 'light' ? 'dark' : 'light';
    root.setAttribute('data-theme', next);
    localStorage.setItem('theme', next);
    syncIcon();
  });

  document.addEventListener('DOMContentLoaded', syncIcon);
})();
