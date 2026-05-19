<?php

require_once APP_PATH . '/services/TeamJoinService.php';

class TeamJoinRequestController extends Controller
{
    public function create(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $teamId = (int) ($_POST['team_id'] ?? 0);
        [$request, $errors] = (new TeamJoinService())->create($teamId, (int) current_user()['id']);
        if ($request) {
            flash('ok', 'Solicitud enviada al capitán.');
        } else {
            flash('warn', implode(' ', $errors ?: ['No se pudo enviar la solicitud.']));
        }
        redirect('teams');
    }

    public function accept(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        [$request, $error] = (new TeamJoinService())->accept((int) $id, (int) current_user()['id']);
        flash($request ? 'ok' : 'warn', $request ? 'Solicitud aceptada.' : ($error ?: 'No se pudo aceptar.'));
        $this->back('notification');
    }

    public function reject(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        [$request, $error] = (new TeamJoinService())->reject((int) $id, (int) current_user()['id']);
        flash($request ? 'ok' : 'warn', $request ? 'Solicitud rechazada.' : ($error ?: 'No se pudo rechazar.'));
        $this->back('notification');
    }
}
