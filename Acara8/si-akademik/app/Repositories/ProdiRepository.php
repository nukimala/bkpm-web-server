<?php
// app/Repositories/ProdiRepository.php
require_once __DIR__ . '/BaseRepository.php';

class ProdiRepository extends BaseRepository
{
    private const PER_PAGE = 10;

    public function all(): array
    {
        return $this->pdo->query('SELECT id, kode, nama FROM prodi ORDER BY kode')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, kode, nama FROM prodi WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function options(): array
    {
        return $this->pdo->query('SELECT id, kode, nama FROM prodi ORDER BY nama')->fetchAll();
    }

    private function whereKeyword(string $keyword): array
    {
        $like = '%' . $keyword . '%';
        return [
            ' WHERE kode LIKE :kw1 OR nama LIKE :kw2',
            ['kw1' => $like, 'kw2' => $like],
        ];
    }

    public function paginate(int $page, string $keyword = ''): array
    {
        $where = '';
        $params = [];
        if ($keyword !== '') {
            [$where, $params] = $this->whereKeyword($keyword);
        }

        $total = $this->count('SELECT COUNT(*) FROM prodi' . $where, $params);
        $result = $this->paginateQuery(
            'SELECT id, kode, nama FROM prodi' . $where . ' ORDER BY kode',
            $params,
            $page,
            self::PER_PAGE
        );

        $result['total']   = $total;
        $result['pages']   = max(1, (int) ceil($total / self::PER_PAGE));
        $result['keyword'] = $keyword;
        return $result;
    }

    public function create(array $data): bool
    {
        $stmt = $this->pdo->prepare('INSERT INTO prodi (kode, nama) VALUES (:kode, :nama)');
        return $stmt->execute([
            'kode' => $data['kode'],
            'nama' => $data['nama'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare('UPDATE prodi SET kode = :kode, nama = :nama WHERE id = :id');
        return $stmt->execute([
            'id'   => $id,
            'kode' => $data['kode'],
            'nama' => $data['nama'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM prodi WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}