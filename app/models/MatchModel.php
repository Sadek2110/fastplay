<?php
require_once CORE_PATH . '/Model.php';

class MatchModel extends Model {
    protected string $table = 'matches';

    public function getAll(): array {
        $st = $this->db->query('
            SELECT m.*,
                   ht.name AS home_team_name, at.name AS away_team_name,
                   f.name  AS field_name
            FROM matches m
            JOIN teams ht ON ht.id = m.home_team_id
            JOIN teams at ON at.id = m.away_team_id
            LEFT JOIN fields f ON f.id = m.field_id
            ORDER BY m.match_date DESC
            LIMIT 100
        ');
        return $st->fetchAll();
    }

    public function getUpcoming(int $limit = 6): array {
        $st = $this->db->prepare('
            SELECT m.*,
                   ht.name AS home_team_name, at.name AS away_team_name,
                   f.name  AS field_name
            FROM matches m
            JOIN teams ht ON ht.id = m.home_team_id
            JOIN teams at ON at.id = m.away_team_id
            LEFT JOIN fields f ON f.id = m.field_id
            WHERE m.match_date >= NOW() AND m.status = "confirmed"
            ORDER BY m.match_date ASC
            LIMIT ?
        ');
        $st->execute([$limit]);
        return $st->fetchAll();
    }

    public function getDetail(int $id): array|false {
        $st = $this->db->prepare('
            SELECT m.*,
                   ht.name AS home_team_name, at.name AS away_team_name,
                   f.name  AS field_name
            FROM matches m
            JOIN teams ht ON ht.id = m.home_team_id
            JOIN teams at ON at.id = m.away_team_id
            LEFT JOIN fields f ON f.id = m.field_id
            WHERE m.id = ?
        ');
        $st->execute([$id]);
        return $st->fetch();
    }

    public function getLineups(int $matchId): array {
        $st = $this->db->prepare('
            SELECT ml.*, u.name, u.position,
                   CASE WHEN ml.team_id = m.home_team_id THEN "home" ELSE "away" END AS side
            FROM match_lineups ml
            JOIN users u  ON u.id  = ml.user_id
            JOIN matches m ON m.id = ml.match_id
            WHERE ml.match_id = ?
            ORDER BY ml.is_starter DESC, ml.jersey_number ASC
        ');
        $st->execute([$matchId]);
        $rows = $st->fetchAll();
        $lineups = ['home' => [], 'away' => []];
        foreach ($rows as $r) $lineups[$r['side']][] = $r;
        return $lineups;
    }
}
