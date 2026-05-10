(function() {
  'use strict';

  if (window.FastPlayScrollAnim) return;
  window.FastPlayScrollAnim = {
    init: init,
    drawFrame: drawFrame,
    resizeCanvas: resizeCanvas,
  };

  let _config;
  let canvas, ctx, progressBar, frames, currentFrame, ticking, reduceMotion;
  const FRAME_COUNT = 192;

  function init(config) {
    _config = config || {};
    canvas = document.getElementById('frameCanvas');
    if (!canvas) return;
    ctx = canvas.getContext('2d');
    progressBar = document.getElementById('scrollProgress');
    reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    frames = new Array(FRAME_COUNT);
    resizeCanvas();
    drawFrame(0);

    if (reduceMotion) {
      loadFrame(0, function() {
        drawFrame(0);
        if (canvas) canvas.classList.add('is-ready');
      });
    } else {
      preloadFrames();
    }

    window.addEventListener('resize', function() { resizeCanvas(); drawFrame(currentFrame); });
    window.addEventListener('scroll', onScroll, { passive: true });

    // Safety: show canvas after 4s even if frames fail to load
    setTimeout(function() {
      if (canvas && !canvas.classList.contains('is-ready')) {
        canvas.classList.add('is-ready');
      }
    }, 4000);

    initObserver();
    if (_config.onReady) _config.onReady();
  }

  function frameUrl(i) {
    return (_config.framePath || '') + String(i + 1).padStart(4, '0') + '.png';
  }

  function loadFrame(i, onLoad) {
    if (frames[i]) {
      if (onLoad) {
        if (frames[i].complete) onLoad();
        else frames[i].addEventListener('load', onLoad, { once: true });
      }
      return;
    }
    const img = new Image();
    img.decoding = 'async';
    img.src = frameUrl(i);
    img.onload = function() { if (onLoad) onLoad(); };
    img.onerror = function() {
      console.warn('[FastPlay] Frame no cargado: ' + frameUrl(i));
      if (onLoad) onLoad();
    };
    frames[i] = img;
  }

  function preloadFrames() {
    let isCanvasReady = false;
    function markReady() {
      if (isCanvasReady) return;
      isCanvasReady = true;
      drawFrame(0);
      if (canvas) canvas.classList.add('is-ready');
    }
    loadFrame(0, markReady);
    if (frames[0] && frames[0].complete && frames[0].naturalWidth > 0) {
      markReady();
    }
    const initial = Math.min(24, FRAME_COUNT);
    for (let i = 1; i < initial; i++) {
      loadFrame(i, markReady);
    }
    let idx = initial;
    const batchSize = 8;
    function nextBatch() {
      const end = Math.min(idx + batchSize, FRAME_COUNT);
      for (; idx < end; idx++) loadFrame(idx);
      if (idx < FRAME_COUNT) {
        if (window.requestIdleCallback) requestIdleCallback(nextBatch, { timeout: 200 });
        else setTimeout(nextBatch, 60);
      }
    }
    if (window.requestIdleCallback) requestIdleCallback(nextBatch, { timeout: 200 });
    else setTimeout(nextBatch, 80);
  }

  function resizeCanvas() {
    if (!canvas) return;
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
  }

  function drawFrame(index) {
    if (!ctx || !canvas) return;
    let img = frames[index];
    if (!img || !img.complete || img.naturalWidth === 0) {
      for (let d = 1; d < FRAME_COUNT; d++) {
        const a = frames[index - d], b = frames[index + d];
        if (a && a.complete && a.naturalWidth > 0) { img = a; break; }
        if (b && b.complete && b.naturalWidth > 0) { img = b; break; }
      }
      if (!img) return;
    }
    const cw = canvas.width, ch = canvas.height;
    const iw = img.naturalWidth, ih = img.naturalHeight;
    const scale = Math.max(cw / iw, ch / ih);
    const dw = iw * scale, dh = ih * scale;
    const dx = (cw - dw) / 2, dy = (ch - dh) / 2;
    ctx.clearRect(0, 0, cw, ch);
    ctx.drawImage(img, dx, dy, dw, dh);
  }

  function onScroll() {
    if (ticking) return;
    ticking = true;
    requestAnimationFrame(function() {
      const scrollTop = window.scrollY;
      const maxScroll = document.documentElement.scrollHeight - window.innerHeight;
      const scrollFraction = maxScroll > 0 ? Math.min(scrollTop / maxScroll, 1) : 0;
      if (progressBar) progressBar.style.width = (scrollFraction * 100) + '%';
      if (!reduceMotion) {
        const frameIndex = Math.min(Math.floor(scrollFraction * FRAME_COUNT), FRAME_COUNT - 1);
        if (frameIndex !== currentFrame) { currentFrame = frameIndex; drawFrame(currentFrame); }
      }
      if (_config.onScroll) _config.onScroll(scrollTop, scrollFraction);
      ticking = false;
    });
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
