<?php
/**
 * FastPlay · Seed de administrador inicial
 *
 * Ejecutar una sola vez:
 *   php database/seed-admin.php
 *
 * Crea la cuenta de admin de Sadek si no existe ya un usuario con ese email.
 */

require_once __DIR__ . '/../config/config.php';
require_once APP_PATH . '/core/Database.php';

$email    = 'sadek@dksaa.com';
$name     = 'Sadek';
$password = 'deksa2110';

$exists = Database::value('SELECT 1 FROM users WHERE email = ?', [$email]);
if ($exists) {
    echo "Ya existe un usuario con email {$email}. No se ha creado nada.\n";
    exit(0);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

Database::run(
    "INSERT INTO users (name, email, password_hash, role, email_verified, city) VALUES (?, ?, ?, 'admin', 1, 'Ceuta')",
    [$name, $email, $hash]
);

echo "Admin creado correctamente:\n";
echo "  Email:    {$email}\n";
echo "  Password: {$password}\n";
echo "  Rol:      admin\n";
