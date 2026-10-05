<?php
// app/Repositories/MatakuliahRepository.php
require_once __DIR__ . '/../Core/Database.php';

class MatakuliahRepository
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
        return $this->pdo()->query('SELECT * FROM matakuliah ORDER BY kode')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo()->prepare('SELECT * FROM matakuliah WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo()->prepare(
            'INSERT INTO matakuliah (kode, nama, sks) VALUES (:kode, :nama, :sks)'
        );
        return $stmt->execute([
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'sks'  => $data['sks'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo()->prepare(
            'UPDATE matakuliah SET kode = :kode, nama = :nama, sks = :sks WHERE id = :id'
        );
        return $stmt->execute([
            'id'   => $id,
            'kode' => $data['kode'],
            'nama' => $data['nama'],
            'sks'  => $data['sks'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo()->prepare('DELETE FROM matakuliah WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}