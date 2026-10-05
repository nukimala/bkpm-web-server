<?php
class AuthMiddleware
{
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user'] !== '';
    }

    public function handle(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!self::isLoggedIn()) {
            $_SESSION['flash'] = 'Silakan login terlebih dahulu.';
            header('Location: ' . BASE_PATH . '/login');
            exit;
        }
    }

    public static function requireLogin(string $base_path, bool $flashIfNotLogged = true): void
    {
        if (!self::isLoggedIn()) {
            if ($flashIfNotLogged) {
                $_SESSION['flash'] = 'Silakan login terlebih dahulu.';
            }
            header('Location: ' . $base_path . '/login');
            exit;
        }
    }
}