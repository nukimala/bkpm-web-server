<?php
// routes/api.php
// Daftar endpoint API Tahap 1 (Acara 15): konsep API & JSON, hanya GET / read-only.
// Bentuk route: '<path>' => 'Controller@method'.
return [
    'api'                  => 'ApiController@info',
    'api/mahasiswa'        => 'ApiController@mahasiswa',
    'api/mahasiswa/{nim}'  => 'ApiController@mahasiswaDetail',
    'api/prodi'            => 'ApiController@prodi',
    'api/matakuliah'       => 'ApiController@matakuliah',
];