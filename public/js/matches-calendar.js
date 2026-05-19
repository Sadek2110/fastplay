(function () {
  function pad(value) {
    return String(value).padStart(2, '0');
  }

  function keyFromDate(date) {
    return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate());
  }

  function statusLabel(status) {
    return {
      pending: 'Pendiente',
      confirmed: 'Jugando',
      finished: 'Jugado',
      cancelled: 'Cancelado'
    }[status] || status || 'Pendiente';
  }

  function initCalendar(root) {
    var matches = [];
    try {
      matches = JSON.parse(root.getAttribute('data-calendar-matches') || '[]');
    } catch (error) {
      matches = [];
    }

    var byDay = matches.reduce(function (map, match) {
      var raw = String(match.scheduled_at || '').slice(0, 10);
      if (!raw) return map;
      map[raw] = map[raw] || [];
      map[raw].push(match);
      return map;
    }, {});

    var title = root.querySelector('[data-calendar-title]');
    var grid = root.querySelector('[data-calendar-grid]');
    var dayTitle = root.querySelector('[data-calendar-day-title]');
    var dayList = root.querySelector('[data-calendar-day-list]');
    var cursor = new Date();
    var selected = keyFromDate(cursor);

    function renderDayList(key) {
      var list = byDay[key] || [];
      dayTitle.textContent = 'Partidos del ' + key.split('-').reverse().join('/');
      if (!list.length) {
        dayList.innerHTML = '<p class="fp-muted">No hay partidos programados para este dia.</p>';
        return;
      }
      dayList.innerHTML = list.map(function (match) {
        var time = String(match.scheduled_at || '').slice(11, 16) || match.t || '';
        return '<a class="calendar-match" href="' + window.FP_BASE_URL + '/matches/show/' + Number(match.id) + '">' +
          '<strong>' + match.h + ' vs ' + match.a + '</strong>' +
          '<span>' + time + ' - ' + (match.f || 'Campo de Ceuta a confirmar') + '</span>' +
          '<small>' + statusLabel(match.st) + '</small>' +
        '</a>';
      }).join('');
    }

    function render() {
      var month = cursor.getMonth();
      var year = cursor.getFullYear();
      var first = new Date(year, month, 1);
      var last = new Date(year, month + 1, 0);
      var startOffset = (first.getDay() + 6) % 7;
      var todayKey = keyFromDate(new Date());

      title.textContent = cursor.toLocaleDateString('es-ES', { month: 'long', year: 'numeric' });
      grid.innerHTML = '';

      for (var i = 0; i < startOffset; i += 1) {
        grid.insertAdjacentHTML('beforeend', '<span class="calendar-day is-empty"></span>');
      }

      for (var day = 1; day <= last.getDate(); day += 1) {
        var date = new Date(year, month, day);
        var key = keyFromDate(date);
        var hasMatch = Boolean(byDay[key]);
        var button = document.createElement('button');
        button.type = 'button';
        button.className = 'calendar-day' +
          (hasMatch ? ' has-match' : '') +
          (key === todayKey ? ' today' : '') +
          (key === selected ? ' is-selected' : '');
        button.textContent = day;
        button.addEventListener('click', function (selectedKey) {
          return function () {
            selected = selectedKey;
            render();
            renderDayList(selectedKey);
          };
        }(key));
        grid.appendChild(button);
      }
      renderDayList(selected);
    }

    root.querySelector('[data-calendar-prev]').addEventListener('click', function () {
      cursor = new Date(cursor.getFullYear(), cursor.getMonth() - 1, 1);
      selected = keyFromDate(new Date(cursor.getFullYear(), cursor.getMonth(), 1));
      render();
    });

    root.querySelector('[data-calendar-next]').addEventListener('click', function () {
      cursor = new Date(cursor.getFullYear(), cursor.getMonth() + 1, 1);
      selected = keyFromDate(new Date(cursor.getFullYear(), cursor.getMonth(), 1));
      render();
    });

    render();
  }

  document.addEventListener('DOMContentLoaded', function () {
    window.FP_BASE_URL = window.FP_BASE_URL || '';
    document.querySelectorAll('[data-calendar-matches]').forEach(initCalendar);
  });
})();
