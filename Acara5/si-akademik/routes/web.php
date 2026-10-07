<?php
// routes/web.php
// Daftar route dipisah per HTTP method, sesuai pola $routes['GET'/'POST'] (Acara 5).
return [
    'GET' => [
        ''                    => 'HomeController@index',
        'beranda'             => 'HomeController@index',
        'mahasiswa'           => 'MahasiswaController@index',
        'mahasiswa/create'    => 'MahasiswaController@create',
        'mahasiswa/{id}'      => 'MahasiswaController@show',
    ],
    'POST' => [
        'mahasiswa'           => 'MahasiswaController@store',
    ],
];
