<?php

use PHPUnit\Framework\TestCase;

class ProductionConfigurationTest extends TestCase
{
    /** @var array<string, string|false> */
    private array $envBackup = [];

    /** @var array<string, mixed> */
    private array $serverBackup = [];

    /** @var list<string> */
    private array $mailFilesBefore = [];

    protected function setUp(): void
    {
        $this->backupEnv(['STRIPE_SECRET_KEY', 'GOOGLE_CLIENT_ID', 'GOOGLE_CLIENT_SECRET', 'SMTP_PASSWORD']);
        $this->serverBackup = [
            'HTTP_HOST' => $_SERVER['HTTP_HOST'] ?? null,
            'HTTP_X_FORWARDED_PROTO' => $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null,
            'HTTPS' => $_SERVER['HTTPS'] ?? null,
            'SCRIPT_NAME' => $_SERVER['SCRIPT_NAME'] ?? null,
        ];
        $this->mailFilesBefore = glob(STORAGE_PATH . DIRECTORY_SEPARATOR . 'mail' . DIRECTORY_SEPARATOR . '*') ?: [];
    }

    protected function tearDown(): void
    {
        foreach ($this->envBackup as $key => $value) {
            if ($value === false) {
                putenv($key);
                unset($_ENV[$key], $_SERVER[$key]);
            } else {
                putenv($key . '=' . $value);
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }

        foreach ($this->serverBackup as $key => $value) {
            if ($value === null) {
                unset($_SERVER[$key]);
            } else {
                $_SERVER[$key] = $value;
            }
        }

        $mailDir = STORAGE_PATH . DIRECTORY_SEPARATOR . 'mail';
        foreach (glob($mailDir . DIRECTORY_SEPARATOR . '*') ?: [] as $file) {
            if (!in_array($file, $this->mailFilesBefore, true) && is_file($file)) {
                @unlink($file);
            }
        }
    }

    public function test_composer_dependencies_required_by_production_flows_are_installed(): void
    {
        $this->assertTrue(class_exists(\Stripe\Stripe::class), 'Falta stripe/stripe-php.');
        $this->assertTrue(class_exists(\League\OAuth2\Client\Provider\Google::class), 'Falta league/oauth2-google.');
        $this->assertTrue(class_exists(\PHPMailer\PHPMailer\PHPMailer::class), 'Falta phpmailer/phpmailer.');
    }

    public function test_stripe_checkout_fails_fast_when_secret_key_is_missing(): void
    {
        $this->setEnv('STRIPE_SECRET_KEY', null);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('STRIPE_SECRET_KEY valida');

        new StripeService();
    }

    public function test_stripe_checkout_rejects_placeholder_secret_key(): void
    {
        $this->setEnv('STRIPE_SECRET_KEY', 'sk_test_123');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('STRIPE_SECRET_KEY valida');

        new StripeService();
    }

    public function test_stripe_checkout_accepts_well_formed_secret_key_without_api_call(): void
    {
        $this->setEnv('STRIPE_SECRET_KEY', 'sk_test_51FastplayDiagnosticKey_1234567890');

        new StripeService();

        $this->assertSame('sk_test_51FastplayDiagnosticKey_1234567890', \Stripe\Stripe::getApiKey());
    }

    public function test_google_provider_is_not_created_when_oauth_credentials_are_missing(): void
    {
        $this->setEnv('GOOGLE_CLIENT_ID', null);
        $this->setEnv('GOOGLE_CLIENT_SECRET', null);

        $provider = $this->invokeGoogleProvider();

        $this->assertNull($provider);
    }

    public function test_google_provider_uses_exact_https_callback_behind_proxy(): void
    {
        $this->setEnv('GOOGLE_CLIENT_ID', '1234567890-test.apps.googleusercontent.com');
        $this->setEnv('GOOGLE_CLIENT_SECRET', 'GOCSPX-test-secret');
        $_SERVER['HTTP_HOST'] = 'fastplay.dksaa.com';
        $_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';
        $_SERVER['SCRIPT_NAME'] = '/index.php';
        unset($_SERVER['HTTPS']);

        $provider = $this->invokeGoogleProvider();

        $this->assertInstanceOf(\League\OAuth2\Client\Provider\Google::class, $provider);
        $this->assertSame(
            'https://fastplay.dksaa.com/auth/google/callback',
            $this->readPrivateProperty($provider, 'redirectUri')
        );
    }

    public function test_mail_service_does_not_require_smtp_password_outside_production(): void
    {
        $this->setEnv('SMTP_PASSWORD', null);

        $sent = MailService::send(
            'diagnostic@test.com',
            'Diagnostico FastPlay',
            'diagnostico_inexistente',
            ['message' => 'Prueba local de correo.']
        );

        $this->assertTrue($sent);
    }

    /**
     * @param list<string> $keys
     */
    private function backupEnv(array $keys): void
    {
        foreach ($keys as $key) {
            $this->envBackup[$key] = getenv($key);
        }
    }

    private function setEnv(string $key, ?string $value): void
    {
        if ($value === null) {
            putenv($key);
            unset($_ENV[$key], $_SERVER[$key]);
            return;
        }

        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }

    private function invokeGoogleProvider(): ?\League\OAuth2\Client\Provider\Google
    {
        $controller = new AuthController();
        $method = new ReflectionMethod(AuthController::class, 'googleProvider');
        $method->setAccessible(true);

        return $method->invoke($controller);
    }

    private function readPrivateProperty(object $object, string $property): mixed
    {
        $reflection = new ReflectionProperty($object, $property);
        $reflection->setAccessible(true);

        return $reflection->getValue($object);
    }
}
