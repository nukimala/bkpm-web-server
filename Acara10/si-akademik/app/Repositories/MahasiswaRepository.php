<?php
require_once __DIR__ . '/../Core/BaseModel.php';
require_once __DIR__ . '/../Models/MahasiswaModel.php';


class MahasiswaRepository extends BaseModel
{
    private const PER_PAGE = 10;

    private MahasiswaModel $model;

    public function __construct(PDO $pdo)
    {
        // Warisi constructor BaseModel (objek PDO).
        parent::__construct($pdo);
        // Model domain dipakai untuk validasi sebelum data disimpan.
        $this->model = new MahasiswaModel($pdo);
    }

    /** Query dasar dengan JOIN ke tabel prodi. */
    private function select(): string
    {
        return 'SELECT m.id, m.nim, m.nama, m.email, m.prodi_id, m.angkatan, m.status,
                       p.nama AS prodi
                FROM mahasiswa m
                JOIN prodi p ON p.id = m.prodi_id';
    }

    /** potongan WHERE + parameter untuk pencarian. */
    private function filterKeyword(string $keyword): array
    {
        $like = '%' . $keyword . '%';
        return [
            ' WHERE m.nim LIKE :kw1 OR m.nama LIKE :kw2 OR p.nama LIKE :kw3',
            ['kw1' => $like, 'kw2' => $like, 'kw3' => $like],
        ];
    }

    /** Semua data mahasiswa. */
    public function all(): array
    {
        return $this->fetchAll($this->select() . ' ORDER BY m.nim');
    }

    /** Satu mahasiswa berdasarkan id. */
    public function find(int $id): ?array
    {
        return $this->fetchOne($this->select() . ' WHERE m.id = :id', ['id' => $id]);
    }

    /** Satu mahasiswa berdasarkan NIM. */
    public function findByNim(string $nim): ?array
    {
        return $this->fetchOne($this->select() . ' WHERE m.nim = :nim', ['nim' => $nim]);
    }

    /** Pencarian sederhana. */
    public function search(string $keyword): array
    {
        [$where, $params] = $this->filterKeyword($keyword);
        return $this->fetchAll($this->select() . $where . ' ORDER BY m.nim', $params);
    }

    /** Daftar mahasiswa + pagination sederhana untuk halaman index. */
    public function paginate(int $page, string $keyword = ''): array
    {
        $page   = max(1, $page);
        $where  = '';
        $params = [];

        if ($keyword !== '') {
            [$where, $params] = $this->filterKeyword($keyword);
        }

        $from     = ' FROM mahasiswa m JOIN prodi p ON p.id = m.prodi_id';
        $countRow = $this->fetchOne('SELECT COUNT(*) AS total' . $from . $where, $params);
        $total    = (int) ($countRow['total'] ?? 0);

        $offset  = ($page - 1) * self::PER_PAGE;
        $perPage = self::PER_PAGE;

        $sql = $this->select() . $where . ' ORDER BY m.nim LIMIT :limit OFFSET :offset';
        $params['limit']  = $perPage;
        $params['offset'] = $offset;

        return [
            'data'    => $this->fetchAll($sql, $params),
            'total'   => $total,
            'pages'   => max(1, (int) ceil($total / self::PER_PAGE)),
            'page'    => $page,
            'offset'  => $offset,
            'keyword' => $keyword,
        ];
    }

    /** Menyimpan mahasiswa baru, mengembalikan id yang dibuat. */
    public function create(array $data): int
    {
        $errors = $this->model->validate($data);
        if ($errors !== []) {
            throw new InvalidArgumentException(implode(' ', $errors));
        }

        $this->execute(
            'INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
             VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)',
            [
                'nim'      => $data['nim'],
                'nama'     => $data['nama'],
                'email'    => $data['email'],
                'prodi_id' => (int) $data['prodi_id'],
                'angkatan' => (int) $data['angkatan'],
                'status'   => $data['status'] ?? 'aktif',
            ]
        );

        return $this->lastInsertId();
    }

    /** Mengubah data mahasiswa. */
    public function update(int $id, array $data): int
    {
        $errors = $this->model->validate($data);
        if ($errors !== []) {
            throw new InvalidArgumentException(implode(' ', $errors));
        }

        return $this->execute(
            'UPDATE mahasiswa
             SET nim = :nim, nama = :nama, email = :email,
                 prodi_id = :prodi_id, angkatan = :angkatan, status = :status
             WHERE id = :id',
            [
                'id'       => $id,
                'nim'      => $data['nim'],
                'nama'     => $data['nama'],
                'email'    => $data['email'],
                'prodi_id' => (int) $data['prodi_id'],
                'angkatan' => (int) $data['angkatan'],
                'status'   => $data['status'] ?? 'aktif',
            ]
        );
    }

    /** Menghapus mahasiswa. */
    public function delete(int $id): int
    {
        return $this->execute('DELETE FROM mahasiswa WHERE id = :id', ['id' => $id]);
    }
}