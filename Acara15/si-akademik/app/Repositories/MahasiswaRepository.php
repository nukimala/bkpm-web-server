<?php
// app/Repositories/MahasiswaRepository.php
require_once __DIR__ . '/../Core/Database.php';
require_once __DIR__ . '/../Models/Mahasiswa.php';

class MahasiswaRepository
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

    private function selectJoin(): string
    {
        return "SELECT m.id, m.nim, m.nama, m.prodi_id, m.status, m.alamat,
                       p.nama AS prodi
                FROM mahasiswa m
                JOIN prodi p ON p.id = m.prodi_id";
    }

    private function map(array $row): Mahasiswa
    {
        return new Mahasiswa(
            $row['nim'],
            $row['nama'],
            $row['prodi'],
            $row['status'],
            (int)$row['id'],
            (int)$row['prodi_id'],
            $row['alamat'] ?? ''
        );
    }

    public function all(): array
    {
        $stmt = $this->pdo()->query($this->selectJoin() . ' ORDER BY m.nim');
        return array_map(fn($row) => $this->map($row), $stmt->fetchAll());
    }

    public function find(int $id): ?Mahasiswa
    {
        $stmt = $this->pdo()->prepare($this->selectJoin() . ' WHERE m.id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->map($row) : null;
    }

    public function findByNim(string $nim): ?Mahasiswa
    {
        $stmt = $this->pdo()->prepare($this->selectJoin() . ' WHERE m.nim = :nim');
        $stmt->execute(['nim' => $nim]);
        $row = $stmt->fetch();
        return $row ? $this->map($row) : null;
    }

    public function search(string $keyword): array
    {
        $like = '%' . $keyword . '%';
        $stmt = $this->pdo()->prepare(
            $this->selectJoin() .
            ' WHERE m.nim LIKE :k OR m.nama LIKE :k OR p.nama LIKE :k ORDER BY m.nim'
        );
        $stmt->execute(['k' => $like]);
        return array_map(fn($row) => $this->map($row), $stmt->fetchAll());
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo()->prepare(
            'INSERT INTO mahasiswa (nim, nama, prodi_id, status, alamat)
             VALUES (:nim, :nama, :prodi_id, :status, :alamat)'
        );
        return $stmt->execute([
            'nim'      => $data['nim'],
            'nama'     => $data['nama'],
            'prodi_id' => $data['prodi_id'],
            'status'   => $data['status'] ?? 'aktif',
            'alamat'   => $data['alamat'] ?? '',
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo()->prepare(
            'UPDATE mahasiswa
             SET nim = :nim, nama = :nama, prodi_id = :prodi_id, status = :status, alamat = :alamat
             WHERE id = :id'
        );
        return $stmt->execute([
            'id'       => $id,
            'nim'      => $data['nim'],
            'nama'     => $data['nama'],
            'prodi_id' => $data['prodi_id'],
            'status'   => $data['status'] ?? 'aktif',
            'alamat'   => $data['alamat'] ?? '',
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo()->prepare('DELETE FROM mahasiswa WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function countByProdi(int $prodiId): int
    {
        $stmt = $this->pdo()->prepare('SELECT COUNT(*) AS jumlah FROM mahasiswa WHERE prodi_id = :id');
        $stmt->execute(['id' => $prodiId]);
        return (int)$stmt->fetch()['jumlah'];
    }
}