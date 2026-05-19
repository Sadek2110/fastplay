<?php
// FastPlay - landing + paginas de error

class HomeController extends Controller
{
    public function index(): void
    {
        $liga = $this->model('Liga');
        $campo = $this->model('Campo');

        $this->view('home/index', [
            'active' => 'home',
            'fields' => array_slice($campo->ceuta(), 0, 3),
            'stats' => $liga->stats(),
            'title' => 'FastPlay Ceuta - Futbol local organizado',
        ]);
    }

    public function notFound(): void
    {
        http_response_code(404);
        $this->view('errors/404', [
            'active' => '',
            'title' => '404 - Pagina no encontrada',
        ]);
    }

    public function serverError(): void
    {
        http_response_code(500);
        $this->view('errors/500', [
            'active' => '',
            'title' => '500 - Error interno',
        ]);
    }
}
