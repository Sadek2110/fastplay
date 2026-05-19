<?php

class TeamJoinRequest
{
    public function pendingForTeam(int $teamId): array
    {
        return Database::all(
            "SELECT r.*, u.name AS user_name, u.email AS user_email, t.name AS team_name
             FROM team_join_requests r
             JOIN users u ON u.id = r.user_id
             JOIN teams t ON t.id = r.team_id
             WHERE r.team_id=? AND r.status='pending'
             ORDER BY r.created_at DESC",
            [$teamId]
        );
    }

    public function find(int $id): ?array
    {
        return Database::one(
            "SELECT r.*, u.name AS user_name, u.email AS user_email, t.name AS team_name, c.email AS captain_email
             FROM team_join_requests r
             JOIN users u ON u.id = r.user_id
             JOIN users c ON c.id = r.captain_id
             JOIN teams t ON t.id = r.team_id
             WHERE r.id=?",
            [$id]
        );
    }

    public function create(int $teamId, int $userId, int $captainId): array
    {
        if (Database::value('SELECT 1 FROM team_members WHERE user_id=?', [$userId])) {
            return [null, ['team' => 'Ya perteneces a un equipo.']];
        }
        if (Database::value("SELECT 1 FROM team_join_requests WHERE team_id=? AND user_id=? AND status='pending'", [$teamId, $userId])) {
            return [null, ['team' => 'Ya tienes una solicitud pendiente para este equipo.']];
        }
        Database::run(
            "INSERT INTO team_join_requests (team_id,user_id,captain_id,status) VALUES (?,?,?,'pending')",
            [$teamId, $userId, $captainId]
        );
        return [$this->find(Database::insertId()), []];
    }

    public function accept(int $id, int $captainId): array
    {
        $request = $this->find($id);
        if (!$request || (int) $request['captain_id'] !== $captainId || $request['status'] !== 'pending') {
            return [null, 'Solicitud no disponible.'];
        }
        if (Database::value('SELECT 1 FROM team_members WHERE user_id=?', [(int) $request['user_id']])) {
            Database::run("UPDATE team_join_requests SET status='rejected', updated_at=datetime('now') WHERE id=?", [$id]);
            return [null, 'El usuario ya pertenece a un equipo.'];
        }
        Database::run('INSERT INTO team_members (team_id,user_id,role) VALUES (?,?,?)', [(int) $request['team_id'], (int) $request['user_id'], 'player']);
        Database::run('UPDATE users SET current_team_id=? WHERE id=?', [(int) $request['team_id'], (int) $request['user_id']]);
        Database::run("UPDATE team_join_requests SET status='accepted', updated_at=datetime('now') WHERE id=?", [$id]);
        return [$this->find($id), null];
    }

    public function reject(int $id, int $captainId): array
    {
        $request = $this->find($id);
        if (!$request || (int) $request['captain_id'] !== $captainId || $request['status'] !== 'pending') {
            return [null, 'Solicitud no disponible.'];
        }
        Database::run("UPDATE team_join_requests SET status='rejected', updated_at=datetime('now') WHERE id=?", [$id]);
        return [$this->find($id), null];
    }
}
