<?php

// FastPlay · configuración de correo (SMTP)
// Todos los valores provienen de variables de entorno (.env o panel de hosting).
// No pongas credenciales aquí: este archivo se versiona en git.
//
// SMTP_PASSWORD se mantiene como alias retrocompatible de MAIL_PASS.

$pass = getenv('MAIL_PASS');
if ($pass === false || $pass === '') {
    $pass = getenv('SMTP_PASSWORD') ?: '';
}

return [
    'host'       => getenv('MAIL_HOST') ?: 'smtp.ionos.es',
    'port'       => (int) (getenv('MAIL_PORT') ?: 587),
    'user'       => getenv('MAIL_USER') ?: '',
    'pass'       => $pass,
    'from'       => getenv('MAIL_FROM') ?: '',
    'from_name'  => getenv('MAIL_FROM_NAME') ?: 'FastPlay',
    'encryption' => getenv('MAIL_ENCRYPTION') ?: 'tls', // 'tls' (STARTTLS) | 'ssl'
];
