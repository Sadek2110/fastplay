<?php
// FastPlay · partidos

class MatchesController extends Controller
{
    public function index(): void
    {
        $partido = $this->model('Partido');
        $this->view('matches/index', [
            'active'  => 'matches',
            'matches' => $partido->all(),
            'title'   => 'Partidos — FastPlay',
        ]);
    }

    public function show(string $id = ''): void
    {
        $id = (int) $id;
        $partido = $this->model('Partido');
        $match = $partido->find($id);
        if (!$match) { Router::notFound(); return; }

        $this->view('matches/show', [
            'active' => 'matches',
            'match'  => $match,
            'title'  => $match['home_name'] . ' vs ' . $match['away_name'],
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $errors = [];
        $equipo = $this->model('Equipo');
        $userId = (int) current_user()['id'];
        $teams  = $equipo->ofUser($userId);
        $allTeams = $equipo->all();
        $fields = $this->model('Campo')->all();
        $leagues = $this->model('Liga')->all();

        if (!$teams && !is_admin()) {
            flash('warn', 'Necesitas pertenecer a un equipo para crear un partido.');
            redirect('teams/create');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();
            $home = (int) ($_POST['home_team_id'] ?? 0);
            $away = (int) ($_POST['away_team_id'] ?? 0);

            if (!is_admin() && !$equipo->isCaptain($home, $userId) && !$equipo->isCaptain($away, $userId)) {
                flash('warn', 'Sólo puedes programar partidos como capitán de uno de los equipos.');
                flash_old($_POST);
                redirect('matches/create');
                return;
            }

            $partido = $this->model('Partido');
            [$match, $errors] = $partido->create($userId, $_POST);
            if ($match) {
                flash('ok', 'Partido creado, esperando confirmación del rival.');
                redirect('matches/show/' . $match['id']);
                return;
            }
            flash_old($_POST);
        }

        $this->view('matches/create', [
            'active'   => 'matches',
            'errors'   => $errors,
            'myTeams'  => $teams,
            'teams'    => $allTeams,
            'fields'   => $fields,
            'leagues'  => $leagues,
            'title'    => 'Crear partido — FastPlay',
        ]);
    }

    public function confirm(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        $id = (int) $id;
        $partido = $this->model('Partido');
        if (!$this->canManageMatch($partido, $id)) { return; }
        $partido->setStatus($id, 'confirmed');
        flash('ok', 'Partido confirmado.');
        redirect('matches/show/' . $id);
    }

    public function cancel(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        $id = (int) $id;
        $partido = $this->model('Partido');
        if (!$this->canManageMatch($partido, $id)) { return; }
        $partido->setStatus($id, 'cancelled');
        flash('ok', 'Partido cancelado.');
        redirect('matches/show/' . $id);
    }

    public function delete(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        $id = (int) $id;
        $partido = $this->model('Partido');
        $equipo  = $this->model('Equipo');
        $userId  = (int) current_user()['id'];
        if (!$partido->deleteIfAllowed($id, $userId, is_admin(), $equipo)) {
            flash('warn', 'No tienes permisos para borrar ese partido.');
            redirect('matches/show/' . $id);
            return;
        }
        flash('ok', 'Partido eliminado.');
        redirect('matches');
    }

    public function finish(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        $id = (int) $id;
        $partido = $this->model('Partido');
        if (!$this->canManageMatch($partido, $id)) { return; }
        $hs = isset($_POST['home_score']) ? (int) $_POST['home_score'] : 0;
        $as = isset($_POST['away_score']) ? (int) $_POST['away_score'] : 0;
        $partido->setStatus($id, 'finished', max(0, $hs), max(0, $as));
        flash('ok', 'Partido finalizado, marcador registrado.');
        redirect('matches/show/' . $id);
    }

    private function canManageMatch(Partido $partido, int $matchId): bool
    {
        $match = $partido->find($matchId);
        if (!$match) { Router::notFound(); return false; }
        if (is_admin()) { return true; }
        $equipo = $this->model('Equipo');
        $userId = (int) current_user()['id'];
        $home = (int) $match['home_team_id'];
        $away = (int) $match['away_team_id'];
        if ($equipo->isCaptain($home, $userId) || $equipo->isCaptain($away, $userId)) {
            return true;
        }
        flash('warn', 'No tienes permisos sobre este partido.');
        redirect('matches/show/' . $matchId);
        return false;
    }
}