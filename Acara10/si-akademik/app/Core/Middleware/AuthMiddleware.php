<?php
// app/Core/Middleware/AuthMiddleware.php
class AuthMiddleware
{
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user'] !== '';
    }

    public static function requireLogin(string $base_path, bool $flashIfNotLogged = true): void
    {
        if (!self::isLoggedIn()) {
            if ($flashIfNotLogged) {
                $_SESSION['flash_error'] = 'Silakan login terlebih dahulu.';
            }
            header('Location: ' . $base_path . '/login');
            exit;
        }
    }
}