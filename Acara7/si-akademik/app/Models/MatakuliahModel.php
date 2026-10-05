<?php

require_once __DIR__ . '/Model.php';

class MatakuliahModel extends Model
{
    public static function all(): array
    {
        $sql = 'SELECT m.kode, m.nama, m.sks, p.nama AS prodi
                FROM matakuliah m
                JOIN prodi p ON p.id = m.prodi_id
                ORDER BY m.kode';

        return self::connect()->query($sql)->fetchAll();
    }
}