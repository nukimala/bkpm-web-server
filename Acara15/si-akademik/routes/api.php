<?php
// routes/api.php
// Daftar endpoint API Acara 15: konsep API & JSON (GET & POST).
// Bentuk route: '<METHOD> <path>' => 'Controller@method'.
return [
    'GET api'                 => 'ApiController@info',
    'GET api/mahasiswa'       => 'ApiController@mahasiswa',
    'GET api/mahasiswa/{nim}' => 'ApiController@mahasiswaDetail',
    'POST api/mahasiswa'      => 'ApiController@mahasiswaStore',
    'GET api/prodi'           => 'ApiController@prodi',
    'GET api/matakuliah'      => 'ApiController@matakuliah',
];