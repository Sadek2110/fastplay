<main class="fp-fade fp-page" style="max-width:760px;">
    <?php $this->partial('back-button', ['href' => url('teams')]); ?>
    <section class="fp-glass fp-panel fp-upgrade-card">
        <i class="bi bi-stars"></i>
        <h1 class="fp-h1">Crear equipo requiere Premium</h1>
        <p class="fp-muted">Activa tu suscripción para crear un equipo, convertirte en capitán y gestionar solicitudes.</p>
        <a href="<?= url('subscription') ?>" class="fp-btn fp-btn-primary"><i class="bi bi-credit-card"></i><span>Ver Premium</span></a>
    </section>
</main>
