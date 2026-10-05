<?php
require_once __DIR__ . '/../Core/BaseModel.php';

class MatakuliahModel extends BaseModel
{
    protected string $table = 'matakuliah';

    public function fields(): array
    {
        return ['kode', 'nama', 'sks', 'prodi_id'];
    }

    public function validate(array $data): array
    {
        $errors = [];

        if ($data['kode'] === '') {
            $errors[] = 'Kode matakuliah wajib diisi.';
        } elseif (!preg_match('/^[A-Z0-9]{2,10}$/', $data['kode'])) {
            $errors[] = 'Kode matakuliah harus 2-10 karakter huruf kapital/angka.';
        }

        if ($data['nama'] === '') {
            $errors[] = 'Nama matakuliah wajib diisi.';
        }

        $sks = (int) $data['sks'];
        if ($sks < 1 || $sks > 6) {
            $errors[] = 'SKS harus antara 1 sampai 6.';
        }

        if ((int) $data['prodi_id'] <= 0) {
            $errors[] = 'Prodi wajib dipilih.';
        }

        return $errors;
    }
}