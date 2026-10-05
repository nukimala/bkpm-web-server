<?php
// app/Repositories/MatakuliahRepository.php
require_once __DIR__ . '/BaseRepository.php';

class MatakuliahRepository extends BaseRepository
{
    private const PER_PAGE = 10;

    private function selectJoin(): string
    {
        return 'SELECT m.id, m.kode, m.nama, m.sks, m.prodi_id, p.nama AS prodi
                FROM matakuliah m
                JOIN prodi p ON p.id = m.prodi_id';
    }

    public function all(): array
    {
        return $this->pdo->query($this->selectJoin() . ' ORDER BY m.kode')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare($this->selectJoin() . ' WHERE m.id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    private function whereKeyword(string $keyword): array
    {
        $like = '%' . $keyword . '%';
        return [
            ' WHERE m.kode LIKE :kw1 OR m.nama LIKE :kw2 OR p.nama LIKE :kw3',
            ['kw1' => $like, 'kw2' => $like, 'kw3' => $like],
        ];
    }

    public function paginate(int $page, string $keyword = ''): array
    {
        $where = '';
        $params = [];
        if ($keyword !== '') {
            [$where, $params] = $this->whereKeyword($keyword);
        }

        $from = ' FROM matakuliah m JOIN prodi p ON p.id = m.prodi_id';
        $total = $this->count('SELECT COUNT(*)' . $from . $where, $params);
        $result = $this->paginateQuery(
            $this->selectJoin() . $where . ' ORDER BY m.kode',
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
        $stmt = $this->pdo->prepare(
            'INSERT INTO matakuliah (kode, nama, sks, prodi_id)
             VALUES (:kode, :nama, :sks, :prodi_id)'
        );
        return $stmt->execute([
            'kode'     => $data['kode'],
            'nama'     => $data['nama'],
            'sks'      => $data['sks'],
            'prodi_id' => $data['prodi_id'],
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare(
            'UPDATE matakuliah
             SET kode = :kode, nama = :nama, sks = :sks, prodi_id = :prodi_id
             WHERE id = :id'
        );
        return $stmt->execute([
            'id'       => $id,
            'kode'     => $data['kode'],
            'nama'     => $data['nama'],
            'sks'      => $data['sks'],
            'prodi_id' => $data['prodi_id'],
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM matakuliah WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }
}