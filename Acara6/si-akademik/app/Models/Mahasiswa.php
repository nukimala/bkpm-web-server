<?php

require_once __DIR__ . '/../Core/Model.php';

class Mahasiswa extends Model
{
    private string $nim;
    private string $nama;
    private string $prodi;
    private string $status;

    public function __construct(string $nim, string $nama, string $prodi, string $status = 'aktif')
    {
        $this->nim = $nim;
        $this->nama = $nama;
        $this->prodi = $prodi;
        $this->status = $status;
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getAngkatan(): int
    {
        $duaDigit = substr($this->nim, 0, 2);
        return (int)('20' . $duaDigit);
    }
}