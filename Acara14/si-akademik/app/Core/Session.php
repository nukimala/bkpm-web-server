<?php
// app/Core/Session.php
// Helper pengelola sesi: flash message dan old input untuk pola PRG (Acara 13).
class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    // Ambil flash sekali lalu hapus (PRG / Post-Redirect-Get).
    public static function flash(string $key): string
    {
        $value = $_SESSION[$key] ?? '';
        unset($_SESSION[$key]);
        return $value;
    }

    // Simpan input lama supaya bisa diisi ulang ke form saat validasi gagal.
    public static function withOldInput(array $data): void
    {
        $_SESSION['_old'] = $data;
    }

    public static function old(string $key, string $default = ''): string
    {
        return $_SESSION['_old'][$key] ?? $default;
    }

    public static function clearOld(): void
    {
        unset($_SESSION['_old']);
    }
}