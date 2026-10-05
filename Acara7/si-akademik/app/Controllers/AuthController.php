<?php
// app/Controllers/AuthController.php
require_once __DIR__ . '/../Core/Middleware/AuthMiddleware.php';

class AuthController
{
    private string $base_path = BASE_PATH;

    private function setFlash(string $message): void
    {
        $_SESSION['flash'] = $message;
    }

    public function showLogin(): void
    {
        if (AuthMiddleware::isLoggedIn()) {
            header('Location: ' . $this->base_path . '/dashboard');
            exit;
        }
        $content = __DIR__ . '/../Views/auth/login.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function login(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        $validUser = 'admin';
        $validPass = 'admin123';

        if ($username === $validUser && $password === $validPass) {
            $_SESSION['user'] = $username;
            $this->setFlash('Login berhasil. Selamat datang, ' . $username . '!');
            header('Location: ' . $this->base_path . '/dashboard');
            exit;
        }

        $this->setFlash('Username atau password salah.');
        $content = __DIR__ . '/../Views/auth/login.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }

    public function logout(): void
    {
        session_unset();
        session_destroy();
        header('Location: ' . $this->base_path . '/login');
        exit;
    }
}