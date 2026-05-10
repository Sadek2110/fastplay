<?php
// FastPlay · front controller

require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Database.php';
require_once APP_PATH . '/core/Router.php';
require_once APP_PATH . '/core/Controller.php';

// Inicializa BD (idempotente — sólo migra/seedea si está vacía)
try {
    Database::pdo();
} catch (Throwable $e) {
    http_response_code(500);
    error_log('[FastPlay/DB] ' . $e->getMessage());
    echo 'No se pudo inicializar la base de datos.';
    exit;
}

$url = isset($_GET['url']) ? (string) $_GET['url'] : '';
Router::dispatch($url);