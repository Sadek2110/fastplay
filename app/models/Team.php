<?php
require_once CORE_PATH . '/Model.php';

class Team extends Model {
    protected string $table = 'teams';

    public function getAll(string $city = ''): array {
        if ($city) {
            $st = $this->db->prepare('
                SELECT t.*, COUNT(tp.user_id) AS player_count
                FROM teams t
                LEFT JOIN team_players tp ON tp.team_id = t.id
                WHERE t.city LIKE ?
                GROUP BY t.id ORDER BY t.name
            ');
            $st->execute(['%' . $city . '%']);
        } else {
            $st = $this->db->query('
                SELECT t.*, COUNT(tp.user_id) AS player_count
                FROM teams t
                LEFT JOIN team_players tp ON tp.team_id = t.id
                GROUP BY t.id ORDER BY t.name
            ');
        }
        return $st->fetchAll();
    }

    public function getPlayers(int $teamId): array {
        $st = $this->db->prepare('
            SELECT u.id, u.name, u.position, u.photo, tp.role
            FROM team_players tp
            JOIN users u ON u.id = tp.user_id
            WHERE tp.team_id = ?
            ORDER BY FIELD(tp.role,"captain","cocaptain","player"), u.name
        ');
        $st->execute([$teamId]);
        return $st->fetchAll();
    }

    public function getTeamByPlayer(int $userId): array|false {
        $st = $this->db->prepare('
            SELECT t.* FROM teams t
            JOIN team_players tp ON tp.team_id = t.id
            WHERE tp.user_id = ? LIMIT 1
        ');
        $st->execute([$userId]);
        return $st->fetch();
    }
}
