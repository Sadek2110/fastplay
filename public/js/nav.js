(function () {
  document.addEventListener('DOMContentLoaded', function () {
    var sidebar = document.getElementById('fpSidebar');
    var toggle = document.querySelector('[data-nav-toggle]');
    if (!sidebar || !toggle) return;

    // Toggle sidebar on mobile
    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      var open = sidebar.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    // Close sidebar when clicking a link (mobile)
    sidebar.querySelectorAll('a, button[type="submit"]').forEach(function (el) {
      el.addEventListener('click', function () {
        if (window.innerWidth < 1024) {
          sidebar.classList.remove('open');
          toggle.setAttribute('aria-expanded', 'false');
        }
      });
    });

    // Close sidebar when clicking outside (mobile)
    document.addEventListener('click', function (e) {
      if (window.innerWidth < 1024 && sidebar.classList.contains('open')) {
        if (!sidebar.contains(e.target)) {
          sidebar.classList.remove('open');
          toggle.setAttribute('aria-expanded', 'false');
        }
      }
    });

    // Notification badge polling
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
