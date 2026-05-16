(function() {
  'use strict';

  if (window.FastPlayScrollAnim) return;
  window.FastPlayScrollAnim = { init: init };

  let _config;
  let canvas, ctx, progressBar, reduceMotion;
  let frames = [];
  let frameCount = 0;
  let currentFrame = -1;
  let ticking = false;
  let canvasW = 0;
  let canvasH = 0;

  function init(config) {
    _config = config || {};
    canvas = document.getElementById('frameCanvas');
    if (!canvas) return;
    ctx = canvas.getContext('2d', { alpha: false });
    progressBar = document.getElementById('scrollProgress');
    reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    frameCount = Math.max(1, parseInt(canvas.dataset.frameCount || '192', 10) || 192);
    frames = new Array(frameCount);

    resizeCanvas();
    loadFrame(0, function() {
      drawFrame(0);
      canvas.classList.add('is-ready');
    });

    if (!reduceMotion) preloadFrames();

    window.addEventListener('resize', function() {
      resizeCanvas();
      drawFrame(Math.max(currentFrame, 0));
    });
    window.addEventListener('scroll', onScroll, { passive: true });

    initObserver();
    updateScrollState();
    if (_config.onReady) _config.onReady();
  }

  function frameUrl(index) {
    return (canvas.dataset.framePath || '') + String(index + 1).padStart(4, '0') + '.webp';
  }

  function loadFrame(index, onLoad) {
    if (frames[index]) {
      if (onLoad) {
        if (frames[index].complete) onLoad();
        else frames[index].addEventListener('load', onLoad, { once: true });
      }
      return;
    }
    const img = new Image();
    img.decoding = 'async';
    img._frameIndex = index;
    img.onload = function() { if (onLoad) onLoad(); };
    img.src = frameUrl(index);
    frames[index] = img;
  }

  function preloadFrames() {
    const eager = Math.min(32, frameCount);
    for (let i = 1; i < eager; i++) loadFrame(i);

    let index = eager;
    function batch() {
      const end = Math.min(index + 12, frameCount);
      for (; index < end; index++) loadFrame(index);
      if (index < frameCount) {
        if (window.requestIdleCallback) requestIdleCallback(batch, { timeout: 250 });
        else setTimeout(batch, 50);
      }
    }
    if (window.requestIdleCallback) requestIdleCallback(batch, { timeout: 250 });
    else setTimeout(batch, 80);
  }

  function resizeCanvas() {
    const dpr = Math.min(window.devicePixelRatio || 1, 1.5);
    canvasW = Math.round(window.innerWidth * dpr);
    canvasH = Math.round(window.innerHeight * dpr);
    canvas.width = canvasW;
    canvas.height = canvasH;
  }

  function drawFrame(index) {
    if (!ctx) return;
    index = Math.max(0, Math.min(frameCount - 1, index));
    let img = frames[index];
    if (!img) {
      loadFrame(index, function() { drawFrame(index); });
      img = nearestLoadedFrame(index);
    }
    if (!img || !img.complete || img.naturalWidth === 0) return;
    if (img._frameIndex === currentFrame) return;

    currentFrame = img._frameIndex;
    const iw = img.naturalWidth;
    const ih = img.naturalHeight;
    const scale = Math.max(canvasW / iw, canvasH / ih);
    const dw = iw * scale;
    const dh = ih * scale;
    const dx = (canvasW - dw) / 2;
    const dy = (canvasH - dh) / 2;
    ctx.drawImage(img, dx, dy, dw, dh);
  }

  function nearestLoadedFrame(index) {
    for (let distance = 1; distance < frameCount; distance++) {
      const previous = frames[index - distance];
      if (previous && previous.complete && previous.naturalWidth > 0) return previous;
      const next = frames[index + distance];
      if (next && next.complete && next.naturalWidth > 0) return next;
    }
    return null;
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
    if (!reduceMotion) {
      const frameIndex = Math.min(frameCount - 1, Math.round(scrollFraction * (frameCount - 1)));
      drawFrame(frameIndex);
      loadFrame(Math.min(frameCount - 1, frameIndex + 1));
      loadFrame(Math.min(frameCount - 1, frameIndex + 2));
    }
    if (_config.onScroll) _config.onScroll(scrollTop, scrollFraction);
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
