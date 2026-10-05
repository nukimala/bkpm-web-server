<?php
// ini adalah objek untuk dikirimke view
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../app/Models/Mahasiswa.php';

$mahasiswa = [
    new Mahasiswa("2401001", "Budi Santoso", "Teknik Informatika"),
    new Mahasiswa("2401002", "Ani Wijaya", "Teknik Informatika"),
    new Mahasiswa("2402001", "Citra Lestari", "Sistem Informasi"),
    new Mahasiswa("2501003", "Dedi Kurniawan", "Teknik Informatika"),
];

$content = __DIR__ . '/../app/Views/mahasiswa/index.php';
require __DIR__ . '/../app/Views/layouts/main.php';