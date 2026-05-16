(function() {
  'use strict';

  if (window.FastPlayScrollAnim) return;
  window.FastPlayScrollAnim = {
    init: init,
  };

  let _config;
  let video, progressBar, ticking, reduceMotion;
  let targetTime = 0;
  let renderedTime = 0;
  let scrubRaf = 0;
  let lastSeekAt = 0;

  function init(config) {
    _config = config || {};
    video = document.getElementById('heroVideo');
    if (!video) return;
    progressBar = document.getElementById('scrollProgress');
    reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    video.pause();
    video.currentTime = 0;
    video.addEventListener('loadedmetadata', function() {
      targetTime = 0;
      renderedTime = 0;
      updateScrollState();
    }, { once: true });
    if (video.readyState >= 1) markReady();
    else video.addEventListener('loadedmetadata', markReady, { once: true });
    video.addEventListener('loadeddata', markReady, { once: true });
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
    if (!reduceMotion && video && video.duration && isFinite(video.duration)) {
      targetTime = Math.min(video.duration * scrollFraction, Math.max(video.duration - 0.04, 0));
      requestScrub();
    }
    if (_config.onScroll) _config.onScroll(scrollTop, scrollFraction);
  }

  function requestScrub() {
    if (scrubRaf) return;
    scrubRaf = requestAnimationFrame(scrubVideo);
  }

  function scrubVideo(now) {
    scrubRaf = 0;
    if (!video || !video.duration || !isFinite(video.duration)) return;

    const delta = targetTime - renderedTime;
    const absDelta = Math.abs(delta);
    if (absDelta < 0.012) {
      renderedTime = targetTime;
    } else {
      renderedTime += delta * 0.28;
    }

    // Limit seeks to roughly 30fps; browsers decode video seeks much more
    // smoothly when they are paced instead of fired on every scroll event.
    if (now - lastSeekAt > 32 || absDelta > 0.18) {
      video.currentTime = renderedTime;
      lastSeekAt = now;
    }

    if (Math.abs(targetTime - renderedTime) >= 0.012) {
      requestScrub();
    }
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
