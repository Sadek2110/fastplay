(function () {
  document.addEventListener('DOMContentLoaded', function () {
    var toggle = document.querySelector('[data-nav-toggle]');
    var menu = document.querySelector('[data-nav-menu]');
    if (!toggle || !menu) return;
    toggle.addEventListener('click', function () {
      var open = menu.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    menu.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        menu.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      });
    });

    var badge = document.querySelector('[data-notification-badge]');
    if (badge) {
      setInterval(function () {
        fetch(window.location.origin + window.location.pathname.split('/').slice(0, -1).join('/') + '/notification/unreadCount')
          .then(function (r) { return r.ok ? r.json() : null; })
          .then(function (data) {
            if (!data) return;
            badge.textContent = data.count;
            badge.hidden = Number(data.count) <= 0;
          })
          .catch(function () {});
      }, 60000);
    }
  });
})();
