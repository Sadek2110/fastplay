<?php
declare(strict_types=1);

class Router
{
    private array $routes = [];

    public function get(string $path, string $controller, string $method): void
    {
        $this->routes[] = ['GET', $path, $controller, $method];
    }

    public function post(string $path, string $controller, string $method): void
    {
        $this->routes[] = ['POST', $path, $controller, $method];
    }

    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $base = parse_url(APP_URL, PHP_URL_PATH);
        $path = '/' . ltrim(substr($requestUri, strlen($base)), '/');
        $path = $path === '' ? '/' : $path;

        foreach ($this->routes as [$method, $route, $controller, $action]) {
            if ($method !== $requestMethod) continue;

            $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                $this->run($controller, $action, $matches);
                return;
            }
        }

        http_response_code(404);
        echo $this->notFound();
    }

    private function run(string $controller, string $action, array $params): void
    {
        $file = APP_PATH . '/controllers/' . $controller . '.php';
        if (!file_exists($file)) {
            http_response_code(500);
            die("Controller not found: {$controller}");
        }
        require_once $file;
        $instance = new $controller();
        $instance->$action(...$params);
    }

    private function notFound(): string
    {
        return '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">
        <title>404 — FastPlay</title>
        <script src="https://cdn.tailwindcss.com"></script></head>
        <body class="bg-[#060d09] text-white flex items-center justify-center min-h-screen">
        <div class="text-center">
          <div class="text-8xl font-black text-green-500 mb-4">404</div>
          <p class="text-gray-400 text-xl mb-8">Página no encontrada</p>
          <a href="' . APP_URL . '/" class="bg-green-600 hover:bg-green-500 text-white font-bold px-8 py-3 rounded-full transition-colors">
            Volver al inicio
          </a>
        </div></body></html>';
    }
}
