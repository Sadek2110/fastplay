<?php

class TeamsController extends Controller
{
    public function index(): void
    {
        $equipo = $this->model('Equipo');
        $this->model('Usuario');
        $user = current_user();
        $userId = $user ? (int) $user['id'] : 0;
        $myTeam = $userId > 0 ? $equipo->summaryForUser($userId) : null;

        $this->view('teams/index', [
            'active' => 'teams',
            'teams' => $myTeam ? [] : $equipo->all(),
            'myTeam' => $myTeam,
            'members' => $myTeam ? $equipo->members((int) $myTeam['id']) : [],
            'teamMatches' => $myTeam ? $equipo->recentMatches((int) $myTeam['id']) : [],
            'isCaptain' => $myTeam && $userId > 0 ? (int) $myTeam['captain_id'] === $userId : false,
            'isPremium' => $userId > 0 ? Usuario::isPremium($userId) : false,
            'title' => 'Equipos - FastPlay',
        ]);
    }

    public function all(): void
    {
        $equipo = $this->model('Equipo');
        $this->view('teams/all', [
            'active' => 'teams',
            'teams' => $equipo->allFiltered(trim((string) ($_GET['q'] ?? '')), (string) ($_GET['sort'] ?? 'name')),
            'q' => trim((string) ($_GET['q'] ?? '')),
            'sort' => (string) ($_GET['sort'] ?? 'name'),
            'title' => 'Todos los equipos - FastPlay',
        ]);
    }

    public function show(string $id = ''): void
    {
        $id = (int) $id;
        $equipo = $this->model('Equipo');
        $team = $equipo->find($id);
        if (!$team) { Router::notFound(); return; }

        $user = current_user();
        $isMember = false;
        if ($user) {
            $isMember = (bool) Database::value('SELECT 1 FROM team_members WHERE team_id=? AND user_id=?', [$id, (int) $user['id']]);
        }

        $this->view('teams/show', [
            'active' => 'teams',
            'team' => $team,
            'members' => $equipo->members($id),
            'teamStats' => $equipo->stats($id),
            'isMember' => $isMember,
            'pendingRequests' => $user && (int) $team['captain_id'] === (int) $user['id'] ? $this->model('TeamJoinRequest')->pendingForTeam($id) : [],
            'title' => $team['name'] . ' - FastPlay',
            'scripts' => '<script src="' . asset('js/team-detail.js') . '" defer></script>',
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $errors = [];
        $userId = (int) current_user()['id'];
        $this->model('Usuario');

        if (!Usuario::isPremium($userId)) {
            $this->view('subscription/upgrade_required', [
                'active' => 'teams',
                'title' => 'Crear equipo requiere Premium - FastPlay',
            ]);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();
            [$team, $errors] = $this->model('Equipo')->create($userId, $_POST);
            if ($team) {
                flash('ok', 'Equipo creado: ' . $team['name']);
                redirect('teams/show/' . $team['id']);
            }
            flash_old($_POST);
        }

        $this->view('teams/create', [
            'active' => 'teams',
            'errors' => $errors,
            'title' => 'Crear equipo - FastPlay',
        ]);
    }

    public function join(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        flash('warn', 'La union directa esta deshabilitada. Envia una solicitud al capitan.');
        redirect('teams/show/' . (int) $id);
    }

    public function leave(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        $id = (int) $id;
        if ($this->model('Equipo')->leave($id, (int) current_user()['id'])) {
            flash('ok', 'Has salido del equipo.');
        } else {
            flash('warn', 'No puedes salir del equipo siendo capitan.');
        }
        redirect('teams/show/' . $id);
    }

    public function delete(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        $id = (int) $id;

        $equipo = $this->model('Equipo');
        if (!is_admin() && !$equipo->isCaptain($id, (int) current_user()['id'])) {
            flash('warn', 'Solo el capitan o un admin pueden eliminar el equipo.');
            redirect('teams');
            return;
        }
        $blocker = $equipo->deletionBlocker($id);
        if ($blocker) {
            flash('warn', $blocker);
            redirect('teams/show/' . $id);
            return;
        }
        $equipo->delete($id);
        flash('ok', 'Equipo eliminado.');
        redirect('teams');
    }
}
