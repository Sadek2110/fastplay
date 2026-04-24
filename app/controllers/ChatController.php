<?php
require_once CORE_PATH . '/Controller.php';
require_once APP_PATH  . '/models/Chat.php';

class ChatController extends Controller {

    public function index(): void {
        $this->requireLogin();
        $rooms = (new Chat())->getRoomsByUser($_SESSION['user_id']);
        $this->render('chat/index', compact('rooms'));
    }

    public function room(string $id): void {
        $this->requireLogin();
        $chat     = new Chat();
        $room     = $chat->findById((int)$id);
        if (!$room) { $this->redirect('/chat'); }
        $messages = $chat->getMessages((int)$id);
        $this->render('chat/room', compact('room', 'messages'));
    }
}
