<?php

class MatchesController extends Controller
{
    public function index(): void
    {
        $matches = $this->model('Partido')->all();
        $this->view('matches/index', [
            'active' => 'matches',
            'matches' => $matches,
            'calendarMatches' => $matches,
            'scripts' => '<script src="' . asset('js/matches-calendar.js') . '" defer></script>',
            'title' => 'Partidos en Ceuta - FastPlay',
        ]);
    }

    public function show(string $id = ''): void
    {
        $id = (int) $id;
        $match = $this->model('Partido')->find($id);
        if (!$match) { Router::notFound(); return; }

        $isManager = false;
        if (is_auth()) {
            $equipo = $this->model('Equipo');
            $userId = (int) current_user()['id'];
            $isManager = is_admin()
                || $equipo->isCaptain((int) $match['home_team_id'], $userId)
                || $equipo->isCaptain((int) $match['away_team_id'], $userId);
        }

        $this->view('matches/show', [
            'active' => 'matches',
            'match' => $match,
            'isManager' => $isManager,
            'title' => $match['home_name'] . ' vs ' . $match['away_name'],
        ]);
    }

    public function create(): void
    {
        $this->requireAuth();
        $equipo = $this->model('Equipo');
        $userId = (int) current_user()['id'];
        $myTeam = $equipo->mine($userId);
        $captainTeams = Database::all('SELECT * FROM teams WHERE captain_id=? ORDER BY name', [$userId]);
        $teams = array_values(array_filter($equipo->all(), static function (array $team) use ($myTeam) {
            return !$myTeam || (int) $team['id'] !== (int) $myTeam['id'];
        }));

        $this->view('matches/create', [
            'active' => 'matches',
            'myTeam' => $myTeam,
            'captainTeams' => $captainTeams,
            'teams' => $teams,
            'title' => 'Solicitar partido - FastPlay',
        ]);
    }

    public function confirm(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        $id = (int) $id;
        $partido = $this->model('Partido');
        if (!$this->canManageMatch($partido, $id)) { return; }
        flash($partido->setStatus($id, 'confirmed') ? 'ok' : 'warn', 'Partido actualizado.');
        redirect('matches/show/' . $id);
    }

    public function cancel(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        $id = (int) $id;
        $partido = $this->model('Partido');
        if (!$this->canManageMatch($partido, $id)) { return; }
        flash($partido->setStatus($id, 'cancelled') ? 'ok' : 'warn', 'Partido actualizado.');
        redirect('matches/show/' . $id);
    }

    public function delete(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        $id = (int) $id;
        if (!$this->model('Partido')->deleteIfAllowed($id, (int) current_user()['id'], is_admin(), $this->model('Equipo'))) {
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
        $hs = min(99, max(0, (int) ($_POST['home_score'] ?? 0)));
        $as = min(99, max(0, (int) ($_POST['away_score'] ?? 0)));
        if ($hs === 0 && $as === 0) {
            flash('warn', 'Indica un marcador real para finalizar el partido.');
            redirect('matches/show/' . $id);
            return;
        }
        flash($partido->setStatus($id, 'finished', $hs, $as) ? 'ok' : 'warn', 'Partido actualizado.');
        redirect('matches/show/' . $id);
    }

    private function canManageMatch(Partido $partido, int $matchId): bool
    {
        $match = $partido->find($matchId);
        if (!$match) { Router::notFound(); return false; }
        if (is_admin()) { return true; }
        $equipo = $this->model('Equipo');
        $userId = (int) current_user()['id'];
        if ($equipo->isCaptain((int) $match['home_team_id'], $userId) || $equipo->isCaptain((int) $match['away_team_id'], $userId)) {
            return true;
        }
        flash('warn', 'No tienes permisos sobre este partido.');
        redirect('matches/show/' . $matchId);
        return false;
    }
}
