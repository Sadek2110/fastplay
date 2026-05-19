<?php
// FastPlay - panel del jugador

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $user = current_user();
        $userId = (int) ($user['id'] ?? 0);

        $usuario = $this->model('Usuario');
        $partido = $this->model('Partido');
        $equipo  = $this->model('Equipo');
        $notification = $this->model('Notification');

        $freshUser = $userId > 0 ? $usuario->find($userId) : null;
        if (!$freshUser) {
            logout_user();
            flash('warn', 'Tu sesion ya no es valida. Inicia sesion de nuevo.');
            redirect('auth/login');
        }

        $_SESSION['user'] = array_merge($user ?? [], $freshUser);
        $user = $_SESSION['user'];

        $statsFallback = [
            ['i' => 'bi-calendar2-check', 'v' => 0, 'l' => 'Partidos jugados', 'c' => '#4ade80'],
            ['i' => 'bi-people', 'v' => 0, 'l' => 'Equipos', 'c' => '#60a5fa'],
            ['i' => 'bi-shield-check', 'v' => 0, 'l' => 'Como capitan', 'c' => '#fbbf24'],
            ['i' => 'bi-bell', 'v' => 0, 'l' => 'Notificaciones', 'c' => '#38bdf8'],
        ];

        $this->view('dashboard/index', [
            'active'        => 'dashboard',
            'user'          => $user,
            'stats'         => $this->safe(fn () => $usuario->dashboardStats($userId), $statsFallback),
            'notifications' => $this->safe(fn () => $notification->forUser($userId, 'all', 5), []),
            'unreadCount'   => $this->safe(fn () => $notification->unreadCount($userId), 0),
            'upcoming'      => $this->safe(fn () => $partido->upcoming(), []),
            'team'          => $this->safe(fn () => $equipo->mine($userId), null),
            'card'          => $this->safe(fn () => $usuario->playerCard($userId), []),
            'isPremium'     => $this->safe(fn () => Usuario::isPremium($userId), false),
            'title'         => 'Mi panel - FastPlay',
        ]);
    }

    private function safe(callable $callback, mixed $fallback): mixed
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            error_log('[FastPlay] dashboard fallback: ' . $e->getMessage());
            return $fallback;
        }
    }
}
