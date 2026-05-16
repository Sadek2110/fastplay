<?php
// FastPlay · login / registro / logout

class AuthController extends Controller
{
    public function login(): void
    {
        $this->requireGuest();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();
            $usuario = $this->model('Usuario');
            $email = trim($_POST['email'] ?? '');
            $user = $usuario->login($email, $_POST['password'] ?? '', $_SERVER['REMOTE_ADDR'] ?? '');
            if ($user) {
                login_user($user);
                flash('ok', '¡Bienvenido de vuelta, ' . $user['name'] . '!');
                redirect('dashboard');
            }
            $errors['_'] = 'Email o contraseña incorrectos. Si fallas demasiadas veces, espera 10 min.';
            flash_old(['email' => $email]);
        }

        $this->view('auth/login', [
            'active' => 'login',
            'errors' => $errors,
            'title'  => 'Iniciar sesión — FastPlay',
        ], 'auth');
    }

    public function register(): void
    {
        $this->requireGuest();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_csrf();
            $usuario = $this->model('Usuario');
            [$user, $errors] = $usuario->register($_POST);
            if ($user) {
                login_user($user);
                flash('ok', '¡Cuenta creada! Empieza a jugar.');
                redirect('dashboard');
            }
            flash_old($_POST);
        }

        $this->view('auth/register', [
            'active' => 'register',
            'errors' => $errors,
            'title'  => 'Crear cuenta — FastPlay',
        ], 'auth');
    }

    public function logout(): void
    {
        $this->requirePost();
        logout_user();
        flash('ok', 'Has cerrado sesión.');
        redirect('');
    }
}