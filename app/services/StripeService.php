<?php

class StripeService
{
    public function __construct()
    {
        if (!class_exists(\Stripe\Stripe::class)) {
            throw new RuntimeException('Stripe SDK no instalado. Ejecuta: composer install');
        }

        $secretKey = trim((string) (getenv('STRIPE_SECRET_KEY') ?: ''));
        if ($secretKey === '' || $secretKey === 'sk_test_123') {
            throw new RuntimeException('Stripe no esta configurado: falta STRIPE_SECRET_KEY valida.');
        }

        if (!preg_match('/^sk_(test|live)_[A-Za-z0-9_]+$/', $secretKey)) {
            throw new RuntimeException('Stripe no esta configurado: STRIPE_SECRET_KEY no parece valida.');
        }

        \Stripe\Stripe::setApiKey($secretKey);
    }

    public function createCheckoutSession(int $userId): array
    {
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => ['name' => 'Suscripción FastPlay Premium'],
                    'unit_amount' => 500, // 5.00 €
                    'recurring' => ['interval' => 'month'],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => url('subscription/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('subscription/cancel'),
            'client_reference_id' => (string) $userId,
        ]);
        return [
            'mode' => 'subscription',
            'provider' => 'stripe',
            'checkout_url' => $session->url,
        ];
    }

    public function retrieveSubscription(string $id): array
    {
        return ['id' => $id, 'status' => 'active'];
    }

    public function cancelSubscription(string $id): array
    {
        return ['id' => $id, 'status' => 'cancelled'];
    }
}
