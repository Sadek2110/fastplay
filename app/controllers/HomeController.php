<?php
// FastPlay · landing + páginas de error

class HomeController extends Controller
{
    public function index(): void
    {
        $liga = $this->model('Liga');

        $this->view('home/index', [
            'active'  => 'home',
            'leagues' => array_slice($liga->all(), 0, 4),
            'stats'   => $liga->stats(),
            'title'   => 'FastPlay — Fútbol amateur organizado',
        ]);
    }

    public function notFound(): void
    {
        http_response_code(404);
        $this->view('errors/404', [
            'active' => '',
            'title'  => '404 — Página no encontrada',
        ]);
    }

    public function serverError(): void
    {
        http_response_code(500);
        $this->view('errors/500', [
            'active' => '',
            'title'  => '500 — Error interno',
        ]);
    }
}