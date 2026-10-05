<?php
require_once __DIR__ . '/../Core/BaseModel.php';
require_once __DIR__ . '/../Models/MatakuliahModel.php';


class MatakuliahRepository extends BaseModel
{
    private MatakuliahModel $model;

    public function __construct(PDO $pdo)
    {
        parent::__construct($pdo);
        $this->model = new MatakuliahModel($pdo);
    }

    private function select(): string
    {
        return 'SELECT mk.id, mk.kode, mk.nama, mk.sks, mk.prodi_id, p.nama AS prodi
                FROM matakuliah mk
                JOIN prodi p ON p.id = mk.prodi_id';
    }

    public function all(): array
    {
        return $this->fetchAll($this->select() . ' ORDER BY mk.nama');
    }

    public function find(int $id): ?array
    {
        return $this->fetchOne($this->select() . ' WHERE mk.id = :id', ['id' => $id]);
    }

    public function search(string $keyword): array
    {
        $like = '%' . $keyword . '%';
        return $this->fetchAll(
            $this->select() . ' WHERE mk.kode LIKE :kw1 OR mk.nama LIKE :kw2 OR p.nama LIKE :kw3
             ORDER BY mk.nama',
            ['kw1' => $like, 'kw2' => $like, 'kw3' => $like]
        );
    }

    public function create(array $data): int
    {
        $errors = $this->model->validate($data);
        if ($errors !== []) {
            throw new InvalidArgumentException(implode(' ', $errors));
        }

        $this->execute(
            'INSERT INTO matakuliah (kode, nama, sks, prodi_id) VALUES (:kode, :nama, :sks, :prodi_id)',
            [
                'kode'    => $data['kode'],
                'nama'    => $data['nama'],
                'sks'     => (int) $data['sks'],
                'prodi_id' => (int) $data['prodi_id'],
            ]
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
            'UPDATE matakuliah SET kode = :kode, nama = :nama, sks = :sks, prodi_id = :prodi_id
             WHERE id = :id',
            [
                'id'      => $id,
                'kode'    => $data['kode'],
                'nama'    => $data['nama'],
                'sks'     => (int) $data['sks'],
                'prodi_id' => (int) $data['prodi_id'],
            ]
        );
    }

    public function delete(int $id): int
    {
        return $this->execute('DELETE FROM matakuliah WHERE id = :id', ['id' => $id]);
    }
}