<?php
// app/Models/Mahasiswa.php
// Class Mahasiswa dengan properti privat, getter/setter, dan validasi di setter (Acara 9).

class Mahasiswa
{
    private int $id;
    private string $nim;
    private string $nama;
    private int $prodiId;
    private string $prodi;
    private string $status;
    private string $alamat;

    public function __construct(
        string $nim,
        string $nama,
        string $prodi,
        string $status = 'aktif',
        int $id = 0,
        int $prodiId = 0,
        string $alamat = ''
    ) {
        $this->setNim($nim);
        $this->setNama($nama);
        $this->setProdi($prodi);
        $this->setStatus($status);
        $this->id = $id;
        $this->setProdiId($prodiId);
        $this->setAlamat($alamat);
    }

    // ---------- Getter ----------
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

    public function getProdi(): string
    {
        return $this->prodi;
    }

    public function getProdiId(): int
    {
        return $this->prodiId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getAlamat(): string
    {
        return $this->alamat;
    }

    // ---------- Setter dengan validasi ----------
    public function setNim(string $nim): void
    {
        if ($nim === '' || !ctype_digit($nim)) {
            throw new InvalidArgumentException('NIM tidak boleh kosong dan harus berupa angka.');
        }
        $this->nim = $nim;
    }

    public function setNama(string $nama): void
    {
        if (trim($nama) === '') {
            throw new InvalidArgumentException('Nama tidak boleh kosong.');
        }
        $this->nama = trim($nama);
    }

    public function setProdi(string $prodi): void
    {
        if (trim($prodi) === '') {
            throw new InvalidArgumentException('Nama prodi tidak boleh kosong.');
        }
        $this->prodi = trim($prodi);
    }

    public function setProdiId(int $prodiId): void
    {
        if ($prodiId < 0) {
            throw new InvalidArgumentException('ID prodi tidak valid.');
        }
        $this->prodiId = $prodiId;
    }

    public function setStatus(string $status): void
    {
        if (!in_array($status, ['aktif', 'nonaktif'], true)) {
            throw new InvalidArgumentException('Status harus aktif atau nonaktif.');
        }
        $this->status = $status;
    }

    public function setAlamat(string $alamat): void
    {
        $this->alamat = $alamat;
    }

    // Tugas Mandiri (Acara 4): metode untuk menampilkan angkatan dari NIM.
    // Contoh: NIM "2401001" diawali "24" -> angkatan 2024.
    public function getAngkatan(): int
    {
        $duaDigit = substr($this->nim, 0, 2);
        return (int)('20' . $duaDigit);
    }
}