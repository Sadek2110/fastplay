<link rel="stylesheet" href="<?= asset('css/scroll-anim.css') ?>">
<style>
/* ====== PAGE-SPECIFIC: hide nav/footer, glass cards ====== */
.fp-navbar, .fp-footer, .fp-tabs, .fp-bg-glow { display: none !important; }

.scroll-card {
  background: rgba(6,13,9,.55);
  backdrop-filter: blur(28px) saturate(140%);
  -webkit-backdrop-filter: blur(28px) saturate(140%);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 24px; padding: 36px 32px;
  box-shadow: 0 24px 80px rgba(0,0,0,.5); position: relative;
}
.scroll-card--green {
  border-color: rgba(34,197,94,.25); background: rgba(6,30,14,.55);
  box-shadow: 0 24px 80px rgba(0,0,0,.5), 0 0 60px rgba(22,163,74,.08);
}
.scroll-section--left .scroll-card::after,
.scroll-section--right .scroll-card::after {
  content: ''; position: absolute; top: 18%; bottom: 18%; width: 2px;
  background: linear-gradient(180deg, transparent, rgba(74,222,128,.5), transparent);
  border-radius: 2px;
}
.scroll-section--left .scroll-card::after  { right: -1px; }
.scroll-section--right .scroll-card::after { left: -1px; }

/* Feature list override (list-based, not card-based) */
.scroll-features {
  list-style: none; padding: 0; margin: 0 0 28px;
  display: flex; flex-direction: column; gap: 14px;
}
.scroll-features li { display: flex; align-items: center; gap: 12px; font-size: 14px; color: #e5e7eb; }
.scroll-features li .icon {
  width: 32px; height: 32px; border-radius: 10px;
  background: rgba(34,197,94,.12); border: 1px solid rgba(34,197,94,.2);
  display: flex; align-items: center; justify-content: center;
  font-size: 16px; flex-shrink: 0;
}

/* Stats override (grid-based) */
.scroll-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-top: 24px; }
.scroll-stat { text-align: center; padding: 16px 8px; border-radius: 16px; background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.06); }
.scroll-stat__num { font-size: 28px; font-weight: 900; color: #4ade80; line-height: 1; text-shadow: none; }
.scroll-stat__label { font-size: 10px; color: #6b7280; text-transform: uppercase; letter-spacing: .06em; margin-top: 6px; text-shadow: none; font-weight: 600; }

/* Reset section inner — card handles glass */
.scroll-section__inner {
  max-width: 460px; background: none; border: none;
  backdrop-filter: none; -webkit-backdrop-filter: none;
  box-shadow: none; padding: 0; border-radius: 0;
}
.scroll-section--left  .scroll-section__inner,
.scroll-section--right .scroll-section__inner,
.scroll-section--center .scroll-section__inner { background: none; }
.scroll-section--left  .scroll-section__inner { transform: translateX(-80px) scale(.96); }
.scroll-section--right .scroll-section__inner { transform: translateX(80px) scale(.96); }
.scroll-section--center .scroll-section__inner { transform: translateY(60px) scale(.96); max-width: 560px; }
.scroll-section__inner.visible { opacity: 1; transform: translate(0,0) scale(1); }

.scroll-hint { bottom: 40px; }

/* ====== RESPONSIVE ====== */
@media (max-width: 900px) {
  .scroll-section, .scroll-section--left, .scroll-section--right { justify-content: center; padding: 60px 20px; }
  .scroll-section--left .scroll-section__inner,
  .scroll-section--right .scroll-section__inner { transform: translateY(60px) scale(.96); max-width: 560px; }
  .scroll-section--left .scroll-card::after,
  .scroll-section--right .scroll-card::after { display: none; }
  .scroll-anim__sides { background: none; }
}
@media (max-width: 700px) {
  .scroll-card { padding: 28px 22px; border-radius: 20px; }
  .scroll-dots { display: none; }
  .scroll-stats { grid-template-columns: 1fr; }
}

@media (prefers-reduced-motion: reduce) {
  .scroll-section__inner, .scroll-stagger > *,
  .scroll-feature-item, .scroll-feature-item__icon, .scroll-hint {
    animation: none !important; transition: none !important;
    transform: none !important; opacity: 1 !important;
  }
}
</style>

<div class="scroll-anim">

  <div class="scroll-progress" id="scrollProgress"></div>

  <div class="scroll-anim__canvas-wrap">
    <canvas id="frameCanvas"></canvas>
  </div>

  <div class="scroll-anim__vignette"></div>
  <div class="scroll-anim__sides"></div>

  <div class="scroll-dots" id="scrollDots">
    <div class="scroll-dot active" data-section="0" data-label="Inicio"></div>
    <div class="scroll-dot" data-section="1" data-label="Comunidad"></div>
    <div class="scroll-dot" data-section="2" data-label="Funciones"></div>
    <div class="scroll-dot" data-section="3" data-label="Liga Pro"></div>
    <div class="scroll-dot" data-section="4" data-label="Únete"></div>
  </div>

  <div class="scroll-anim__content">

    <section class="scroll-section scroll-section--left" id="section-0">
      <div class="scroll-section__inner">
        <div class="scroll-card">
          <div class="scroll-eyebrow">
            <span class="fp-pulse-dot" style="width:7px;height:7px;border-radius:50%;background:#4ade80;display:inline-block;"></span>
            Temporada 2026
          </div>
          <h1 class="scroll-title">¿Te apetece<br><span class="fp-gradient-text">jugar?</span></h1>
          <p class="scroll-desc">
            FastPlay conecta jugadores, organiza partidos en campos reales y lleva el fútbol amateur al siguiente nivel.
            <strong style="color:#fff;">En cualquier lugar. Para todos.</strong>
          </p>
          <div class="scroll-cta-row">
            <a href="<?= url('auth/register') ?>" class="fp-btn fp-btn-primary fp-btn-glow" style="padding:14px 28px;font-size:15px;">Empieza gratis →</a>
            <a href="<?= url('leagues') ?>" class="fp-btn fp-btn-ghost" style="padding:14px 28px;font-size:15px;">Ver ligas</a>
          </div>
        </div>
      </div>
      <div class="scroll-hint">
        <span>Scroll para explorar</span>
        <div class="scroll-hint__arrow"></div>
      </div>
    </section>

    <section class="scroll-section scroll-section--right" id="section-1">
      <div class="scroll-section__inner">
        <div class="scroll-card">
          <div class="scroll-eyebrow">📊 Comunidad</div>
          <h2 class="scroll-title" style="font-size:clamp(30px,5vw,44px);">La comunidad <span class="fp-gradient-text">crece</span></h2>
          <p class="scroll-desc">Miles de jugadores ya confían en FastPlay para organizar sus partidos cada semana.</p>
          <div class="scroll-stats">
            <div class="scroll-stat"><div class="scroll-stat__num">2.4K</div><div class="scroll-stat__label">Jugadores</div></div>
            <div class="scroll-stat"><div class="scroll-stat__num">180</div><div class="scroll-stat__label">Equipos</div></div>
            <div class="scroll-stat"><div class="scroll-stat__num">42</div><div class="scroll-stat__label">Campos</div></div>
          </div>
        </div>
      </div>
    </section>

    <section class="scroll-section scroll-section--left" id="section-2">
      <div class="scroll-section__inner">
        <div class="scroll-card scroll-card--green">
          <div class="scroll-eyebrow">⚡ Funciones</div>
          <h2 class="scroll-title" style="font-size:clamp(30px,5vw,44px);">Todo lo que <span class="fp-gradient-text">necesitas</span></h2>
          <ul class="scroll-features">
            <li><span class="icon">⚽</span> Crea y gestiona tu equipo con plantilla completa</li>
            <li><span class="icon">🏟️</span> Reserva campos reales con un solo clic</li>
            <li><span class="icon">🎯</span> Matchmaking inteligente por nivel y posición</li>
            <li><span class="icon">📊</span> Estadísticas detalladas de cada partido</li>
          </ul>
        </div>
      </div>
    </section>

    <section class="scroll-section scroll-section--right" id="section-3">
      <div class="scroll-section__inner">
        <div class="scroll-card">
          <div class="scroll-eyebrow">🏆 Liga Pro</div>
          <h2 class="scroll-title" style="font-size:clamp(30px,5vw,44px);">Compite a <span class="fp-gradient-text">otro nivel</span></h2>
          <p class="scroll-desc">Árbitro oficial, estadísticas completas y premios reales. La liga profesional para el fútbol amateur.</p>
          <ul class="scroll-features">
            <li><span class="icon">🎖️</span> Árbitro oficial en cada partido</li>
            <li><span class="icon">💰</span> Premios económicos reales</li>
            <li><span class="icon">📈</span> Clasificación y tabla de posiciones en vivo</li>
          </ul>
          <div style="margin-top:8px;display:inline-block;padding:6px 16px;border-radius:9999px;font-size:13px;color:#fbbf24;background:rgba(245,158,11,.10);border:1px solid rgba(245,158,11,.20);font-weight:600;">Desde 20€ / temporada por equipo</div>
        </div>
      </div>
    </section>

    <section class="scroll-section scroll-section--center" id="section-4">
      <div class="scroll-section__inner">
        <div class="scroll-card" style="text-align:center;">
          <div class="scroll-eyebrow" style="margin-left:auto;margin-right:auto;">🚀 Únete</div>
          <h2 class="scroll-title" style="font-size:clamp(30px,5vw,48px);">¿Listo para <span class="fp-gradient-text">jugar?</span></h2>
          <p class="scroll-desc" style="max-width:420px;margin-left:auto;margin-right:auto;">Regístrate gratis y empieza a competir hoy mismo. Tu próximo partido te espera.</p>
          <div class="scroll-cta-row" style="justify-content:center;">
            <a href="<?= url('auth/register') ?>" class="fp-btn fp-btn-primary fp-btn-glow" style="padding:16px 36px;font-size:16px;">Crear cuenta gratis →</a>
          </div>
        </div>
      </div>
    </section>

  </div>
</div>

<script src="<?= asset('js/scroll-anim.js') ?>"></script>
<script>
(function() {
  'use strict';

  <?php
    $projectBase = rtrim(dirname(BASE_URL), '/\\');
    if ($projectBase === '' || $projectBase === '.') $projectBase = '';
    $projectBase = str_replace(' ', '%20', $projectBase);
  ?>
  var FRAME_PATH = '<?= $projectBase ?>/uploads/frames/frame_';

  var dots = document.querySelectorAll('.scroll-dot');
  var sectionEls = document.querySelectorAll('.scroll-section');

  function updateDots(frac) {
    var total = sectionEls.length;
    var activeIdx = Math.min(Math.floor(frac * total), total - 1);
    dots.forEach(function(d, i) { d.classList.toggle('active', i === activeIdx); });
  }

  dots.forEach(function(dot) {
    dot.addEventListener('click', function() {
      var idx = parseInt(dot.dataset.section);
      var target = sectionEls[idx];
      if (target) target.scrollIntoView({ behavior: 'smooth' });
    });
  });

  FastPlayScrollAnim.init({
    framePath: FRAME_PATH,
    onScroll: function(scrollTop, scrollFraction) {
      updateDots(scrollFraction);
    },
    onReady: function() {
      setTimeout(function() {
        var first = document.querySelector('#section-0 .scroll-section__inner');
        if (first) first.classList.add('visible');
      }, 300);
    }
  });
})();
</script>
