<?php

require_once __DIR__ . '/Model.php';
require_once __DIR__ . '/Mahasiswa.php';

class MahasiswaModel extends Model
{
    private const SELECT = 'm.id, m.nim, m.nama, m.email, m.prodi_id, m.angkatan, m.status,
                           p.nama AS prodi';

    public static function all(): array
    {
        $sql = 'SELECT ' . self::SELECT . '
                FROM mahasiswa m
                JOIN prodi p ON p.id = m.prodi_id
                ORDER BY m.nim';

        $rows = self::connect()->query($sql)->fetchAll();

        return array_map([self::class, 'toEntity'], $rows);
    }

    public static function find(int $id): ?Mahasiswa
    {
        $sql = 'SELECT ' . self::SELECT . '
                FROM mahasiswa m
                JOIN prodi p ON p.id = m.prodi_id
                WHERE m.id = :id';

        $stmt = self::connect()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ? self::toEntity($row) : null;
    }

    public static function findByNim(string $nim): ?Mahasiswa
    {
        $sql = 'SELECT ' . self::SELECT . '
                FROM mahasiswa m
                JOIN prodi p ON p.id = m.prodi_id
                WHERE m.nim = :nim';

        $stmt = self::connect()->prepare($sql);
        $stmt->execute(['nim' => $nim]);
        $row = $stmt->fetch();

        return $row ? self::toEntity($row) : null;
    }

    private static function toEntity(array $row): Mahasiswa
    {
        return new Mahasiswa(
            $row['nim'],
            $row['nama'],
            $row['prodi'],
            $row['status'],
            $row['email'],
            (int) $row['angkatan']
        );
    }
}