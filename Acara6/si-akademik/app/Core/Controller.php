<?php

require_once __DIR__ . '/Middleware/AuthMiddleware.php';

class Controller
{
    protected string $base_path = BASE_PATH;

    protected function render(string $view, array $data = []): void
    {
        extract($data);
        $content = __DIR__ . '/../Views/' . $view;
        require __DIR__ . '/../Views/layouts/main.php';
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . ($path === '' ? $this->base_path . '/' : $this->base_path . '/' . ltrim($path, '/')));
        exit;
    }

    public static function setFlash(string $message): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash'] = $message;
    }

    public static function flash(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['flash'])) {
            return '';
        }
        $message = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $message;
    }
}