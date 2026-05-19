<?php
// FastPlay · panel del jugador

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $user = current_user();

        $usuario = $this->model('Usuario');
        $partido = $this->model('Partido');
        $equipo  = $this->model('Equipo');
        $notification = $this->model('Notification');

        $this->view('dashboard/index', [
            'active'       => 'dashboard',
            'user'         => $user,
            'stats'        => $usuario->dashboardStats((int) $user['id']),
            'notifications'=> $notification->forUser((int) $user['id'], 'all', 5),
            'unreadCount'  => $notification->unreadCount((int) $user['id']),
            'upcoming'     => $partido->upcoming(),
            'team'         => $equipo->mine((int) $user['id']),
            'card'         => $usuario->playerCard((int) $user['id']),
            'title'        => 'Mi panel — FastPlay',
        ]);
    }
}
