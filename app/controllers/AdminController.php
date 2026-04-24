<?php
require_once CORE_PATH . '/Controller.php';
require_once APP_PATH  . '/models/User.php';
require_once APP_PATH  . '/models/Team.php';
require_once APP_PATH  . '/models/League.php';
require_once APP_PATH  . '/models/MatchModel.php';

class AdminController extends Controller {

    public function dashboard(): void {
        $this->requireAdmin();
        $userModel = new User();
        $stats = [
            'users'   => $userModel->count(),
            'players' => $userModel->count(['role' => 'player']),
            'captains'=> $userModel->count(['role' => 'captain']),
            'teams'   => (new Team())->count(),
            'leagues' => (new League())->count(),
            'matches' => (new MatchModel())->count(),
        ];
        $this->render('admin/dashboard', compact('stats'));
    }

    public function users(): void {
        $this->requireAdmin();
        $users = (new User())->findAll('created_at DESC', 50);
        $this->render('admin/users', compact('users'));
    }

    public function teams(): void {
        $this->requireAdmin();
        $teams = (new Team())->findAll('created_at DESC', 50);
        $this->render('admin/teams', compact('teams'));
    }

    public function leagues(): void {
        $this->requireAdmin();
        $leagues = (new League())->findAll('start_date DESC', 50);
        $this->render('admin/leagues', compact('leagues'));
    }

    public function fields(): void {
        $this->requireAdmin();
        require_once APP_PATH . '/models/Field.php';
        $fields = (new Field())->findAll('name ASC', 100);
        $this->render('admin/fields', compact('fields'));
    }
}
