<?php
require_once __DIR__ . '/../Core/Controller.php';

class ProdiController extends Controller
{
    private array $prodi = [
        ['kode' => 'TI', 'nama' => 'Teknik Informatika', 'jenjang' => 'D3'],
        ['kode' => 'SI', 'nama' => 'Sistem Informasi', 'jenjang' => 'D3'],
        ['kode' => 'TK', 'nama' => 'Teknik Komputer', 'jenjang' => 'D3'],
    ];

    public function index(): void
    {
        $this->render('prodi/index.php', ['prodi' => $this->prodi]);
    }
}