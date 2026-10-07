<?php
// routes/web.php
// Route GET, kecuali 'auth' yang hanya menerima POST (proses login).
return [
    ''                 => 'HomeController@index',
    'beranda'          => 'HomeController@index',
    'login'            => 'AuthController@showLogin',
    'auth'             => ['handler' => 'AuthController@login', 'method' => 'POST'],
    'logout'           => 'AuthController@logout',
    'dashboard'        => 'DashboardController@index',
    'mahasiswa'        => 'MahasiswaController@index',
    'mahasiswa/create' => 'MahasiswaController@create',
    'mahasiswa/{id}'   => 'MahasiswaController@show',
    'prodi'            => 'ProdiController@index',
    'matakuliah'       => 'MatakuliahController@index',
];
