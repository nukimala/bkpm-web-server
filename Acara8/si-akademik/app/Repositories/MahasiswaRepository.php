<?php
// app/Repositories/MahasiswaRepository.php
require_once __DIR__ . '/BaseRepository.php';
require_once __DIR__ . '/../Models/Mahasiswa.php';

class MahasiswaRepository extends BaseRepository
{
    private const PER_PAGE = 10;

    private function selectJoin(): string
    {
        return 'SELECT m.id, m.nim, m.nama, m.email, m.prodi_id, m.angkatan, m.status,
                       p.nama AS prodi
                FROM mahasiswa m
                JOIN prodi p ON p.id = m.prodi_id';
    }

    private function whereKeyword(string $keyword): array
    {
        $like = '%' . $keyword . '%';
        return [
            ' WHERE m.nim LIKE :kw1 OR m.nama LIKE :kw2 OR p.nama LIKE :kw3',
            ['kw1' => $like, 'kw2' => $like, 'kw3' => $like],
        ];
    }

    private function map(array $row): Mahasiswa
    {
        return new Mahasiswa(
            $row['nim'],
            $row['nama'],
            $row['prodi'],
            $row['status'],
            (int) $row['id'],
            (int) $row['prodi_id'],
            $row['email'] ?? '',
            (int) ($row['angkatan'] ?? 0)
        );
    }

    private function mapAll(array $rows): array
    {
        return array_map(fn($row) => $this->map($row), $rows);
    }

    public function all(): array
    {
        return $this->mapAll($this->pdo->query($this->selectJoin() . ' ORDER BY m.nim')->fetchAll());
    }

    public function find(int $id): ?Mahasiswa
    {
        $stmt = $this->pdo->prepare($this->selectJoin() . ' WHERE m.id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ? $this->map($row) : null;
    }

    public function findByNim(string $nim): ?Mahasiswa
    {
        $stmt = $this->pdo->prepare($this->selectJoin() . ' WHERE m.nim = :nim');
        $stmt->execute(['nim' => $nim]);
        $row = $stmt->fetch();
        return $row ? $this->map($row) : null;
    }

    public function search(string $keyword): array
    {
        [$where, $params] = $this->whereKeyword($keyword);
        $stmt = $this->pdo->prepare($this->selectJoin() . $where . ' ORDER BY m.nim');
        $stmt->execute($params);
        return $this->mapAll($stmt->fetchAll());
    }

    public function paginate(int $page, string $keyword = ''): array
    {
        $where = '';
        $params = [];
        if ($keyword !== '') {
            [$where, $params] = $this->whereKeyword($keyword);
        }

        $from = ' FROM mahasiswa m JOIN prodi p ON p.id = m.prodi_id';
        $total = $this->count('SELECT COUNT(*)' . $from . $where, $params);

        $result = $this->paginateQuery(
            $this->selectJoin() . $where . ' ORDER BY m.nim',
            $params,
            $page,
            self::PER_PAGE
        );

        $result['total']    = $total;
        $result['pages']    = max(1, (int) ceil($total / self::PER_PAGE));
        $result['data']     = $this->mapAll($result['data']);
        $result['keyword']  = $keyword;
        return $result;
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)'
        );
        return $stmt->execute([
            'nim'      => $data['nim'],
            'nama'     => $data['nama'],
            'email'    => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan'],
            'status'   => $data['status'] ?? 'aktif',
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email,
                 prodi_id = :prodi_id, angkatan = :angkatan, status = :status
             WHERE id = :id'
        );
        return $stmt->execute([
            'id'       => $id,
            'nim'      => $data['nim'],
            'nama'     => $data['nama'],
            'email'    => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan'],
            'status'   => $data['status'] ?? 'aktif',
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM mahasiswa WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}