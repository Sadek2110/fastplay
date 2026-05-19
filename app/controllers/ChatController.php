<?php

class ChatController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        flash('warn', 'El chat solo esta disponible dentro de un equipo o una negociacion de partido.');
        redirect('teams');
    }

    public function team(string $teamId = ''): void
    {
        $this->requireAuth();
        $teamId = (int) $teamId;
        if (!is_admin() && !Database::value('SELECT 1 FROM team_members WHERE team_id=? AND user_id=?', [$teamId, (int) current_user()['id']])) {
            flash('warn', 'No tienes acceso al chat de este equipo.');
            redirect('teams');
            return;
        }
        $roomId = (int) Database::value("SELECT id FROM chat_rooms WHERE type='team' AND team_id=?", [$teamId]);
        if (!$roomId) {
            $teamName = (string) Database::value('SELECT name FROM teams WHERE id=?', [$teamId]);
            $roomId = $this->model('Chat')->createRoom('Equipo: ' . $teamName, 'team', $teamId);
        }
        redirect('chat/room/' . $roomId);
    }

    public function matchNegotiation(string $matchRequestId = ''): void
    {
        $this->requireAuth();
        $matchRequestId = (int) $matchRequestId;
        $roomId = (int) Database::value("SELECT id FROM chat_rooms WHERE type='match_negotiation' AND match_request_id=?", [$matchRequestId]);
        if (!$roomId) {
            flash('warn', 'El chat se habilita cuando se acepta la solicitud.');
            redirect('match-request/show/' . $matchRequestId);
            return;
        }
        redirect('chat/room/' . $roomId);
    }

    public function room(string $id = ''): void
    {
        $this->requireAuth();
        $id = (int) $id;
        $chat = $this->model('Chat');
        $room = $chat->room($id);
        if (!$room) { Router::notFound(); return; }
        if (!$chat->canAccessRoom($room, (int) current_user()['id'], is_admin())) {
            flash('warn', 'No tienes acceso a esta sala.');
            redirect('teams');
            return;
        }
        $msgs = array_reverse($chat->messages($id));
        $this->view('chat/room', [
            'active' => 'chat',
            'room' => $room,
            'messages' => $msgs,
            'title' => $room['name'] . ' - Chat - FastPlay',
        ]);
    }

    public function send(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        $id = (int) $id;
        $res = $this->model('Chat')->send($id, (int) current_user()['id'], (string) ($_POST['body'] ?? ''), is_admin());
        if (!empty($res['error'])) {
            flash('warn', $res['error']);
        }
        redirect('chat/room/' . $id);
    }

    public function messages(string $id = ''): void
    {
        $this->requireAuth();
        $id = (int) $id;
        $chat = $this->model('Chat');
        $room = $chat->room($id);
        if (!$room || !$chat->canAccessRoom($room, (int) current_user()['id'], is_admin())) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Acceso denegado']);
            return;
        }
        header('Content-Type: application/json');
        $msgs = array_reverse($chat->messages($id));
        echo json_encode(array_map(static function ($m) {
            return [
                'id' => (int) $m['id'],
                'user_name' => $m['user_name'],
                'body' => $m['body'],
                'created_at' => date('d/m H:i', strtotime($m['created_at'])),
                'own' => (int) $m['user_id'] === (int) current_user()['id'],
            ];
        }, $msgs));
    }
}
