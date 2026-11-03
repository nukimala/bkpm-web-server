<?php
// routes/web.php
// Daftar route aplikasi SI Akademik (CRUD lengkap).
// Perhatikan: route spesifik harus ditulis sebelum route yang lebih umum.
return [
    // Autentikasi
    ''                       => 'HomeController@index',
    'beranda'                => 'HomeController@index',
    'login'                  => 'AuthController@showLogin',
    'auth'                   => 'AuthController@login',
    'logout'                 => 'AuthController@logout',
    'dashboard'              => 'DashboardController@index',

    // Mahasiswa
    'mahasiswa'              => 'MahasiswaController@index',
    'mahasiswa/create'       => 'MahasiswaController@create',
    'mahasiswa/store'        => 'MahasiswaController@store',
    'mahasiswa/edit/{id}'    => 'MahasiswaController@edit',
    'mahasiswa/update/{id}'  => 'MahasiswaController@update',
    'mahasiswa/delete/{id}'  => 'MahasiswaController@delete',
    'mahasiswa/{nim}'        => 'MahasiswaController@show',

    // Prodi
    'prodi'                  => 'ProdiController@index',
    'prodi/create'           => 'ProdiController@create',
    'prodi/store'            => 'ProdiController@store',
    'prodi/edit/{id}'        => 'ProdiController@edit',
    'prodi/update/{id}'      => 'ProdiController@update',
    'prodi/delete/{id}'      => 'ProdiController@delete',

    // Matakuliah
    'matakuliah'             => 'MatakuliahController@index',
    'matakuliah/create'      => 'MatakuliahController@create',
    'matakuliah/store'       => 'MatakuliahController@store',
    'matakuliah/edit/{id}'   => 'MatakuliahController@edit',
    'matakuliah/update/{id}' => 'MatakuliahController@update',
    'matakuliah/delete/{id}' => 'MatakuliahController@delete',
];