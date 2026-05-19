<?php

require_once APP_PATH . '/models/MatchRequest.php';
require_once APP_PATH . '/services/MatchRequestService.php';

class MatchRequestController extends Controller
{
    public function show(string $id = ''): void
    {
        $this->requireAuth();
        $request = (new MatchRequest())->find((int) $id);
        if (!$request) { Router::notFound(); return; }
        $userId = (int) current_user()['id'];
        if (!is_admin() && (int) $request['requesting_captain_id'] !== $userId && (int) $request['requested_captain_id'] !== $userId) {
            flash('warn', 'No tienes acceso a esta solicitud.');
            redirect('matches');
        }
        $room = Database::one("SELECT * FROM chat_rooms WHERE type='match_negotiation' AND match_request_id=?", [(int) $request['id']]);
        $this->view('matches/request_show', [
            'active' => 'matches',
            'request' => $request,
            'room' => $room,
            'title' => 'Solicitud de partido - FastPlay',
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $userId = (int) current_user()['id'];
        $requestingTeamId = (int) ($_POST['requesting_team_id'] ?? 0);
        $requestedTeamId = (int) ($_POST['requested_team_id'] ?? 0);
        if (!Database::value('SELECT 1 FROM teams WHERE id=? AND captain_id=?', [$requestingTeamId, $userId])) {
            flash('warn', 'Solo el capitán puede solicitar partidos.');
            redirect('matches/create');
        }
        [$request, $errors] = (new MatchRequestService())->create($requestingTeamId, $requestedTeamId, $userId);
        if ($request) {
            flash('ok', 'Solicitud de partido enviada.');
            redirect('match-request/show/' . (int) $request['id']);
        }
        flash('warn', implode(' ', $errors ?: ['No se pudo crear la solicitud.']));
        redirect('matches/create');
    }

    public function accept(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        [$request, $error] = (new MatchRequestService())->accept((int) $id, (int) current_user()['id']);
        flash($request ? 'ok' : 'warn', $request ? 'Solicitud aceptada. Ya puedes negociar fecha y lugar.' : ($error ?: 'No se pudo aceptar.'));
        redirect('match-request/show/' . (int) $id);
    }

    public function reject(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        [$request, $error] = (new MatchRequestService())->reject((int) $id, (int) current_user()['id']);
        flash($request ? 'ok' : 'warn', $request ? 'Solicitud rechazada.' : ($error ?: 'No se pudo rechazar.'));
        redirect('matches');
    }

    public function confirm(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        [$request, $error] = (new MatchRequestService())->confirm(
            (int) $id,
            (int) current_user()['id'],
            (string) ($_POST['match_date'] ?? ''),
            (string) ($_POST['match_time'] ?? ''),
            (string) ($_POST['location'] ?? '')
        );
        if ($request && $request['status'] === 'accepted_final') {
            flash('ok', 'Partido oficial creado.');
            redirect('matches/show/' . (int) $request['match_id']);
        }
        flash($request ? 'ok' : 'warn', $request ? 'Confirmación registrada.' : ($error ?: 'No se pudo confirmar.'));
        redirect('match-request/show/' . (int) $id);
    }
}
