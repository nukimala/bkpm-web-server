<?php
require_once __DIR__ . '/../Core/BaseModel.php';


class ProdiModel extends BaseModel
{
    protected string $table = 'prodi';

    public function fields(): array
    {
        return ['kode', 'nama'];
    }

    public function validate(array $data): array
    {
        $errors = [];

        if ($data['kode'] === '') {
            $errors[] = 'Kode prodi wajib diisi.';
        } elseif (!preg_match('/^[A-Z0-9]{2,10}$/', $data['kode'])) {
            $errors[] = 'Kode prodi harus 2-10 karakter huruf kapital/angka.';
        }

        if ($data['nama'] === '') {
            $errors[] = 'Nama prodi wajib diisi.';
        }

        return $errors;
    }
}