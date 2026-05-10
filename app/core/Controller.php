<?php
// FastPlay · controlador base con vistas, modelos, auth y CSRF

class Controller
{
    protected function view(string $view, array $data = [], string $layout = 'main'): void
    {
        $viewFile = APP_PATH . '/views/' . $view . '.php';
        $layoutFile = APP_PATH . '/views/layouts/' . $layout . '.php';

        if (!file_exists($viewFile)) {
            throw new RuntimeException("Vista no encontrada: {$view}");
        }
        if (!file_exists($layoutFile)) {
            throw new RuntimeException("Layout no encontrado: {$layout}");
        }

        $data['_user']   = current_user();
        $data['_flash']  = flash_pull();
        $data['active']  = $data['active']  ?? '';
        $data['title']   = $data['title']   ?? 'FastPlay';

        extract($data, EXTR_SKIP);

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
        old_clear();
    }

    protected function model(string $modelName)
    {
        $modelFile = APP_PATH . '/models/' . $modelName . '.php';
        if (!file_exists($modelFile)) {
            throw new RuntimeException("Modelo no encontrado: {$modelName}");
        }
        require_once $modelFile;
        return new $modelName();
    }

    protected function partial(string $name, array $data = []): void
    {
        $partialFile = APP_PATH . '/views/partials/' . $name . '.php';
        if (file_exists($partialFile)) {
            extract($data, EXTR_SKIP);
            require $partialFile;
        }
    }

    protected function requireAuth(): void
    {
        if (!is_auth()) {
            flash('warn', 'Inicia sesión para continuar.');
            redirect('auth/login');
        }
    }

    protected function requireGuest(): void
    {
        if (is_auth()) {
            redirect('dashboard');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireAuth();
        if (!is_admin()) {
            http_response_code(403);
            $this->view('errors/403', ['title' => 'Acceso denegado — FastPlay']);
            exit;
        }
    }

    protected function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            header('Allow: POST');
            exit('Método no permitido');
        }
        require_csrf();
    }

    protected function back(string $fallback = ''): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? '';
        if ($ref !== '' && parse_url($ref, PHP_URL_HOST) === ($_SERVER['HTTP_HOST'] ?? null)) {
            header('Location: ' . $ref);
            exit;
        }
        redirect($fallback);
    }
}