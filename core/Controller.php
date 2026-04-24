<?php
declare(strict_types=1);

abstract class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $file = APP_PATH . '/views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($file)) {
            http_response_code(404);
            die("View not found: {$view}");
        }
        require $file;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . APP_URL . $path);
        exit;
    }

    protected function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function requireLogin(): void
    {
        if (empty($_SESSION['user_id'])) {
            $this->flash('error', 'Debes iniciar sesión para acceder.');
            $this->redirect('/login');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireLogin();
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            $this->redirect('/dashboard');
        }
    }

    protected function requireCaptain(): void
    {
        $this->requireLogin();
        if (!in_array($_SESSION['user_role'] ?? '', ['captain', 'admin'])) {
            $this->flash('error', 'Se necesita rol de capitán.');
            $this->redirect('/dashboard');
        }
    }

    protected function flash(string $type, string $msg): void
    {
        $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
    }

    protected function getFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    protected function sanitize(string $value): string
    {
        return htmlspecialchars(trim($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    protected function post(string $key, string $default = ''): string
    {
        return $this->sanitize($_POST[$key] ?? $default);
    }

    protected function get(string $key, string $default = ''): string
    {
        return $this->sanitize($_GET[$key] ?? $default);
    }
}
