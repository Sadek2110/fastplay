<?php
require_once CORE_PATH . '/Controller.php';
require_once APP_PATH  . '/models/League.php';

class LeagueController extends Controller {

    public function index(): void {
        $leagues = (new League())->getActiveLeagues();
        $this->render('league/list', compact('leagues'));
    }

    public function detail(string $id): void {
        $leagueModel = new League();
        $league      = $leagueModel->findById((int)$id);
        if (!$league) { $this->redirect('/leagues'); }
        $standings   = $leagueModel->getStandings((int)$id);
        $this->render('league/detail', compact('league', 'standings'));
    }
}
