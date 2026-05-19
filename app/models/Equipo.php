<?php
// FastPlay · modelo Equipo

class Equipo
{
    public function all(): array
    {
        return Database::all(
            "SELECT t.id, t.name, t.city, t.badge,
                    COALESCE((SELECT SUM(points) FROM league_teams WHERE team_id = t.id), 0) AS points,
                    (SELECT COUNT(*) FROM team_members WHERE team_id = t.id) AS players,
                    u.name AS captain_name
             FROM teams t
             JOIN users u ON u.id = t.captain_id
             ORDER BY t.name ASC"
        );
    }

    public function find(int $id): ?array
    {
        return Database::one(
            "SELECT t.*, u.name AS captain_name, u.email AS captain_email
             FROM teams t JOIN users u ON u.id = t.captain_id
             WHERE t.id = ?", [$id]
        );
    }

    public function members(int $teamId): array
    {
        return Database::all(
            "SELECT u.id, u.name, u.email, u.position, u.city, tm.joined_at,
                    CASE WHEN t.captain_id = u.id THEN 1 ELSE 0 END AS is_captain
             FROM team_members tm
             JOIN users u ON u.id = tm.user_id
             JOIN teams t ON t.id = tm.team_id
             WHERE tm.team_id = ?
             ORDER BY is_captain DESC, u.name ASC",
            [$teamId]
        );
    }

    /** Equipos donde milita el usuario. */
    public function ofUser(int $userId): array
    {
        return Database::all(
            "SELECT t.* FROM teams t
             JOIN team_members tm ON tm.team_id = t.id
             WHERE tm.user_id = ?
             ORDER BY t.name", [$userId]
        );
    }

    public function mine(int $userId): ?array
    {
        $teams = $this->ofUser($userId);
        return $teams[0] ?? null;
    }

    public function create(int $captainId, array $data): array
    {
        $errors = [];
        $name = trim((string) ($data['name'] ?? ''));
        $city = trim((string) ($data['city'] ?? ''));
        $badge = trim((string) ($data['badge'] ?? '🛡️'));
        if (!v_required($name) || mb_strlen($name) < 3) $errors['name'] = 'Nombre mínimo de 3 caracteres.';
        if (!v_required($city))                         $errors['city'] = 'Ciudad obligatoria.';
        if ($badge !== '' && mb_strlen($badge) > 4)      $errors['badge'] = 'El escudo debe ser breve.';
        if ($errors) return [null, $errors];

        if (Database::value('SELECT 1 FROM team_members WHERE user_id=?', [$captainId])) {
            return [null, ['name' => 'Ya perteneces a un equipo.']];
        }
        if (Database::value('SELECT 1 FROM teams WHERE captain_id=?', [$captainId])) {
            return [null, ['name' => 'Ya eres capitán de un equipo.']];
        }

        $exists = Database::value('SELECT 1 FROM teams WHERE name = ? AND city = ?', [$name, $city]);
        if ($exists) {
            return [null, ['name' => 'Ya existe un equipo con ese nombre en esa ciudad.']];
        }

        Database::run('INSERT INTO teams (name,city,badge,captain_id) VALUES (?,?,?,?)', [$name, $city, $badge, $captainId]);
        $id = Database::insertId();
        Database::run('INSERT INTO team_members (team_id,user_id,role) VALUES (?,?,?)', [$id, $captainId, 'captain']);
        Database::run('UPDATE users SET current_team_id=? WHERE id=?', [$id, $captainId]);
        return [$this->find($id), []];
    }

    public function join(int $teamId, int $userId): bool
    {
        $exists = Database::value('SELECT 1 FROM team_members WHERE team_id=? AND user_id=?', [$teamId, $userId]);
        if ($exists) return false;
        if (Database::value('SELECT 1 FROM team_members WHERE user_id=?', [$userId])) return false;
        Database::run('INSERT INTO team_members (team_id,user_id,role) VALUES (?,?,?)', [$teamId, $userId, 'player']);
        Database::run('UPDATE users SET current_team_id=? WHERE id=?', [$teamId, $userId]);
        return true;
    }

    public function leave(int $teamId, int $userId): bool
    {
        $team = $this->find($teamId);
        if ($team && (int) $team['captain_id'] === $userId) {
            return false;
        }
        Database::run('DELETE FROM team_members WHERE team_id=? AND user_id=?', [$teamId, $userId]);
        Database::run('UPDATE users SET current_team_id=NULL WHERE id=?', [$userId]);
        return true;
    }

    public function delete(int $id): void
    {
        Database::run('DELETE FROM teams WHERE id=?', [$id]);
    }

    public function allFiltered(string $search = '', string $sort = 'name'): array
    {
        $order = match ($sort) {
            'points' => 'points DESC, t.name ASC',
            'created' => 't.created_at DESC',
            default => 't.name ASC',
        };
        $params = [];
        $where = '';
        if ($search !== '') {
            $where = 'WHERE t.name LIKE ? OR t.city LIKE ? OR u.name LIKE ?';
            $like = '%' . $search . '%';
            $params = [$like, $like, $like];
        }
        return Database::all(
            "SELECT t.id, t.name, t.city, t.badge, t.created_at,
                    COALESCE((SELECT SUM(points) FROM league_teams WHERE team_id = t.id), 0) AS points,
                    (SELECT COUNT(*) FROM team_members WHERE team_id = t.id) AS players,
                    u.name AS captain_name
             FROM teams t
             JOIN users u ON u.id = t.captain_id
             {$where}
             ORDER BY {$order}",
            $params
        );
    }

    public function deletionBlocker(int $id): ?string
    {
        $matches = (int) Database::value(
            'SELECT COUNT(*) FROM matches WHERE home_team_id=? OR away_team_id=?',
            [$id, $id]
        );
        if ($matches > 0) {
            return 'No se puede eliminar un equipo con partidos asociados.';
        }
        $activeLeagues = (int) Database::value(
            "SELECT COUNT(*)
             FROM league_teams lt
             JOIN leagues l ON l.id = lt.league_id
             WHERE lt.team_id=? AND l.status IN ('open','in_progress')",
            [$id]
        );
        if ($activeLeagues > 0) {
            return 'No se puede eliminar un equipo inscrito en una liga activa.';
        }
        return null;
    }

    public function isCaptain(int $teamId, int $userId): bool
    {
        return (bool) Database::value('SELECT 1 FROM teams WHERE id=? AND captain_id=?', [$teamId, $userId]);
    }
}
