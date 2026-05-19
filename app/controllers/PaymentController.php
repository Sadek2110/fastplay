<?php

require_once APP_PATH . '/models/Subscription.php';

class PaymentController extends Controller
{
    public function webhook(): void
    {
        header('Content-Type: application/json');
        $payload = json_decode((string) file_get_contents('php://input'), true) ?: [];
        $userId = (int) ($payload['user_id'] ?? 0);
        $status = (string) ($payload['status'] ?? 'pending');
        if ($userId > 0 && in_array($status, ['active','cancelled','expired','pending'], true)) {
            (new Subscription())->upsertLocal($userId, $status);
        }
        echo json_encode(['ok' => true]);
    }
}
