<?php

return [
    // Autentikasi
    ''                       => 'HomeController@index',
    'beranda'                => 'HomeController@index',
    'login'                  => 'AuthController@showLogin',
    'auth'                   => ['handler' => 'AuthController@login', 'method' => 'POST'],
    'logout'                 => 'AuthController@logout',
    'dashboard'              => 'DashboardController@index',

    // Mahasiswa
    'mahasiswa'              => 'MahasiswaController@index',
    'mahasiswa/create'       => 'MahasiswaController@create',
    'mahasiswa/store'        => ['handler' => 'MahasiswaController@store', 'method' => 'POST'],
    'mahasiswa/edit/{id}'    => 'MahasiswaController@edit',
    'mahasiswa/update/{id}'  => ['handler' => 'MahasiswaController@update', 'method' => 'POST'],
    'mahasiswa/delete/{id}'  => ['handler' => 'MahasiswaController@delete', 'method' => 'POST'],
    'mahasiswa/{nim}'        => 'MahasiswaController@show',

    // Prodi
    'prodi'                  => 'ProdiController@index',
    'prodi/create'           => 'ProdiController@create',
    'prodi/store'            => ['handler' => 'ProdiController@store', 'method' => 'POST'],
    'prodi/edit/{id}'        => 'ProdiController@edit',
    'prodi/update/{id}'      => ['handler' => 'ProdiController@update', 'method' => 'POST'],
    'prodi/delete/{id}'      => ['handler' => 'ProdiController@delete', 'method' => 'POST'],

    // Matakuliah
    'matakuliah'             => 'MatakuliahController@index',
    'matakuliah/create'      => 'MatakuliahController@create',
    'matakuliah/store'       => ['handler' => 'MatakuliahController@store', 'method' => 'POST'],
    'matakuliah/edit/{id}'   => 'MatakuliahController@edit',
    'matakuliah/update/{id}' => ['handler' => 'MatakuliahController@update', 'method' => 'POST'],
    'matakuliah/delete/{id}' => ['handler' => 'MatakuliahController@delete', 'method' => 'POST'],
];