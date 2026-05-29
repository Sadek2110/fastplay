<?php

declare(strict_types=1);

$root = dirname(__DIR__);

loadEnv($root . DIRECTORY_SEPARATOR . '.env');

$autoload = $root . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
if (is_file($autoload)) {
    require_once $autoload;
}

$failed = false;

section('Composer');
check('stripe/stripe-php', class_exists(\Stripe\Stripe::class));
check('league/oauth2-google', class_exists(\League\OAuth2\Client\Provider\Google::class));
check('phpmailer/phpmailer', class_exists(\PHPMailer\PHPMailer\PHPMailer::class));

section('Extensiones PHP');
$dbDriver = getenv('DB_DRIVER') ?: 'mysql';
foreach (['curl', 'openssl', 'json', 'mbstring', 'pdo'] as $extension) {
    check($extension, extension_loaded($extension));
}
check($dbDriver === 'pgsql' ? 'pdo_pgsql' : 'pdo_mysql', extension_loaded($dbDriver === 'pgsql' ? 'pdo_pgsql' : 'pdo_mysql'));

section('Variables de entorno');
envCheck('APP_ENV', false, static fn (string $value): bool => $value !== '');
envCheck('STRIPE_SECRET_KEY', true, static fn (string $value): bool => preg_match('/^sk_(test|live)_[A-Za-z0-9_]+$/', $value) === 1 && $value !== 'sk_test_123');
envCheck('GOOGLE_CLIENT_ID', false, static fn (string $value): bool => str_ends_with($value, '.apps.googleusercontent.com'));
envCheck('GOOGLE_CLIENT_SECRET', false, static fn (string $value): bool => str_starts_with($value, 'GOCSPX-'));
envCheck('SMTP_PASSWORD', true, static fn (string $value): bool => $value !== '');

section('Base de datos');
try {
    $driver = $dbDriver;
    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: ($driver === 'pgsql' ? '5432' : '3306');
    $name = getenv('DB_NAME') ?: 'fastplay';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASS') ?: '';

    $dsn = $driver === 'pgsql'
        ? sprintf('pgsql:host=%s;port=%s;dbname=%s', $host, $port, $name)
        : sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $name);

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->query('SELECT 1');
    check('Conexion PDO', true);
} catch (Throwable $e) {
    check('Conexion PDO', false, $e->getMessage());
}

section('Resultado');
if ($failed) {
    echo "Hay fallos de configuracion. Corrige las variables marcadas y vuelve a desplegar.\n";
    exit(1);
}

echo "Diagnostico correcto. Si sigue habiendo 500, revisa el log de Apache/PHP tras reproducir el fallo.\n";

function section(string $title): void
{
    echo "\n=== {$title} ===\n";
}

function check(string $label, bool $ok, string $detail = ''): void
{
    global $failed;

    if (!$ok) {
        $failed = true;
    }

    echo sprintf('%-28s %s', $label, $ok ? 'OK' : 'FALLO');
    if ($detail !== '') {
        echo ' - ' . $detail;
    }
    echo "\n";
}

/**
 * @param callable(string): bool $validator
 */
function envCheck(string $key, bool $secret, callable $validator): void
{
    $value = getenv($key);
    if ($value === false || trim((string) $value) === '') {
        check($key, false, 'NO EXISTE');
        return;
    }

    $value = trim((string) $value);
    check($key, $validator($value), $secret ? maskSecret($value) : $value);
}

function maskSecret(string $value): string
{
    if (strlen($value) <= 8) {
        return '***';
    }

    return substr($value, 0, 4) . str_repeat('*', max(4, strlen($value) - 8)) . substr($value, -4);
}

function loadEnv(string $file): void
{
    if (!is_file($file) || !is_readable($file)) {
        return;
    }

    foreach (file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = ltrim($line);
        if ($line === '' || $line[0] === '#' || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = array_map('trim', explode('=', $line, 2));
        if ($key === '' || getenv($key) !== false) {
            continue;
        }

        if (strlen($value) >= 2) {
            $first = $value[0];
            $last = substr($value, -1);
            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                $value = substr($value, 1, -1);
            }
        }

        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}
