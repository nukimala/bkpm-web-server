<?php
// app/Repositories/UserRepository.php
// Akses data tabel users untuk autentikasi berbasis database (Acara 14).
require_once __DIR__ . '/../Core/Database.php';

class UserRepository
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    private function pdo(): PDO
    {
        return $this->database->getConnection();
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = $this->pdo()->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}