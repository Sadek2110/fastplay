<?php
require_once CORE_PATH . '/Controller.php';
require_once APP_PATH  . '/models/User.php';
require_once APP_PATH  . '/models/MatchModel.php';
require_once APP_PATH  . '/models/Team.php';

class UserController extends Controller {

    public function dashboard(): void {
        $this->requireLogin();
        $userId    = $_SESSION['user_id'];
        $userModel = new User();
        $stats     = $userModel->getStats($userId);
        $team      = (new Team())->getTeamByPlayer($userId);
        $upcoming  = (new MatchModel())->getUpcoming(3);
        $achievements = $userModel->getAchievements($userId);
        $this->render('user/dashboard', [
            'stats'          => $stats,
            'team'           => $team,
            'upcomingMatches'=> $upcoming,
            'achievements'   => $achievements,
        ]);
    }

    public function profile(): void {
        $this->requireLogin();
        $user = (new User())->findById($_SESSION['user_id']);
        $this->render('user/profile', compact('user'));
    }

    public function update(): void {
        $this->requireLogin();
        $userId = $_SESSION['user_id'];
        $data   = [
            'name'     => htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'phone'    => trim($_POST['phone'] ?? ''),
            'city'     => htmlspecialchars(trim($_POST['city'] ?? ''), ENT_QUOTES, 'UTF-8'),
            'position' => $_POST['position'] ?? '',
            'age'      => (int)($_POST['age'] ?? 0) ?: null,
            'height'   => (int)($_POST['height'] ?? 0) ?: null,
        ];

        // Photo upload
        if (!empty($_FILES['photo']['tmp_name'])) {
            $allowed = ['image/jpeg','image/png','image/webp'];
            $finfo   = new finfo(FILEINFO_MIME_TYPE);
            $mime    = $finfo->file($_FILES['photo']['tmp_name']);
            if (in_array($mime, $allowed) && $_FILES['photo']['size'] <= UPLOAD_MAX_SIZE) {
                $ext      = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime];
                $filename = bin2hex(random_bytes(12)) . '.' . $ext;
                move_uploaded_file($_FILES['photo']['tmp_name'], UPLOAD_PATH . '/profiles/' . $filename);
                $data['photo'] = $filename;
                $_SESSION['user_photo'] = $filename;
            }
        }

        (new User())->update($userId, $data);
        $_SESSION['user_name'] = $data['name'];
        $this->flash('success', 'Perfil actualizado.');
        $this->redirect('/profile');
    }
}
