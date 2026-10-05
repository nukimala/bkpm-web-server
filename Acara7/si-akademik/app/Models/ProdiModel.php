<?php

require_once __DIR__ . '/Model.php';

class ProdiModel extends Model
{
    public static function all(): array
    {
        $sql = 'SELECT id, kode, nama FROM prodi ORDER BY id';

        return self::connect()->query($sql)->fetchAll();
    }
}