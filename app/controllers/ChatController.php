<?php
// FastPlay · chat (salas + mensajes)

class ChatController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        $chat = $this->model('Chat');
        $this->view('chat/index', [
            'active' => 'chat',
            'rooms'  => $chat->rooms(),
            'title'  => 'Chat — FastPlay',
        ]);
    }

    public function room(string $id = ''): void
    {
        $this->requireAuth();
        $id = (int) $id;
        $chat = $this->model('Chat');
        $room = $chat->room($id);
        if (!$room) { Router::notFound(); return; }

        $msgs = array_reverse($chat->messages($id));
        $this->view('chat/room', [
            'active'   => 'chat',
            'room'     => $room,
            'messages' => $msgs,
            'rooms'    => $chat->rooms(),
            'title'    => $room['name'] . ' — Chat — FastPlay',
        ]);
    }

    public function send(string $id = ''): void
    {
        $this->requireAuth();
        $this->requirePost();
        $id = (int) $id;
        $chat = $this->model('Chat');
        $res = $chat->send($id, (int) current_user()['id'], (string) ($_POST['body'] ?? ''));
        if (!empty($res['error'])) {
            flash('warn', $res['error']);
        }
        redirect('chat/room/' . $id);
    }
}