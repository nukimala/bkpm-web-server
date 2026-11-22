<?php
// app/Core/Middleware/ApiAuthMiddleware.php
// Middleware autentikasi & otorisasi API berbasis Bearer Token dan Role (Acara 19 & 25).
// Acara 25: Menambahkan Role-Based Access Control (RBAC) dengan requireAdmin().
require_once __DIR__ . '/../ApiResponse.php';
require_once __DIR__ . '/../Database.php';
require_once __DIR__ . '/../../Repositories/TokenRepository.php';

class ApiAuthMiddleware
{
    private static ?array $userPayload = null;

    // Validasi header Authorization: Bearer <token>.
    public static function validate(): array
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (!preg_match('/^Bearer\s+([a-f0-9]{64})$/i', $header, $m)) {
            ApiResponse::error('Token tidak diberikan atau format salah.', 401);
        }

        $repo = new TokenRepository(Database::getInstance());
        $tokenData = $repo->findActive($m[1]);
        if ($tokenData === null) {
            ApiResponse::error('Token tidak valid atau sudah kedaluwarsa.', 401);
        }

        self::$userPayload = $tokenData;
        return $tokenData;
    }

    // Acara 25 (Langkah 1): Memeriksa apakah token terverifikasi DAN memiliki role admin
    public static function requireAdmin(): array
    {
        $payload = self::validate();
        if (($payload['role'] ?? '') !== 'admin') {
            ApiResponse::error('Akses ditolak, hanya admin yang diizinkan.', 403);
        }
        return $payload;
    }

    // Mendapatkan data user yang sedang aktif
    public static function user(): ?array
    {
        return self::$userPayload;
    }
}
