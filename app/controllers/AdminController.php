<?php
// FastPlay · panel admin

class AdminController extends Controller
{
    public function index(): void
    {
        $this->requireAdmin();
        $counts = [
            'users'    => (int) Database::value('SELECT COUNT(*) FROM users'),
            'teams'    => (int) Database::value('SELECT COUNT(*) FROM teams'),
            'leagues'  => (int) Database::value('SELECT COUNT(*) FROM leagues'),
            'matches'  => (int) Database::value('SELECT COUNT(*) FROM matches'),
            'fields'   => (int) Database::value('SELECT COUNT(*) FROM fields'),
            'rooms'    => (int) Database::value('SELECT COUNT(*) FROM chat_rooms'),
        ];
        $this->view('admin/index', [
            'active' => 'admin',
            'counts' => $counts,
            'recent' => Database::all("SELECT email, success, attempted_at FROM login_attempts ORDER BY id DESC LIMIT 12"),
            'title'  => 'Admin — FastPlay',
        ]);
    }

    public function users(): void
    {
        $this->requireAdmin();
        $usuario = $this->model('Usuario');
        $this->view('admin/users', [
            'active' => 'admin',
            'users'  => $usuario->all(),
            'title'  => 'Admin · Usuarios — FastPlay',
        ]);
    }

    public function setRole(string $id = ''): void
    {
        $this->requireAdmin();
        $this->requirePost();
        $usuario = $this->model('Usuario');
        $usuario->setRole((int) $id, (string) ($_POST['role'] ?? 'player'));
        flash('ok', 'Rol actualizado.');
        redirect('admin/users');
    }

    public function deleteUser(string $id = ''): void
    {
        $this->requireAdmin();
        $this->requirePost();
        if ((int) $id === (int) current_user()['id']) {
            flash('warn', 'No puedes eliminar tu propia cuenta desde aquí.');
            redirect('admin/users');
        }
        $this->model('Usuario')->delete((int) $id);
        flash('ok', 'Usuario eliminado.');
        redirect('admin/users');
    }

    public function teams(): void
    {
        $this->requireAdmin();
        $this->view('admin/teams', [
            'active' => 'admin',
            'teams'  => $this->model('Equipo')->all(),
            'title'  => 'Admin · Equipos — FastPlay',
        ]);
    }

    public function leagues(): void
    {
        $this->requireAdmin();
        $this->view('admin/leagues', [
            'active'  => 'admin',
            'leagues' => $this->model('Liga')->all(),
            'title'   => 'Admin · Ligas — FastPlay',
        ]);
    }

    public function fields(): void
    {
        $this->requireAdmin();
        $this->view('admin/fields', [
            'active' => 'admin',
            'fields' => $this->model('Campo')->all(),
            'title'  => 'Admin · Campos — FastPlay',
        ]);
    }

    public function deleteLeague(string $id = ''): void
    {
        $this->requireAdmin();
        $this->requirePost();
        $this->model('Liga')->delete((int) $id);
        flash('ok', 'Liga eliminada.');
        redirect('admin/leagues');
    }

    public function deleteField(string $id = ''): void
    {
        $this->requireAdmin();
        $this->requirePost();
        $this->model('Campo')->delete((int) $id);
        flash('ok', 'Campo eliminado.');
        redirect('admin/fields');
    }
}