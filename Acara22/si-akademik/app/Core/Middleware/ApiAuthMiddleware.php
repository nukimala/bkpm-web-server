<?php
// app/Core/Middleware/ApiAuthMiddleware.php
// Middleware autentikasi API berbasis token Bearer (Acara 19).
// Dipasang pada endpoint yang memodifikasi data (POST/PUT/DELETE).
require_once __DIR__ . '/../ApiResponse.php';
require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../../Repositories/TokenRepository.php';

class ApiAuthMiddleware
{
    // Validasi header Authorization: Bearer <token>.
    public static function validate(): void
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (!preg_match('/^Bearer\s+([a-f0-9]{64})$/i', $header, $m)) {
            ApiResponse::error('Token tidak diberikan atau format salah.', 401);
        }

        $repo = new TokenRepository(Database::getInstance());
        if ($repo->findActive($m[1]) === null) {
            ApiResponse::error('Token tidak valid atau sudah kedaluwarsa.', 401);
        }
    }
}