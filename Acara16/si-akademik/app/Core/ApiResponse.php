<?php
// app/Core/ApiResponse.php
// Helper pembuatan respon JSON untuk endpoint API (Acara 15).
class ApiResponse
{
    // Kirim data apa pun sebagai JSON lalu berhenti.
    public static function json(mixed $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
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

    // Respon tanpa isi, mis. DELETE berhasil -> 204 No Content (Acara 16).
    public static function noContent(int $status = 204): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        exit;
    }
}