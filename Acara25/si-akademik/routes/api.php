<?php
// routes/api.php
// Daftar endpoint API (Acara 16, ditambah autentikasi token Acara 19).
// Format kunci: 'METODE /api/path' => 'Controller@method'.
// Endpoint non-GET (kecuali /api/auth/login) dipasangi ApiAuthMiddleware.
return [
    'GET    /api'                   => 'ApiController@info',
    'POST   /api/auth/login'        => 'Api\AuthApiController@login',
    'POST   /api/auth/logout'       => 'Api\AuthApiController@logout',
    'GET    /api/mahasiswa'         => 'Api\MahasiswaApiController@index',
    'GET    /api/mahasiswa/{id}'    => 'Api\MahasiswaApiController@show',
    'POST   /api/mahasiswa'         => 'Api\MahasiswaApiController@store',
    'PUT    /api/mahasiswa/{id}'    => 'Api\MahasiswaApiController@update',
    'DELETE /api/mahasiswa/{id}'    => 'Api\MahasiswaApiController@delete',
    'GET    /api/prodi'             => 'Api\ProdiApiController@index',
    'GET    /api/prodi/{id}'        => 'Api\ProdiApiController@show',
    'POST   /api/prodi'             => 'Api\ProdiApiController@store',
    'PUT    /api/prodi/{id}'        => 'Api\ProdiApiController@update',
    'DELETE /api/prodi/{id}'        => 'Api\ProdiApiController@delete',
    'GET    /api/matakuliah'        => 'Api\MatakuliahApiController@index',
    'GET    /api/matakuliah/{id}'   => 'Api\MatakuliahApiController@show',
    'POST   /api/matakuliah'        => 'Api\MatakuliahApiController@store',
    'PUT    /api/matakuliah/{id}'   => 'Api\MatakuliahApiController@update',
    'DELETE /api/matakuliah/{id}'   => 'Api\MatakuliahApiController@delete',
];