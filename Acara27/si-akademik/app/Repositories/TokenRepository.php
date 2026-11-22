<?php
// app/Repositories/TokenRepository.php
// Repositori token API (Acara 19): menyimpan token login untuk konsumen API.
require_once __DIR__ . '/../Core/Database.php';

class TokenRepository
{
    private Database $db;

    public function __construct(Database $database)
    {
        $this->db = $database;
    }

    // Simpan token baru milik user tertentu.
    public function create(int $userId, string $token, string $expiresAt): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO api_tokens (user_id, token, expires_at) VALUES (?, ?, ?)'
        );
        return $stmt->execute([$userId, $token, $expiresAt]);
    }

    // Cari token yang masih aktif (belum lewat expired) dan ambil role user (Acara 25: RBAC)
    public function findActive(string $token): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT t.*, u.username, u.nama AS user_nama, COALESCE(u.role, "user") AS role
             FROM api_tokens t
             JOIN users u ON t.user_id = u.id
             WHERE t.token = ? AND t.expires_at > NOW()
             LIMIT 1'
        );
        $stmt->execute([$token]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    // Hapus token (logout).
    public function revoke(string $token): bool
    {
        $stmt = $this->db->prepare('DELETE FROM api_tokens WHERE token = ?');
        return $stmt->execute([$token]);
    }

    // Evaluasi (Acara 20): bersihkan token yang sudah kedaluwarsa.
    public function purgeExpired(): bool
    {
        return $this->db->prepare('DELETE FROM api_tokens WHERE expires_at <= NOW()')->execute();
    }
}
