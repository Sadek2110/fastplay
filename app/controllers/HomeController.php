<?php
require_once CORE_PATH   . '/Controller.php';
require_once APP_PATH    . '/models/MatchModel.php';
require_once APP_PATH    . '/models/League.php';

class HomeController extends Controller {
    public function index(): void {
        $upcomingMatches = (new MatchModel())->getUpcoming(6);
        $activeLeagues   = (new League())->getActiveLeagues();
        $this->render('home/index', compact('upcomingMatches', 'activeLeagues'));
    }
}
