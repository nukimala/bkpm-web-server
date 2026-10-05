<?php
// app/Controllers/AuthController.php
require_once __DIR__ . '/../Core/Controller.php';
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class AuthController extends Controller
{
    // Tugas Mandiri (Acara 6): pesan flash "Login berhasil" muncul sekali lalu hilang.
    private function setFlash(string $message): void
    {
        $_SESSION['flash'] = $message;
    }

    public function showLogin(): void
    {
        if (AuthMiddleware::isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login.php');
    }

    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        // Kredensial sementara (hardcoded). Di produksi diganti database + password_hash().
        $validUser = 'admin';
        $validPass = 'admin123';

        if ($username === $validUser && $password === $validPass) {
            $_SESSION['user'] = $username;
            $this->setFlash('Login berhasil. Selamat datang, ' . $username . '!');
            $this->redirect('/dashboard');
        }

        $this->setFlash('Username atau password salah.');
        $this->view('auth/login.php');
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        $this->redirect('/login');
    }
}