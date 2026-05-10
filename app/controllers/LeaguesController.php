<?php
// FastPlay · ligas

class LeaguesController extends Controller
{
    public function index(): void
    {
        $liga = $this->model('Liga');
        $this->view('leagues/index', [
            'active'  => 'leagues',
            'leagues' => $liga->all(),
            'title'   => 'Ligas activas — FastPlay',
        ]);
    }

    public function show(string $id = ''): void
    {
        $id = (int) $id;
        $liga = $this->model('Liga');
        $league = $liga->find($id);
        if (!$league) { Router::notFound(); return; }

        $equipo = $this->model('Equipo');
        $myTeams = [];
        if (is_auth()) {
            $myTeams = $equipo->ofUser((int) current_user()['id']);
        }

        $this->view('leagues/show', [
            'active'    => 'leagues',
            'league'    => $league,
            'standings' => $liga->standings($id),
            'myTeams'   => $myTeams,
            'title'     => $league['name'] . ' — FastPlay',
        ]);
    }

    public function register(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        $id = (int) $id;

        $teamId = (int) ($_POST['team_id'] ?? 0);
        $equipo = $this->model('Equipo');
        if (!$equipo->isCaptain($teamId, (int) current_user()['id']) && !is_admin()) {
            flash('warn', 'Sólo el capitán puede inscribir su equipo.');
            redirect('leagues/show/' . $id);
            return;
        }

        $liga = $this->model('Liga');
        $res = $liga->register($id, $teamId);
        if (!empty($res['ok'])) {
            flash('ok', '¡Equipo inscrito en la liga!');
        } else {
            flash('warn', $res['error'] ?? 'No se pudo inscribir el equipo.');
        }
        redirect('leagues/show/' . $id);
        return;
    }

    public function create(): void
    {
        $this->requireAdmin();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();
            $liga = $this->model('Liga');
            [$league, $errors] = $liga->create($_POST);
            if ($league) {
                flash('ok', 'Liga creada.');
                redirect('leagues/show/' . $league['id']);
                return;
            }
            flash_old($_POST);
        }

        $this->view('leagues/create', [
            'active' => 'leagues',
            'errors' => $errors,
            'title'  => 'Crear liga — FastPlay',
        ]);
    }
}