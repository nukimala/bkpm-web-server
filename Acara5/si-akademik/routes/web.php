<?php
// routes/web.php
// Daftar route aplikasi SI Akademik.
return [
    ''                    => 'HomeController@index',
    'beranda'             => 'HomeController@index',
    'mahasiswa'           => 'MahasiswaController@index',
    'mahasiswa/create'    => 'MahasiswaController@create',
    'mahasiswa/{id}'      => 'MahasiswaController@show',
];