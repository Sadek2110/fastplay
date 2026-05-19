<?php

class Notification
{
    public function create(int $userId, string $type, string $message, ?string $url = null): int
    {
        Database::run(
            'INSERT INTO notifications (user_id,type,message,action_url) VALUES (?,?,?,?)',
            [$userId, $type, $message, $url]
        );
        return Database::insertId();
    }

    public function forUser(int $userId, string $filter = 'all', int $limit = 30, int $offset = 0): array
    {
        $where = 'user_id = ?';
        $params = [$userId];
        if ($filter === 'unread') {
            $where .= ' AND is_read = 0';
        }
        $limit = max(1, min(100, $limit));
        $offset = max(0, $offset);
        return Database::all(
            "SELECT * FROM notifications WHERE {$where} ORDER BY created_at DESC, id DESC LIMIT {$limit} OFFSET {$offset}",
            $params
        );
    }

    public function unreadCount(int $userId): int
    {
        return (int) Database::value('SELECT COUNT(*) FROM notifications WHERE user_id=? AND is_read=0', [$userId]);
    }

    public function markRead(int $id, int $userId): bool
    {
        Database::run('UPDATE notifications SET is_read=1 WHERE id=? AND user_id=?', [$id, $userId]);
        return true;
    }

    public function markAllRead(int $userId): void
    {
        Database::run('UPDATE notifications SET is_read=1 WHERE user_id=?', [$userId]);
    }
}
