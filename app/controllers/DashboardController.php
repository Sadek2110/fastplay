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
            'scripts'       => '<script src="' . asset('js/dwec-context-panel.js') . '" defer></script>',
        ]);
    }

    /**
     * Endpoint JSON con el contexto del usuario.
     *
     * Devuelve rol efectivo, equipo, premium, notificaciones sin leer
     * y acciones permitidas. Lo consume public/js/dwec-context-panel.js
     * para transformar la interfaz desde el cliente.
     */
    public function context(): void
    {
        header('Content-Type: application/json; charset=UTF-8');
        header('Cache-Control: no-store');

        try {
            if (!is_auth()) {
                echo json_encode([
                    'role'                 => 'guest',
                    'displayName'          => 'Visitante',
                    'team'                 => null,
                    'isPremium'            => false,
                    'unreadNotifications'  => 0,
                    'allowedActions'       => ['login', 'register', 'browseTeams'],
                    'message'              => 'Inicia sesion para acceder a tu panel.',
                    'generatedAt'          => date('c'),
                ], JSON_UNESCAPED_UNICODE);
                return;
            }

            $user = current_user();
            $userId = (int) ($user['id'] ?? 0);

            $usuario = $this->model('Usuario');
            $equipo  = $this->model('Equipo');
            $notification = $this->model('Notification');

            $team = $this->safe(fn () => $equipo->mine($userId), null);
            $unread = (int) $this->safe(fn () => $notification->unreadCount($userId), 0);
            $isPremium = (bool) $this->safe(fn () => Usuario::isPremium($userId), false);

            $effectiveRole = 'player';
            if (is_admin()) {
                $effectiveRole = 'admin';
            } elseif (!empty($team) && (int) ($team['captain_id'] ?? 0) === $userId) {
                $effectiveRole = 'captain';
            }

            $actions = ['viewDashboard', 'editProfile', 'browseTeams'];
            if ($effectiveRole === 'admin') {
                $actions = array_merge($actions, ['manageUsers', 'manageLeagues', 'manageMatches']);
            }
            if ($effectiveRole === 'captain') {
                $actions[] = 'requestMatch';
                $actions[] = 'manageTeam';
            }
            if ($isPremium) {
                $actions[] = 'usePremiumFeatures';
            }

            echo json_encode([
                'role'                => $effectiveRole,
                'displayName'         => (string) ($user['name'] ?? 'Jugador'),
                'team'                => $team ? [
                    'id'   => (int) $team['id'],
                    'name' => (string) $team['name'],
                    'city' => (string) ($team['city'] ?? ''),
                    'badge'=> (string) ($team['badge'] ?? 'FP'),
                ] : null,
                'isPremium'           => $isPremium,
                'unreadNotifications' => $unread,
                'allowedActions'      => array_values(array_unique($actions)),
                'message'             => $effectiveRole === 'admin'
                    ? 'Panel de administracion activo.'
                    : ($effectiveRole === 'captain'
                        ? 'Eres capitan: puedes gestionar el equipo y solicitar partidos.'
                        : 'Bienvenido a FastPlay.'),
                'generatedAt'         => date('c'),
            ], JSON_UNESCAPED_UNICODE);
        } catch (Throwable $e) {
            error_log('[FastPlay] dashboard/context error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'error'   => 'No se pudo recuperar el contexto del usuario.',
                'role'    => 'guest',
            ], JSON_UNESCAPED_UNICODE);
        }
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
