<?php
// app/Controllers/AuthController.php
require_once __DIR__ . '/../Core/Controller.php';

class AuthController extends Controller
{
    private const VALID_USERNAME = 'admin';
    private const VALID_PASSWORD = 'admin123';

    public function loginForm(): void
    {
        if (AuthMiddleware::isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $this->render('auth/login.php');
    }

    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === self::VALID_USERNAME && $password === self::VALID_PASSWORD) {
            $_SESSION['user'] = $username;
            self::setFlash('Selamat datang, ' . $username . '!');
            $this->redirect('dashboard');
        }

        self::setFlash('Username atau password salah.');
        $this->render('auth/login.php');
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        session_unset();
        session_destroy();

        session_start();
        self::setFlash('Anda telah logout.');

        $this->redirect('login');
    }
}