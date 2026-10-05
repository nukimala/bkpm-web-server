<?php

class Mahasiswa
{
    private string $nim;
    private string $nama;
    private string $email;
    private string $prodi;
    private int $angkatan;
    private string $status;

    public function __construct(
        string $nim,
        string $nama,
        string $prodi,
        string $status = 'aktif',
        string $email = '',
        int $angkatan = 0
    ) {
        $this->nim = $nim;
        $this->nama = $nama;
        $this->prodi = $prodi;
        $this->status = $status;
        $this->email = $email;
        $this->angkatan = $angkatan;
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getAngkatan(): int
    {
        if ($this->angkatan > 0) {
            return $this->angkatan;
        }
        return (int)('20' . substr($this->nim, 0, 2));
    }
}