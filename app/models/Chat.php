<?php
require_once CORE_PATH . '/Model.php';

class Chat extends Model {
    protected string $table = 'chat_rooms';

    public function getRoomsByUser(int $userId): array {
        $st = $this->db->prepare('
            SELECT r.*, m.content AS last_message, m.created_at AS last_message_time
            FROM chat_rooms r
            JOIN chat_room_members mem ON mem.room_id = r.id
            LEFT JOIN chat_messages m ON m.id = (
                SELECT id FROM chat_messages WHERE room_id = r.id ORDER BY created_at DESC LIMIT 1
            )
            WHERE mem.user_id = ?
            ORDER BY m.created_at DESC
        ');
        $st->execute([$userId]);
        return $st->fetchAll();
    }

    public function getMessages(int $roomId, int $limit = 50): array {
        $st = $this->db->prepare('
            SELECT cm.*, u.name AS author_name, u.photo AS author_photo
            FROM chat_messages cm
            JOIN users u ON u.id = cm.user_id
            WHERE cm.room_id = ?
            ORDER BY cm.created_at DESC
            LIMIT ?
        ');
        $st->execute([$roomId, $limit]);
        return array_reverse($st->fetchAll());
    }
}
