<?php
require_once __DIR__ . '/../Core/BaseModel.php';
require_once __DIR__ . '/../Models/ProdiModel.php';

class ProdiRepository extends BaseModel
{
    private ProdiModel $model;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->model = new ProdiModel($pdo);
    }

    public function all(): array
    {
        return $this->fetchAll('SELECT id, kode, nama FROM prodi ORDER BY nama');
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne('SELECT id, kode, nama FROM prodi WHERE id = :id', ['id' => $id]);
    }

    public function search(string $keyword): array
    {
        $like = '%' . $keyword . '%';
        return $this->fetchAll(
            'SELECT id, kode, nama FROM prodi WHERE kode LIKE :kw1 OR nama LIKE :kw2 ORDER BY nama',
            ['kw1' => $like, 'kw2' => $like]
        );
    }

    public function options(): array
    {
        return $this->fetchAll('SELECT id, kode, nama FROM prodi ORDER BY nama');
    }

    public function jumlahMahasiswa(): array
    {
        return $this->fetchAll(
            'SELECT p.id, p.kode, p.nama, COUNT(m.id) AS jumlah
             FROM prodi p
             LEFT JOIN mahasiswa m ON m.prodi_id = p.id
             GROUP BY p.id, p.kode, p.nama
             ORDER BY p.nama'
        );
    }

    public function create(array $data): int
    {
        $errors = $this->model->validate($data);
        if ($errors !== []) {
            throw new InvalidArgumentException(implode(' ', $errors));
        }

        $this->execute(
            'INSERT INTO prodi (kode, nama) VALUES (:kode, :nama)',
            ['kode' => $data['kode'], 'nama' => $data['nama']]
        );

        return $this->lastInsertId();
    }

    public function update(int $id, array $data): int
    {
        $errors = $this->model->validate($data);
        if ($errors !== []) {
            throw new InvalidArgumentException(implode(' ', $errors));
        }

        return $this->execute(
            'UPDATE prodi SET kode = :kode, nama = :nama WHERE id = :id',
            ['id' => $id, 'kode' => $data['kode'], 'nama' => $data['nama']]
        );
    }

    public function delete(int $id): int
    {
        return $this->execute('DELETE FROM prodi WHERE id = :id', ['id' => $id]);
    }
}