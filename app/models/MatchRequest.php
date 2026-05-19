<?php

class MatchRequest
{
    public function find(int $id): ?array
    {
        return Database::one(
            "SELECT r.*,
                    rt.name AS requesting_team_name, dt.name AS requested_team_name,
                    ru.email AS requesting_captain_email, du.email AS requested_captain_email
             FROM match_requests r
             JOIN teams rt ON rt.id = r.requesting_team_id
             JOIN teams dt ON dt.id = r.requested_team_id
             JOIN users ru ON ru.id = r.requesting_captain_id
             JOIN users du ON du.id = r.requested_captain_id
             WHERE r.id=?",
            [$id]
        );
    }

    public function forCaptain(int $userId): array
    {
        return Database::all(
            "SELECT r.*, rt.name AS requesting_team_name, dt.name AS requested_team_name
             FROM match_requests r
             JOIN teams rt ON rt.id = r.requesting_team_id
             JOIN teams dt ON dt.id = r.requested_team_id
             WHERE r.requesting_captain_id=? OR r.requested_captain_id=?
             ORDER BY r.created_at DESC",
            [$userId, $userId]
        );
    }

    public function create(int $requestingTeamId, int $requestedTeamId, int $requestingCaptainId, int $requestedCaptainId): array
    {
        if ($requestingTeamId === $requestedTeamId) {
            return [null, ['team' => 'No puedes solicitar un partido contra tu propio equipo.']];
        }
        $duplicate = Database::value(
            "SELECT 1 FROM match_requests
             WHERE status IN ('pending','accepted')
             AND ((requesting_team_id=? AND requested_team_id=?) OR (requesting_team_id=? AND requested_team_id=?))",
            [$requestingTeamId, $requestedTeamId, $requestedTeamId, $requestingTeamId]
        );
        if ($duplicate) {
            return [null, ['team' => 'Ya existe una solicitud pendiente entre estos equipos.']];
        }
        Database::run(
            "INSERT INTO match_requests (requesting_team_id,requested_team_id,requesting_captain_id,requested_captain_id,status)
             VALUES (?,?,?,?, 'pending')",
            [$requestingTeamId, $requestedTeamId, $requestingCaptainId, $requestedCaptainId]
        );
        return [$this->find(Database::insertId()), []];
    }

    public function accept(int $id, int $captainId): array
    {
        $request = $this->find($id);
        if (!$request || (int) $request['requested_captain_id'] !== $captainId || $request['status'] !== 'pending') {
            return [null, 'Solicitud no disponible.'];
        }
        Database::run("UPDATE match_requests SET status='accepted', updated_at=datetime('now') WHERE id=?", [$id]);
        $roomId = Database::value("SELECT id FROM chat_rooms WHERE type='match_negotiation' AND match_request_id=?", [$id]);
        if (!$roomId) {
            Database::run(
                "INSERT INTO chat_rooms (type,match_request_id,name) VALUES ('match_negotiation',?,?)",
                [$id, 'Negociacion: ' . $request['requesting_team_name'] . ' vs ' . $request['requested_team_name']]
            );
        }
        return [$this->find($id), null];
    }

    public function reject(int $id, int $captainId): array
    {
        $request = $this->find($id);
        if (!$request || (int) $request['requested_captain_id'] !== $captainId || $request['status'] !== 'pending') {
            return [null, 'Solicitud no disponible.'];
        }
        Database::run("UPDATE match_requests SET status='rejected', updated_at=datetime('now') WHERE id=?", [$id]);
        return [$this->find($id), null];
    }

    public function confirm(int $id, int $captainId, string $date, string $time, string $location): array
    {
        $request = $this->find($id);
        if (!$request || $request['status'] !== 'accepted') {
            return [null, 'Solicitud no disponible.'];
        }
        if ((int) $request['requesting_captain_id'] !== $captainId && (int) $request['requested_captain_id'] !== $captainId) {
            return [null, 'No tienes permisos sobre esta solicitud.'];
        }
        $date = trim($date);
        $time = trim($time);
        $location = trim($location);
        if ($date === '' || $time === '' || $location === '') {
            return [null, 'Fecha, hora y lugar son obligatorios.'];
        }
        $field = (int) $request['requesting_captain_id'] === $captainId ? 'requesting_confirmed' : 'requested_confirmed';
        Database::run(
            "UPDATE match_requests SET proposed_date=?, proposed_time=?, location=?, {$field}=1, updated_at=datetime('now') WHERE id=?",
            [$date, $time, $location, $id]
        );
        $request = $this->find($id);
        if ((int) $request['requesting_confirmed'] === 1 && (int) $request['requested_confirmed'] === 1 && empty($request['match_id'])) {
            $scheduledAt = $date . ' ' . $time . ':00';
            Database::run(
                "INSERT INTO matches (home_team_id,away_team_id,scheduled_at,status,local_captain_id,visitor_captain_id,match_time,location,created_by)
                 VALUES (?,?,?,'pending',?,?,?,?,?)",
                [(int) $request['requesting_team_id'], (int) $request['requested_team_id'], $scheduledAt, (int) $request['requesting_captain_id'], (int) $request['requested_captain_id'], $time, $location, (int) $request['requesting_captain_id']]
            );
            $matchId = Database::insertId();
            Database::run("UPDATE match_requests SET status='accepted_final', match_id=?, updated_at=datetime('now') WHERE id=?", [$matchId, $id]);
            $request = $this->find($id);
        }
        return [$request, null];
    }
}
