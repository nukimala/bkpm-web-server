<?php
class Mahasiswa
{
    public const STATUS = ['aktif', 'cuti', 'lulus'];

    private int $id;
    private string $nim;
    private string $nama;
    private string $email;
    private int $prodiId;
    private string $prodi;
    private int $angkatan;
    private string $status;

    public function __construct(
        string $nim,
        string $nama,
        string $email = '',
        string $prodi = '',
        string $status = 'aktif',
        int $id = 0,
        int $prodiId = 0,
        int $angkatan = 0
    ) {
        $this->setNim($nim);
        $this->setNama($nama);
        $this->setEmail($email);
        $this->setProdi($prodi);
        $this->setStatus($status);
        $this->setId($id);
        $this->setProdiId($prodiId);
        $this->setAngkatan($angkatan);
    }

    // Getter
    public function getId(): int
    {
        return $this->id;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getProdi(): string
    {
        return $this->prodi;
    }

    public function getProdiId(): int
    {
        return $this->prodiId;
    }

    public function getAngkatan(): int
    {
        return $this->angkatan;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setId(int $id): void
    {
        if ($id < 0) {
            throw new InvalidArgumentException('Id mahasiswa tidak valid.');
        }
        $this->id = $id;
    }

    public function setNim(string $nim): void
    {
        $nim = trim($nim);
        if ($nim === '' || !ctype_digit($nim)) {
            throw new InvalidArgumentException('NIM tidak boleh kosong dan harus berupa angka.');
        }
        $this->nim = $nim;
    }

    public function setNama(string $nama): void
    {
        $nama = trim($nama);
        if ($nama === '') {
            throw new InvalidArgumentException('Nama tidak boleh kosong.');
        }
        $this->nama = $nama;
    }

    public function setEmail(string $email): void
    {
        $email = trim($email);
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Format email tidak valid.');
        }
        $this->email = $email;
    }

    public function setProdi(string $prodi): void
    {
        $this->prodi = trim($prodi);
    }

    public function setProdiId(int $prodiId): void
    {
        if ($prodiId < 0) {
            throw new InvalidArgumentException('Id prodi tidak valid.');
        }
        $this->prodiId = $prodiId;
    }

    public function setAngkatan(int $angkatan): void
    {
        if ($angkatan < 0) {
            throw new InvalidArgumentException('Angkatan tidak valid.');
        }
        $this->angkatan = $angkatan;
    }

    public function setStatus(string $status): void
    {
        if (!in_array($status, self::STATUS, true)) {
            throw new InvalidArgumentException('Status harus aktif, cuti, atau lulus.');
        }
        $this->status = $status;
    }


    public function hitungAngkatan(): int
    {
        if ($this->angkatan > 0) {
            return $this->angkatan;
        }
        return (int) ('20' . substr($this->nim, 0, 2));
    }
}