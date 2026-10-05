<?php
require_once __DIR__ . '/../Core/BaseModel.php';

class MahasiswaModel extends BaseModel
{
    protected string $table = 'mahasiswa';

    public function fields(): array
    {
        return ['nim', 'nama', 'email', 'prodi_id', 'angkatan', 'status'];
    }

    public function statuses(): array
    {
        return ['aktif', 'cuti', 'lulus'];
    }


    public function validate(array $data): array
    {
        $errors = [];

        if ($data['nim'] === '') {
            $errors[] = 'NIM wajib diisi.';
        } elseif (!ctype_digit((string) $data['nim'])) {
            $errors[] = 'NIM harus berupa angka.';
        }

        if (trim((string) $data['nama']) === '') {
            $errors[] = 'Nama wajib diisi.';
        }

        if ($data['email'] === '') {
            $errors[] = 'Email wajib diisi.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid.';
        }

        if ((int) $data['prodi_id'] <= 0) {
            $errors[] = 'Prodi wajib dipilih.';
        }

        $angkatan = (int) $data['angkatan'];
        if ($angkatan <= 0) {
            $errors[] = 'Angkatan wajib diisi.';
        } elseif ($angkatan > (int) date('Y') + 1) {
            $errors[] = 'Angkatan tidak valid.';
        }

        if (!in_array($data['status'], $this->statuses(), true)) {
            $errors[] = 'Status harus salah satu dari: ' . implode(', ', $this->statuses()) . '.';
        }

        return $errors;
    }
}