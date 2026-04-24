<?php
require_once CORE_PATH . '/Model.php';

class Sanction extends Model {
    protected string $table = 'sanctions';

    public function getActiveByTeam(int $teamId): array {
        $st = $this->db->prepare("
            SELECT s.*, u.name AS issued_by_name
            FROM sanctions s
            LEFT JOIN users u ON u.id = s.issued_by
            WHERE s.team_id = ?
              AND (s.expires_at IS NULL OR s.expires_at > NOW())
            ORDER BY s.id DESC
        ");
        $st->execute([$teamId]);
        return $st->fetchAll();
    }
}
