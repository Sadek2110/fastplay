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
        $teams  = $equipo->ofUser((int) current_user()['id']);
        $allTeams = $equipo->all();
        $fields = $this->model('Campo')->all();
        $leagues = $this->model('Liga')->all();

        if (!$teams && !is_admin()) {
            flash('warn', 'Necesitas pertenecer a un equipo para crear un partido.');
            redirect('teams/create');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();
            $partido = $this->model('Partido');
            [$match, $errors] = $partido->create((int) current_user()['id'], $_POST);
            if ($match) {
                flash('ok', 'Partido creado, esperando confirmación del rival.');
                redirect('matches/show/' . $match['id']);
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
        $partido->setStatus($id, 'cancelled');
        flash('ok', 'Partido cancelado.');
        redirect('matches/show/' . $id);
    }

    public function finish(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        $id = (int) $id;
        $hs = isset($_POST['home_score']) ? (int) $_POST['home_score'] : 0;
        $as = isset($_POST['away_score']) ? (int) $_POST['away_score'] : 0;
        $partido = $this->model('Partido');
        $partido->setStatus($id, 'finished', max(0, $hs), max(0, $as));
        flash('ok', 'Partido finalizado, marcador registrado.');
        redirect('matches/show/' . $id);
    }
}