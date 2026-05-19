<?php

class Subscription
{
    public function activeForUser(int $userId): ?array
    {
        return Database::one("SELECT * FROM subscriptions WHERE user_id=? AND status='active' ORDER BY id DESC LIMIT 1", [$userId]);
    }

    public function upsertLocal(int $userId, string $status, string $provider = 'stripe', ?string $customerId = null, ?string $subscriptionId = null): void
    {
        Database::run(
            "INSERT INTO subscriptions (user_id,provider,provider_customer_id,provider_subscription_id,status,starts_at,updated_at)
             VALUES (?,?,?,?,?,datetime('now'),datetime('now'))",
            [$userId, $provider, $customerId, $subscriptionId, $status]
        );
        Database::run('UPDATE users SET is_premium=? WHERE id=?', [$status === 'active' ? 1 : 0, $userId]);
    }
}
