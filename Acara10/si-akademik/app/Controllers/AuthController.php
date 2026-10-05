<?php
require_once __DIR__ . '/../Core/BaseController.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class AuthController extends BaseController
{
    public function showLogin(): void
    {
        if (AuthMiddleware::isLoggedIn()) {
            $this->redirect('/dashboard');
        }

        $this->view('auth/login');
    }

    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $validUser = 'admin';
        $validPass = 'admin123';

        if ($username === $validUser && $password === $validPass) {
            $_SESSION['user'] = $username;
            $this->redirect('/dashboard', 'Login berhasil. Selamat datang, ' . $username . '!');
        }

        $this->view('auth/login', [], 'Username atau password salah.');
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->redirect('/login');
    }
}