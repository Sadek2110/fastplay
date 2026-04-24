<?php
require_once CORE_PATH . '/Controller.php';
require_once APP_PATH  . '/models/MatchModel.php';

class MatchController extends Controller {

    public function index(): void {
        $matches = (new MatchModel())->getAll();
        $this->render('match/list', compact('matches'));
    }

    public function detail(string $id): void {
        $matchModel = new MatchModel();
        $match      = $matchModel->getDetail((int)$id);
        if (!$match) { $this->redirect('/matches'); }
        $lineups    = $matchModel->getLineups((int)$id);
        $this->render('match/detail', compact('match', 'lineups'));
    }
}
