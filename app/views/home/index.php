<?php $pageTitle = 'Inicio'; ?>
<?php require_once APP_PATH . '/views/partials/header.php'; ?>
<?php require_once APP_PATH . '/views/partials/navbar.php'; ?>

<!-- ═══════════════════════════════════ HERO ═══════════════════════════════════ -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden pt-16">

    <!-- Background grid -->
    <div class="absolute inset-0 bg-grid-dark bg-grid opacity-100 pointer-events-none"></div>

    <!-- Radial green glow center -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[700px] h-[700px] pointer-events-none"
         style="background:radial-gradient(ellipse,rgba(22,163,74,.12) 0%,transparent 70%);"></div>

    <!-- Floating badge left -->
    <div class="absolute left-10 top-1/3 hidden xl:block glass rounded-2xl p-5 fade-up">
        <div class="text-2xl mb-2">🏆</div>
        <div class="text-white font-bold text-sm">Liga Pro</div>
        <div class="text-green-400 text-xs font-medium">Temporada activa</div>
    </div>

    <!-- Floating badge right -->
    <div class="absolute right-10 top-2/5 hidden xl:block glass rounded-2xl p-5 fade-up-2">
        <div class="text-2xl mb-2">📍</div>
        <div class="text-white font-bold text-sm">48 ciudades</div>
        <div class="text-gray-400 text-xs">campos disponibles</div>
    </div>
    <div class="absolute right-10 bottom-1/3 hidden xl:block glass rounded-2xl p-5 fade-up-3">
        <div class="text-2xl mb-2">⚡</div>
        <div class="text-white font-bold text-sm">12 K+ jugadores</div>
        <div class="text-gray-400 text-xs">ya en la plataforma</div>
    </div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">

        <!-- Live badge -->
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-green-500/30 bg-green-500/10 text-green-400 text-sm font-semibold mb-10 fade-up">
            <span class="w-2 h-2 bg-green-400 rounded-full pulse-dot"></span>
            Liga Pro Temporada 2026 — ¡Inscripciones abiertas!
        </div>

        <!-- Headline -->
        <h1 class="text-5xl sm:text-6xl md:text-7xl lg:text-8xl font-black leading-none tracking-tight mb-7 fade-up-1">
            ¿Te apetece<br>
            <span class="gradient-text">jugar un partido?</span>
        </h1>

        <!-- Subtitle -->
        <p class="text-lg sm:text-xl text-gray-400 max-w-2xl mx-auto mb-12 leading-relaxed fade-up-2">
            FastPlay conecta jugadores, organiza partidos en campos reales y lleva el fútbol amateur al siguiente nivel.
            <strong class="text-gray-200">En cualquier lugar. Para todos.</strong>
        </p>

        <!-- CTAs -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-20 fade-up-3">
            <?php if (!empty($_SESSION['user_id'])): ?>
                <a href="<?= APP_URL ?>/dashboard" class="btn-primary text-lg px-10 py-4 glow-green">
                    Mi dashboard <span class="group-hover:translate-x-1 transition-transform">→</span>
                </a>
                <a href="<?= APP_URL ?>/matches" class="btn-ghost text-lg px-10 py-4">
                    Ver partidos
                </a>
            <?php else: ?>
                <a href="<?= APP_URL ?>/register" class="btn-primary text-lg px-10 py-4 glow-green">
                    Empieza gratis →
                </a>
                <a href="<?= APP_URL ?>/leagues" class="btn-ghost text-lg px-10 py-4">
                    Ver ligas activas
                </a>
            <?php endif; ?>
        </div>

        <!-- Stats row -->
        <div class="inline-flex items-center gap-8 md:gap-14 glass rounded-2xl px-10 py-5 fade-up-4">
            <div class="text-center">
                <div class="text-3xl font-black text-white">12K+</div>
                <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mt-0.5">Jugadores</div>
            </div>
            <div class="w-px h-10 bg-white/10"></div>
            <div class="text-center">
                <div class="text-3xl font-black text-white">3.4K</div>
                <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mt-0.5">Partidos</div>
            </div>
            <div class="w-px h-10 bg-white/10"></div>
            <div class="text-center">
                <div class="text-3xl font-black text-white">48</div>
                <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mt-0.5">Ciudades</div>
            </div>
            <div class="w-px h-10 bg-white/10 hidden sm:block"></div>
            <div class="text-center hidden sm:block">
                <div class="text-3xl font-black text-green-400">100%</div>
                <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mt-0.5">Gratis*</div>
            </div>
        </div>
        <p class="text-gray-600 text-xs mt-3 fade-up-5">*Registro y Liga Amistosa siempre gratuitos</p>
    </div>

    <!-- Bottom gradient fade -->
    <div class="absolute bottom-0 left-0 right-0 h-32 pointer-events-none"
         style="background:linear-gradient(to top,#060d09,transparent);"></div>
</section>

<!-- ═══════════════════════════════ FEATURES BENTO ════════════════════════════ -->
<section class="py-28 px-6 max-w-7xl mx-auto">
    <div class="text-center mb-16">
        <p class="text-green-400 font-semibold text-sm uppercase tracking-widest mb-3">Por qué FastPlay</p>
        <h2 class="text-4xl md:text-5xl font-black tracking-tight">Todo lo que necesitas<br>para <span class="gradient-text">jugar</span></h2>
    </div>

    <!-- Bento grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 auto-rows-fr">

        <!-- Feature grande — Equipos -->
        <div class="md:col-span-2 glass-green rounded-3xl p-8 flex flex-col justify-between min-h-[260px] group hover:border-green-500/40 transition-all duration-300">
            <div>
                <div class="text-5xl mb-5">⚽</div>
                <h3 class="text-2xl font-black mb-3">Crea y gestiona tu equipo</h3>
                <p class="text-gray-400 leading-relaxed">
                    Arma tu plantilla, define la alineación y reta a otros equipos. Los capitanes pactan partidos directamente por chat interno.
                </p>
            </div>
            <div class="mt-6 flex items-center gap-3">
                <span class="text-xs px-3 py-1 rounded-full bg-green-500/15 text-green-400 font-medium border border-green-500/20">Desde 4,99 €</span>
                <span class="text-xs px-3 py-1 rounded-full bg-white/5 text-gray-400 font-medium">Tasa única</span>
            </div>
        </div>

        <!-- Feature — Campos -->
        <div class="glass rounded-3xl p-8 flex flex-col justify-between min-h-[260px] hover:bg-white/[.07] transition-all duration-300">
            <div>
                <div class="text-5xl mb-5">🏟️</div>
                <h3 class="text-xl font-black mb-3">Campos reales certificados</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Reserva con un clic. Calendario integrado, bloqueo automático y notificaciones 2h antes.
                </p>
            </div>
            <div class="mt-6">
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                    Google Maps integrado
                </div>
            </div>
        </div>

        <!-- Feature — Liga Pro -->
        <div class="glass rounded-3xl p-8 flex flex-col justify-between min-h-[200px] hover:bg-white/[.07] transition-all duration-300 relative overflow-hidden">
            <div class="absolute top-4 right-4 px-2.5 py-0.5 rounded-full text-xs font-black"
                 style="background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#000;">PRO</div>
            <div>
                <div class="text-4xl mb-4">🏆</div>
                <h3 class="text-xl font-black mb-2">Liga Pro</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Árbitro oficial, estadísticas completas y <strong class="text-yellow-400">premios reales</strong> al campeón.</p>
            </div>
        </div>

        <!-- Feature — Estadísticas -->
        <div class="glass rounded-3xl p-8 flex flex-col justify-between min-h-[200px] hover:bg-white/[.07] transition-all duration-300">
            <div>
                <div class="text-4xl mb-4">📊</div>
                <h3 class="text-xl font-black mb-2">Tu historial completo</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Goles, asistencias, tarjetas y rendimiento. Tu perfil siempre actualizado.</p>
            </div>
        </div>

        <!-- Feature grande — Matchmaking -->
        <div class="glass rounded-3xl p-8 flex flex-col justify-between min-h-[200px] hover:bg-white/[.07] transition-all duration-300">
            <div>
                <div class="text-4xl mb-4">🎯</div>
                <h3 class="text-xl font-black mb-2">Matchmaking inteligente</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Emparejamiento automático por nivel, posición y localidad. Siempre encuentras rival.</p>
            </div>
        </div>

        <!-- Feature — Chat -->
        <div class="glass rounded-3xl p-8 flex flex-col justify-between min-h-[200px] hover:bg-white/[.07] transition-all duration-300">
            <div>
                <div class="text-4xl mb-4">💬</div>
                <h3 class="text-xl font-black mb-2">Chat interno</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Coordina con tu equipo, negocia partidos con otros capitanes. Historial completo.</p>
            </div>
        </div>

        <!-- Feature — Gamificación -->
        <div class="md:col-span-2 glass rounded-3xl p-8 flex flex-col md:flex-row items-center gap-8 hover:bg-white/[.07] transition-all duration-300">
            <div class="flex-1">
                <div class="text-4xl mb-4">🎖️</div>
                <h3 class="text-2xl font-black mb-3">Gamificación y reputación</h3>
                <p class="text-gray-400 leading-relaxed">
                    Rankings individuales, medallas por rendimiento, rachas semanales y reputación de fair play. Cada partido cuenta.
                </p>
            </div>
            <div class="flex flex-col gap-3 min-w-[160px]">
                <div class="flex items-center gap-3 glass rounded-xl px-4 py-2.5">
                    <span>🥇</span><span class="text-sm font-semibold">Máximo goleador</span>
                </div>
                <div class="flex items-center gap-3 glass rounded-xl px-4 py-2.5">
                    <span>🤝</span><span class="text-sm font-semibold">Fair Play</span>
                </div>
                <div class="flex items-center gap-3 glass rounded-xl px-4 py-2.5">
                    <span>🔥</span><span class="text-sm font-semibold">Racha activa</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════ CÓMO FUNCIONA ════════════════════════════════ -->
<section class="py-24 px-6" style="background:linear-gradient(to bottom,#060d09,#0a1610,#060d09);">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-16">
            <p class="text-green-400 font-semibold text-sm uppercase tracking-widest mb-3">Proceso</p>
            <h2 class="text-4xl md:text-5xl font-black tracking-tight">Juega en <span class="gradient-text">3 pasos</span></h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8 relative">
            <!-- Connector line desktop -->
            <div class="hidden md:block absolute top-10 left-1/4 right-1/4 h-px bg-gradient-to-r from-green-500/40 via-green-500/20 to-green-500/40"></div>

            <?php
            $steps = [
                ['num'=>'01','icon'=>'👤','title'=>'Crea tu perfil','desc'=>'Regístrate gratis, añade tu posición, localidad y foto. Tu identidad deportiva en minutos.'],
                ['num'=>'02','icon'=>'👥','title'=>'Únete o crea un equipo','desc'=>'Busca equipos en tu zona o crea el tuyo como capitán. Mínimo 8 jugadores para competir.'],
                ['num'=>'03','icon'=>'⚽','title'=>'¡A jugar!','desc'=>'Pacta partidos, reserva el campo y compite. Tus estadísticas se actualizan automáticamente.'],
            ];
            foreach($steps as $s): ?>
            <div class="text-center relative">
                <div class="w-20 h-20 mx-auto mb-6 glass-green rounded-2xl flex items-center justify-center text-4xl relative">
                    <?= $s['icon'] ?>
                    <span class="absolute -top-2 -right-2 w-7 h-7 bg-green-600 rounded-full text-xs font-black flex items-center justify-center text-white"><?= $s['num'] ?></span>
                </div>
                <h3 class="text-xl font-black mb-3"><?= $s['title'] ?></h3>
                <p class="text-gray-400 text-sm leading-relaxed"><?= $s['desc'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ═══════════════════════ PRÓXIMOS PARTIDOS ═════════════════════════════════ -->
<?php if (!empty($upcomingMatches)): ?>
<section class="py-24 px-6 max-w-7xl mx-auto">
    <div class="flex items-end justify-between mb-10 flex-wrap gap-4">
        <div>
            <p class="text-green-400 font-semibold text-sm uppercase tracking-widest mb-2">En vivo</p>
            <h2 class="text-3xl font-black">Próximos partidos</h2>
        </div>
        <a href="<?= APP_URL ?>/matches" class="btn-ghost px-6 py-2.5 text-sm">Ver todos →</a>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($upcomingMatches as $m): ?>
        <a href="<?= APP_URL ?>/matches/<?= $m['id'] ?>" class="glass rounded-2xl p-6 hover:bg-white/[.07] hover:border-green-500/20 transition-all duration-200 group">
            <div class="flex items-center justify-between mb-5">
                <span class="text-xs px-2.5 py-1 rounded-full bg-green-500/10 text-green-400 font-medium border border-green-500/20">Confirmado</span>
                <span class="text-xs text-gray-500"><?= date('d/m H:i', strtotime($m['match_date'])) ?></span>
            </div>
            <div class="flex items-center gap-3">
                <span class="flex-1 text-center font-bold text-sm truncate"><?= htmlspecialchars($m['home_team_name']) ?></span>
                <span class="px-3 py-1.5 glass rounded-lg text-xs font-black text-gray-300">VS</span>
                <span class="flex-1 text-center font-bold text-sm truncate"><?= htmlspecialchars($m['away_team_name']) ?></span>
            </div>
            <div class="mt-5 flex items-center gap-2 text-xs text-gray-500">
                <span>🏟️</span>
                <span class="truncate"><?= htmlspecialchars($m['field_name'] ?? 'Campo por confirmar') ?></span>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════ LIGAS ACTIVAS ════════════════════════════════ -->
<?php if (!empty($activeLeagues)): ?>
<section class="py-20 px-6 max-w-7xl mx-auto">
    <div class="flex items-end justify-between mb-10 flex-wrap gap-4">
        <div>
            <p class="text-green-400 font-semibold text-sm uppercase tracking-widest mb-2">Compite</p>
            <h2 class="text-3xl font-black">Ligas activas</h2>
        </div>
        <a href="<?= APP_URL ?>/leagues" class="btn-ghost px-6 py-2.5 text-sm">Ver todas →</a>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($activeLeagues as $l): ?>
        <a href="<?= APP_URL ?>/leagues/<?= $l['id'] ?>" class="glass rounded-2xl p-6 hover:bg-white/[.07] transition-all duration-200
            <?= $l['type'] === 'pro' ? 'border-yellow-500/20 hover:border-yellow-500/40' : '' ?>">
            <div class="flex items-center gap-2 mb-4">
                <?php if ($l['type'] === 'pro'): ?>
                    <span class="text-xs px-3 py-1 rounded-full font-black" style="background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#000;">🏆 LIGA PRO</span>
                <?php else: ?>
                    <span class="text-xs px-3 py-1 rounded-full bg-white/10 text-gray-300 font-semibold border border-white/10">🤝 Amistosa</span>
                <?php endif; ?>
            </div>
            <h3 class="font-black text-base mb-1"><?= htmlspecialchars($l['name']) ?></h3>
            <p class="text-gray-500 text-xs mb-3">📍 <?= htmlspecialchars($l['city']) ?></p>
            <div class="text-xs text-gray-600">
                <?= date('d/m/Y', strtotime($l['start_date'])) ?> — <?= date('d/m/Y', strtotime($l['end_date'])) ?>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════ PRECIOS ══════════════════════════════════ -->
<section class="py-28 px-6" style="background:linear-gradient(to bottom,#060d09,#081209,#060d09);">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-16">
            <p class="text-green-400 font-semibold text-sm uppercase tracking-widest mb-3">Planes</p>
            <h2 class="text-4xl md:text-5xl font-black tracking-tight">Sin sorpresas.<br><span class="gradient-text">Sin letras pequeñas.</span></h2>
        </div>

        <div class="grid md:grid-cols-2 gap-6">

            <!-- Free -->
            <div class="glass rounded-3xl p-8 flex flex-col">
                <div class="mb-6">
                    <span class="text-3xl mb-4 block">🤝</span>
                    <h3 class="text-xl font-black mb-1">Liga Amistosa</h3>
                    <p class="text-gray-400 text-sm">Para los que quieren jugar sin compromisos</p>
                </div>
                <div class="mb-8">
                    <span class="text-5xl font-black">Gratis</span>
                </div>
                <ul class="space-y-3 mb-8 flex-1">
                    <?php foreach(['Perfil de jugador completo','Búsqueda de equipos y rivales','Partidos sin árbitro','Chat con capitanes','Acceso a campos disponibles'] as $f): ?>
                    <li class="flex items-center gap-3 text-sm text-gray-300">
                        <span class="w-5 h-5 bg-green-500/20 text-green-400 rounded-full flex items-center justify-center text-xs flex-shrink-0">✓</span>
                        <?= $f ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?= APP_URL ?>/register" class="btn-ghost text-center py-3.5 rounded-xl font-bold">Empezar gratis</a>
            </div>

            <!-- Pro -->
            <div class="rounded-3xl p-8 flex flex-col relative overflow-hidden glow-sm"
                 style="background:linear-gradient(135deg,rgba(22,163,74,0.12),rgba(22,163,74,0.04));border:1px solid rgba(22,163,74,0.3);">
                <div class="absolute top-5 right-5 px-3 py-1 rounded-full text-xs font-black"
                     style="background:linear-gradient(135deg,#fbbf24,#f59e0b);color:#000;">MÁS POPULAR</div>
                <div class="mb-6">
                    <span class="text-3xl mb-4 block">🏆</span>
                    <h3 class="text-xl font-black mb-1">Liga Pro</h3>
                    <p class="text-gray-400 text-sm">Para los que quieren competir de verdad</p>
                </div>
                <div class="mb-8">
                    <span class="text-5xl font-black text-green-400">20€</span>
                    <span class="text-gray-400 text-sm ml-2">/temporada por equipo</span>
                </div>
                <ul class="space-y-3 mb-8 flex-1">
                    <?php foreach(['Todo lo de Amistosa','Árbitro oficial en cada partido','Estadísticas: goles, asistencias, tarjetas','Tabla de clasificación en tiempo real','Premios: 🥇40% · 🥈20% · 🥉10% del fondo','Certificación de campos','Reset por temporada'] as $f): ?>
                    <li class="flex items-center gap-3 text-sm text-gray-200">
                        <span class="w-5 h-5 bg-green-500 rounded-full flex items-center justify-center text-xs flex-shrink-0 text-white">✓</span>
                        <?= $f ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?= APP_URL ?>/register" class="btn-primary text-center py-3.5 rounded-xl font-bold glow-green">
                    Inscribir mi equipo →
                </a>
            </div>
        </div>

        <p class="text-center text-gray-600 text-sm mt-8">
            Tasa de capitán (única): <strong class="text-gray-400">4,99 €</strong> · Pagos mediante Stripe / PayPal · Sin suscripción mensual
        </p>
    </div>
</section>

<!-- ═══════════════════════════════ CTA FINAL ════════════════════════════════ -->
<?php if (empty($_SESSION['user_id'])): ?>
<section class="py-28 px-6">
    <div class="max-w-3xl mx-auto text-center">
        <div class="glass-green rounded-3xl p-12 relative overflow-hidden">
            <div class="absolute inset-0 bg-grid-dark bg-grid opacity-40 pointer-events-none"></div>
            <div class="relative z-10">
                <div class="text-6xl mb-6">⚽</div>
                <h2 class="text-4xl md:text-5xl font-black mb-5">¿Listo para jugar?</h2>
                <p class="text-gray-400 text-lg mb-10 max-w-xl mx-auto">
                    Únete gratis. Crea tu perfil en minutos y empieza a conectar con jugadores de tu zona hoy mismo.
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="<?= APP_URL ?>/register" class="btn-primary text-lg px-10 py-4 glow-green">
                        Crear cuenta gratis →
                    </a>
                    <a href="<?= APP_URL ?>/login" class="btn-ghost text-lg px-10 py-4">
                        Ya tengo cuenta
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require_once APP_PATH . '/views/partials/footer.php'; ?>
