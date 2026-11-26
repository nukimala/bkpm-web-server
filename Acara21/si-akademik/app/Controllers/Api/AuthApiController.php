<?php
// app/Controllers/Api/AuthApiController.php
// Autentikasi API (Acara 19): user login agar mendapat token Bearer.
require_once __DIR__ . '/../../Core/ApiResponse.php';
require_once __DIR__ . '/../../Core/ApiRouter.php';
require_once __DIR__ . '/../../Repositories/UserRepository.php';
require_once __DIR__ . '/../../Repositories/TokenRepository.php';

class AuthApiController
{
    // POST /api/auth/login
    public function login(): void
    {
        $data = ApiRouter::input();
        $username = trim($data['username'] ?? '');
        $password = $data['password'] ?? '';

        if ($username === '' || $password === '') {
            ApiResponse::error('Username dan password wajib diisi.', 422);
        }

        $users = new UserRepository(Database::getInstance());
        $user = $users->findByUsername($username);

        if ($user === null || !password_verify($password, $user['password'])) {
            ApiResponse::error('Username atau password salah.', 401);
        }

        $tokens = new TokenRepository(Database::getInstance());
        $tokens->purgeExpired(); // evaluasi Acara 20: bersihkan token kedaluwarsa dulu

        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + 86400); // berlaku 24 jam

        if (!$tokens->create((int)$user['id'], $token, $expiresAt)) {
            ApiResponse::error('Gagal membuat token.', 500);
        }

        ApiResponse::success([
            'token'      => $token,
            'type'       => 'Bearer',
            'expires_at' => $expiresAt,
            'user'       => $user['username'],
        ], 'Login berhasil.');
    }

    // POST /api/auth/logout (melucuti token)
    public function logout(): void
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/^Bearer\s+([a-f0-9]{64})$/i', $header, $m)) {
            $tokens = new TokenRepository(Database::getInstance());
            $tokens->revoke($m[1]);
        }
        ApiResponse::success([], 'Logout berhasil.');
    }
}