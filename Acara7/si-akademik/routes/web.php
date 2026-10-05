<?php
return [
    ''                 => 'HomeController@index',
    'beranda'          => 'HomeController@index',
    'login'            => 'AuthController@showLogin',
    'auth'             => 'AuthController@login',
    'logout'           => 'AuthController@logout',
    'dashboard'        => 'DashboardController@index',
    'mahasiswa'        => 'MahasiswaController@index',
    'mahasiswa/create' => 'MahasiswaController@create',
    'mahasiswa/{id}'   => 'MahasiswaController@show',
    'prodi'            => 'ProdiController@index',
    'matakuliah'       => 'MatakuliahController@index',
];