<?php
// FastPlay · front controller

require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/Router.php';
require_once APP_PATH . '/core/Controller.php';

// Manejo unificado de excepciones y errores fatales
set_exception_handler(function (Throwable $e) {
    Router::serverError($e);
});

register_shutdown_function(function () {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        Router::serverError(new ErrorException(
            $err['message'], 0, $err['type'], $err['file'], $err['line']
        ));
    }
});

// Inicializa BD (idempotente — sólo migra/seedea si está vacía)
try {
    Database::pdo();
} catch (Throwable $e) {
    Router::serverError($e);
    exit;
}

$url = isset($_GET['url']) ? (string) $_GET['url'] : '';
if ($url === '' && PHP_SAPI === 'cli-server') {
    $path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '', '/');
    if ($path !== '' && !str_contains(basename($path), '.')) {
        $url = $path;
    }
}
Router::dispatch($url);
