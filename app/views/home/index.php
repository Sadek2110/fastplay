<main class="fp-landing">
    <section class="fp-landing-hero">
        <div class="fp-landing-media" aria-hidden="true">
            <video muted playsinline autoplay loop poster="<?= asset('images/hero-poster.jpg') ?>">
                <source src="<?= asset('video/hero.webm') ?>" type="video/webm">
            </video>
        </div>
        <div class="fp-landing-overlay"></div>
        <div class="fp-landing-content">
            <p class="fp-eyebrow">Futbol local en Ceuta</p>
            <h1>Organiza partidos, crea tu equipo y compite en Ceuta</h1>
            <p>Una plataforma deportiva para jugadores, capitanes y equipos locales. Encuentra campos, reta a otros equipos y gestiona tus partidos desde un solo lugar.</p>
            <div class="fp-actions-row">
                <a href="<?= url('auth/register') ?>" class="fp-btn fp-btn-gold fp-btn-glow"><i class="bi bi-person-plus"></i><span>Crear cuenta</span></a>
                <a href="<?= url('campos') ?>" class="fp-btn fp-btn-ghost"><i class="bi bi-geo-alt"></i><span>Ver campos de Ceuta</span></a>
            </div>
        </div>
    </section>

    <section class="fp-section fp-landing-intro">
        <div>
            <p class="fp-eyebrow">FastPlay Ceuta</p>
            <h2 class="fp-h1">Todo el futbol amateur local, ordenado</h2>
            <p class="fp-muted">Crea equipos, solicita unirte a una plantilla, busca rivales y consulta partidos programados en los campos deportivos de Ceuta.</p>
        </div>
        <div class="fp-grid-3">
            <?php foreach ([
                ['bi-shield-check', 'Equipos locales', 'Gestiona plantilla, capitan y solicitudes de union.'],
                ['bi-calendar2-week', 'Partidos claros', 'Consulta fechas, estados, campo y rival sin perder informacion.'],
                ['bi-geo-alt', 'Campos de Ceuta', 'Ubicaciones y tarjetas de instalaciones preparadas para mapa.'],
            ] as $benefit): ?>
                <article class="fp-glass fp-panel fp-benefit">
                    <i class="bi <?= e($benefit[0]) ?>"></i>
                    <h3><?= e($benefit[1]) ?></h3>
                    <p><?= e($benefit[2]) ?></p>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="fp-section">
        <div class="fp-section-title-row">
            <div>
                <p class="fp-eyebrow">Campos destacados</p>
                <h2 class="fp-h1">Instalaciones deportivas de Ceuta</h2>
            </div>
            <a href="<?= url('campos') ?>" class="fp-btn fp-btn-primary"><i class="bi bi-map"></i><span>Ver campos</span></a>
        </div>
        <div class="fp-grid-3">
            <?php foreach ($fields as $field): ?>
                <article class="fp-glass fp-field-card">
                    <div class="fp-field-img" style="background-image:url('<?= e(!empty($field['image']) ? asset($field['image']) : asset('images/hero-pitch.png')) ?>')"></div>
                    <div>
                        <h3><?= e($field['name']) ?></h3>
                        <p><i class="bi bi-geo-alt"></i> <?= e($field['address'] ?? 'Ceuta') ?></p>
                        <small><?= e($field['description'] ?? 'Campo deportivo para futbol local en Ceuta.') ?></small>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="fp-section fp-landing-cta">
        <div>
            <p class="fp-eyebrow">Comunidad ceuti</p>
            <h2>Empieza a competir con tu equipo en Ceuta</h2>
            <p>Crea tu cuenta, encuentra campos y coordina partidos con otros capitanes locales.</p>
        </div>
        <a href="<?= url('auth/register') ?>" class="fp-btn fp-btn-gold"><i class="bi bi-arrow-right-circle"></i><span>Crear cuenta gratis</span></a>
    </section>
</main>
