<?php
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

    public static function success(mixed $data = [], ?string $message = null, int $status = 200): void
    {
        self::json([
            'success' => true,
            'message' => $message ?? 'OK',
            'data'    => $data,
        ], $status);
    }

    public static function error(string $message, int $status = 400, array $errors = []): void
    {
        self::json([
            'success' => false,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    public static function noContent(int $status = 204): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        exit;
    }
}