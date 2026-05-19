<?php

require_once APP_PATH . '/models/Notification.php';

class NotificationService
{
    public static function create(int $userId, string $type, string $message, ?string $url = null): int
    {
        return (new Notification())->create($userId, $type, $message, $url);
    }
}
