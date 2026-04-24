<?php
require_once CORE_PATH . '/Model.php';

class User extends Model {
    protected string $table = 'users';

    public function findByCredential(string $credential): array|false {
        $st = $this->db->prepare(
            'SELECT * FROM users WHERE email = ? OR phone = ? LIMIT 1'
        );
        $st->execute([$credential, $credential]);
        return $st->fetch();
    }

    public function getStats(int $userId): array {
        $st = $this->db->prepare('
            SELECT
                COUNT(DISTINCT mp.match_id) AS matches,
                COALESCE(SUM(s.goals), 0)   AS goals,
                COALESCE(SUM(s.assists), 0)  AS assists,
                COALESCE(SUM(s.yellow_cards), 0) AS yellows,
                COALESCE(SUM(s.red_cards), 0)    AS reds
            FROM match_players mp
            LEFT JOIN stats s ON s.match_id = mp.match_id AND s.user_id = mp.user_id
            WHERE mp.user_id = ?
        ');
        $st->execute([$userId]);
        return $st->fetch() ?: ['matches'=>0,'goals'=>0,'assists'=>0,'yellows'=>0,'reds'=>0];
    }

    public function getAchievements(int $userId): array {
        $st = $this->db->prepare('
            SELECT a.*, ua.earned_at
            FROM user_achievements ua
            JOIN achievements a ON a.id = ua.achievement_id
            WHERE ua.user_id = ?
            ORDER BY ua.earned_at DESC
            LIMIT 10
        ');
        $st->execute([$userId]);
        return $st->fetchAll();
    }
}
