<?php
require_once __DIR__ . '/../Core/Controller.php';

class MatakuliahController extends Controller
{
    private array $matakuliah = [
        ['kode' => 'TI201', 'nama' => 'Algoritma dan Struktur Data', 'sks' => 4],
        ['kode' => 'TI202', 'nama' => 'Basis Data', 'sks' => 3],
        ['kode' => 'SI201', 'nama' => 'Analisis dan Perancangan Sistem', 'sks' => 3],
    ];

    public function index(): void
    {
        $this->render('matakuliah/index.php', ['matakuliah' => $this->matakuliah]);
    }
}