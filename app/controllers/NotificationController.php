<?php

class NotificationController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $filter = ($_GET['filter'] ?? 'all') === 'unread' ? 'unread' : 'all';
        $model = $this->model('Notification');
        $userId = (int) current_user()['id'];
        $joinRequests = [];
        try {
            $raw = $this->model('TeamJoinRequest')->pendingForCaptain($userId);
            foreach ($raw as $r) {
                $joinRequests[(int) $r['id']] = $r;
            }
        } catch (Throwable $e) { /* not captain or table missing */ }
        $this->view('notifications/index', [
            'active'       => 'notification',
            'filter'       => $filter,
            'notifications' => $model->forUser($userId, $filter),
            'unread'       => $model->unreadCount($userId),
            'joinRequests' => $joinRequests,
            'title'        => 'Notificaciones - FastPlay',
        ]);
    }

    public function markRead(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->model('Notification')->markRead((int) $id, (int) current_user()['id']);
        $this->back('notification');
    }

    public function markAllRead(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->model('Notification')->markAllRead((int) current_user()['id']);
        redirect('notification');
    }

    public function unreadCount(): void
    {
        $this->requireAuth();
        header('Content-Type: application/json');
        echo json_encode(['count' => $this->model('Notification')->unreadCount((int) current_user()['id'])]);
    }
}
