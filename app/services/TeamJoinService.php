<?php

require_once APP_PATH . '/models/TeamJoinRequest.php';
require_once APP_PATH . '/services/NotificationService.php';
require_once APP_PATH . '/services/MailService.php';

class TeamJoinService
{
    public function create(int $teamId, int $userId): array
    {
        $team = Database::one('SELECT t.*, u.email AS captain_email FROM teams t JOIN users u ON u.id=t.captain_id WHERE t.id=?', [$teamId]);
        if (!$team) {
            return [null, ['team' => 'Equipo no encontrado.']];
        }
        [$request, $errors] = (new TeamJoinRequest())->create($teamId, $userId, (int) $team['captain_id']);
        if ($request) {
            NotificationService::create((int) $team['captain_id'], 'team_join_request', $request['user_name'] . ' quiere unirse a ' . $team['name'] . '.', 'notification');
            MailService::send((string) $team['captain_email'], 'Nueva solicitud de equipo', 'solicitud_equipo', ['request' => $request, 'url' => url('notification')]);
        }
        return [$request, $errors];
    }

    public function accept(int $id, int $captainId): array
    {
        [$request, $error] = (new TeamJoinRequest())->accept($id, $captainId);
        if ($request) {
            NotificationService::create((int) $request['user_id'], 'team_join_accepted', 'Tu solicitud para unirte a ' . $request['team_name'] . ' fue aceptada.', 'teams/show/' . (int) $request['team_id']);
            MailService::send((string) $request['user_email'], 'Solicitud de equipo aceptada', 'solicitud_equipo_aceptada', ['request' => $request, 'url' => url('teams/show/' . (int) $request['team_id'])]);
        }
        return [$request, $error];
    }

    public function reject(int $id, int $captainId): array
    {
        [$request, $error] = (new TeamJoinRequest())->reject($id, $captainId);
        if ($request) {
            NotificationService::create((int) $request['user_id'], 'team_join_rejected', 'Tu solicitud para unirte a ' . $request['team_name'] . ' fue rechazada.', 'teams');
            MailService::send((string) $request['user_email'], 'Solicitud de equipo rechazada', 'solicitud_equipo_rechazada', ['request' => $request, 'url' => url('teams')]);
        }
        return [$request, $error];
    }
}
