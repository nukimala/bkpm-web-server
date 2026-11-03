<?php
// app/Core/ApiResponse.php
// Helper pembuatan respon JSON untuk endpoint API (Acara 15).
// Acara 18: ditambah header CORS, preflight OPTIONS, dan JSON encoding terpusat.
class ApiResponse
{
    private static function cors(): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }

    // Kirim data apa pun sebagai JSON lalu berhenti.
    public static function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        self::cors();
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        exit;
    }

    // Format respon sukses: mengemas data dengan status.
    public static function success(mixed $data = [], ?string $message = null, int $status = 200): void
    {
        self::json([
            'success' => true,
            'message' => $message ?? 'OK',
            'data'    => $data,
        ], $status);
    }

    // Format respon error: kode status HTTP sesuai jenis kesalahan.
    public static function error(string $message, int $status = 400, array $errors = []): void
    {
        self::json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    // Respon tanpa isi, mis. DELETE berhasil -> 204 No Content, atau preflight OPTIONS.
    public static function noContent(int $status = 204): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        self::cors();
        exit;
    }
}