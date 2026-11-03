<?php
// app/Repositories/ProdiRepository.php
// Repository dengan Dependency Injection (Acara 9).
require_once __DIR__ . '/../Core/Database.php';

class ProdiRepository
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

    public function all(): array
    {
        return $this->pdo()->query('SELECT * FROM prodi ORDER BY kode')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo()->prepare('SELECT * FROM prodi WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo()->prepare(
            'INSERT INTO prodi (kode, nama, ka_prodi) VALUES (:kode, :nama, :ka_prodi)'
        );
        return $stmt->execute([
            'kode'     => $data['kode'],
            'nama'     => $data['nama'],
            'ka_prodi' => $data['ka_prodi'] ?? '',
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo()->prepare(
            'UPDATE prodi SET kode = :kode, nama = :nama, ka_prodi = :ka_prodi WHERE id = :id'
        );
        return $stmt->execute([
            'id'       => $id,
            'kode'     => $data['kode'],
            'nama'     => $data['nama'],
            'ka_prodi' => $data['ka_prodi'] ?? '',
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo()->prepare('DELETE FROM prodi WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}