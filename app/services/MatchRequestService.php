<?php

require_once APP_PATH . '/models/MatchRequest.php';
require_once APP_PATH . '/services/NotificationService.php';
require_once APP_PATH . '/services/MailService.php';

class MatchRequestService
{
    public function create(int $requestingTeamId, int $requestedTeamId, int $captainId): array
    {
        $requested = Database::one('SELECT t.*, u.email AS captain_email FROM teams t JOIN users u ON u.id=t.captain_id WHERE t.id=?', [$requestedTeamId]);
        if (!$requested) {
            return [null, ['team' => 'Equipo rival no encontrado.']];
        }
        [$request, $errors] = (new MatchRequest())->create($requestingTeamId, $requestedTeamId, $captainId, (int) $requested['captain_id']);
        if ($request) {
            NotificationService::create((int) $requested['captain_id'], 'match_request', $request['requesting_team_name'] . ' quiere jugar contra tu equipo.', 'match-request/show/' . (int) $request['id']);
            MailService::send((string) $requested['captain_email'], 'Nueva solicitud de partido', 'solicitud_partido', ['request' => $request, 'url' => url('match-request/show/' . (int) $request['id'])]);
        }
        return [$request, $errors];
    }

    public function accept(int $id, int $captainId): array
    {
        [$request, $error] = (new MatchRequest())->accept($id, $captainId);
        if ($request) {
            NotificationService::create((int) $request['requesting_captain_id'], 'match_request_accepted', 'Tu solicitud contra ' . $request['requested_team_name'] . ' fue aceptada.', 'match-request/show/' . (int) $request['id']);
            NotificationService::create((int) $request['requested_captain_id'], 'match_request_accepted', 'Has aceptado la solicitud de ' . $request['requesting_team_name'] . '.', 'match-request/show/' . (int) $request['id']);
        }
        return [$request, $error];
    }

    public function reject(int $id, int $captainId): array
    {
        [$request, $error] = (new MatchRequest())->reject($id, $captainId);
        if ($request) {
            NotificationService::create((int) $request['requesting_captain_id'], 'match_request', 'Tu solicitud contra ' . $request['requested_team_name'] . ' fue rechazada.', 'matches');
        }
        return [$request, $error];
    }

    public function confirm(int $id, int $captainId, string $date, string $time, string $location): array
    {
        [$request, $error] = (new MatchRequest())->confirm($id, $captainId, $date, $time, $location);
        if ($request && $request['status'] === 'accepted_final' && !empty($request['match_id'])) {
            NotificationService::create((int) $request['requesting_captain_id'], 'match_created', 'Partido creado contra ' . $request['requested_team_name'] . '.', 'matches/show/' . (int) $request['match_id']);
            NotificationService::create((int) $request['requested_captain_id'], 'match_created', 'Partido creado contra ' . $request['requesting_team_name'] . '.', 'matches/show/' . (int) $request['match_id']);
        }
        return [$request, $error];
    }
}
