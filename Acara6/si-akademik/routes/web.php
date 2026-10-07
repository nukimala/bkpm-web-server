<?php

return [
    ''                 => 'HomeController@index',
    'beranda'          => 'HomeController@index',
    'login'            => 'AuthController@loginForm',
    'auth'             => ['handler' => 'AuthController@login', 'method' => 'POST'],
    'logout'           => 'AuthController@logout',

    'dashboard'        => ['handler' => 'DashboardController@index', 'middleware' => ['AuthMiddleware']],
    'mahasiswa'        => ['handler' => 'MahasiswaController@index', 'middleware' => ['AuthMiddleware']],
    'mahasiswa/create' => ['handler' => 'MahasiswaController@create', 'middleware' => ['AuthMiddleware']],
    'mahasiswa/{id}'   => ['handler' => 'MahasiswaController@show', 'middleware' => ['AuthMiddleware']],
    'prodi'            => ['handler' => 'ProdiController@index', 'middleware' => ['AuthMiddleware']],
    'matakuliah'       => ['handler' => 'MatakuliahController@index', 'middleware' => ['AuthMiddleware']],
];