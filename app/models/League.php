<?php
require_once CORE_PATH . '/Model.php';

class League extends Model {
    protected string $table = 'leagues';

    public function getActiveLeagues(): array {
        $st = $this->db->query("
            SELECT l.*, s.name AS season_name
            FROM leagues l
            LEFT JOIN seasons s ON s.id = l.season_id
            WHERE l.status = 'active'
            ORDER BY l.type DESC, l.name ASC
        ");
        return $st->fetchAll();
    }

    public function getStandings(int $leagueId): array {
        $st = $this->db->prepare('
            SELECT ls.*, t.name AS team_name
            FROM league_standings ls
            JOIN teams t ON t.id = ls.team_id
            WHERE ls.league_id = ?
            ORDER BY ls.points DESC, (ls.goals_for - ls.goals_against) DESC, ls.goals_for DESC
        ');
        $st->execute([$leagueId]);
        return $st->fetchAll();
    }
}
