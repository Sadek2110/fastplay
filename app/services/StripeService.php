<?php

class StripeService
{
    public function createCheckoutSession(int $userId): array
    {
        return [
            'mode' => 'subscription',
            'provider' => 'stripe',
            'checkout_url' => getenv('STRIPE_CHECKOUT_URL') ?: url('subscription/success?demo=1&user=' . $userId),
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
