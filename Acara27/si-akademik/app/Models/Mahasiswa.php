<?php
// app/Models/Mahasiswa.php
// Class Mahasiswa dengan properti privat, getter/setter, validasi di setter (Acara 9),
// dukungan konversi ke JSON untuk API (Acara 15), dan properti foto (Acara 26).

class Mahasiswa implements JsonSerializable
{
    private int $id;
    private string $nim;
    private string $nama;
    private int $prodiId;
    private string $prodi;
    private string $status;
    private string $alamat;
    private ?string $foto = null;

    public function __construct(
        string $nim,
        string $nama,
        string $prodi,
        string $status = 'aktif',
        int $id = 0,
        int $prodiId = 0,
        string $alamat = '',
        ?string $foto = null
    ) {
        $this->setNim($nim);
        $this->setNama($nama);
        $this->setProdi($prodi);
        $this->setStatus($status);
        $this->id = $id;
        $this->setProdiId($prodiId);
        $this->setAlamat($alamat);
        $this->setFoto($foto);
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

    public function getFoto(): ?string
    {
        return $this->foto;
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

    public function setFoto(?string $foto): void
    {
        $this->foto = $foto;
    }

    // Tugas Mandiri (Acara 4): metode untuk menampilkan angkatan dari NIM.
    public function getAngkatan(): int
    {
        $duaDigit = substr($this->nim, 0, 2);
        return (int)('20' . $duaDigit);
    }

    // Acara 15 & 26: serialisasi objek ke JSON termasuk path foto
    public function jsonSerialize(): array
    {
        $base = defined('BASE_PATH') ? BASE_PATH : '';
        return [
            'id'       => $this->id,
            'nim'      => $this->nim,
            'nama'     => $this->nama,
            'prodi_id' => $this->prodiId,
            'prodi'    => $this->prodi,
            'status'   => $this->status,
            'alamat'   => $this->alamat,
            'foto'     => $this->foto,
            'foto_url' => $this->foto ? ($base . '/uploads/' . $this->foto) : null,
            'angkatan' => $this->getAngkatan(),
        ];
    }
}
