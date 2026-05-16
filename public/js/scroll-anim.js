(function() {
  'use strict';

  if (window.FastPlayScrollAnim) return;
  window.FastPlayScrollAnim = {
    init: init,
  };

  let _config;
  let video, progressBar, ticking, reduceMotion;
  let lastScrollTop = 0;
  let lastScrollAt = 0;
  let playbackResetTimer = 0;

  function init(config) {
    _config = config || {};
    video = document.getElementById('heroVideo');
    if (!video) return;
    progressBar = document.getElementById('scrollProgress');
    reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (video.readyState >= 1) markReady();
    else video.addEventListener('loadedmetadata', markReady, { once: true });
    video.addEventListener('loadeddata', markReady, { once: true });
    if (reduceMotion) {
      video.pause();
    } else {
      video.play().catch(function() {});
    }
    window.addEventListener('scroll', onScroll, { passive: true });

    setTimeout(function() {
      if (video && !video.classList.contains('is-ready')) {
        video.classList.add('is-ready');
      }
    }, 4000);

    initObserver();
    if (_config.onReady) _config.onReady();
  }

  function markReady() {
    if (video) video.classList.add('is-ready');
  }

  function onScroll() {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(function() {
      updateScrollState();
      ticking = false;
    });
  }

  function updateScrollState() {
    const scrollTop = window.scrollY;
    const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
    const scrollFraction = maxScroll > 0 ? Math.min(scrollTop / maxScroll, 1) : 0;
    if (progressBar) progressBar.style.width = (scrollFraction * 100) + '%';
    if (!reduceMotion && video) {
      updatePlaybackRate(scrollTop);
    }
    if (_config.onScroll) _config.onScroll(scrollTop, scrollFraction);
  }

  function updatePlaybackRate(scrollTop) {
    const now = performance.now();
    if (!lastScrollAt) {
      lastScrollAt = now;
      lastScrollTop = scrollTop;
      return;
    }
    const elapsed = Math.max(now - lastScrollAt, 16);
    const velocity = Math.abs(scrollTop - lastScrollTop) / elapsed;
    const rate = Math.min(1.75, Math.max(0.75, 0.9 + velocity * 0.22));
    video.playbackRate = rate;
    if (video.paused) video.play().catch(function() {});
    lastScrollAt = now;
    lastScrollTop = scrollTop;
    window.clearTimeout(playbackResetTimer);
    playbackResetTimer = window.setTimeout(function() {
      if (video) video.playbackRate = 1;
    }, 180);
  }

  function initObserver() {
    const sections = document.querySelectorAll('.scroll-section__inner');
    const observer = new IntersectionObserver(function(entries) {
      entries.forEach(function(e) {
        if (!e.isIntersecting) return;
        e.target.classList.add('visible');
        if (_config.onReveal) _config.onReveal(e.target);
      });
    }, { threshold: 0.15, rootMargin: '-5% 0px' });
    sections.forEach(function(s) { observer.observe(s); });
  }
})();
