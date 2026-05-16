(function() {
  'use strict';

  var navbar = document.querySelector('.fp-navbar');
  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function parseStat(raw) {
    var s = String(raw).trim();
    var m = s.match(/^([^\d\-,\.]*)([\-]?[\d\.,]+)([^\d]*)$/);
    if (!m) return null;
    var prefix = m[1] || '';
    var numRaw = m[2] || '';
    var suffix = m[3] || '';
    var n;
    if (numRaw.indexOf(',') !== -1) {
      n = parseFloat(numRaw.replace(/\./g, '').replace(',', '.'));
    } else {
      n = parseFloat(numRaw.replace(/\./g, ''));
    }
    if (!isFinite(n)) return null;
    var hasComma = numRaw.indexOf(',') !== -1;
    var decimals = hasComma ? (numRaw.split(',')[1] || '').length : 0;
    return { num: n, prefix: prefix, suffix: suffix, decimals: decimals };
  }

  function formatStat(value, parsed) {
    var fixed = value.toFixed(parsed.decimals);
    if (parsed.decimals > 0) fixed = fixed.replace('.', ',');
    if (parsed.decimals === 0 && /^[\-]?\d+$/.test(fixed) && Math.abs(value) >= 1000 && !parsed.suffix) {
      fixed = Number(fixed).toLocaleString('es-ES');
    }
    return parsed.prefix + fixed + parsed.suffix;
  }

  function animateNumber(el) {
    if (el.dataset.animated === '1') return;
    var parsed = parseStat(el.dataset.value || el.textContent);
    if (!parsed) return;
    el.dataset.animated = '1';
    if (reduceMotion) { el.textContent = formatStat(parsed.num, parsed); return; }
    var duration = 1200, start = performance.now();
    function tick(now) {
      var t = Math.min((now - start) / duration, 1);
      var eased = 1 - Math.pow(1 - t, 3);
      el.textContent = formatStat(parsed.num * eased, parsed);
      if (t < 1) requestAnimationFrame(tick);
      else el.textContent = formatStat(parsed.num, parsed);
    }
    requestAnimationFrame(tick);
  }

  FastPlayScrollAnim.init({
    onScroll: function(scrollTop) {
      if (navbar) {
        if (scrollTop > 60) navbar.classList.add('scrolled');
        else navbar.classList.remove('scrolled');
      }
    },
    onReveal: function(target) {
      var nums = target.querySelectorAll('.scroll-stat__num[data-value]');
      if (nums.length) nums.forEach(animateNumber);
    }
  });
})();
