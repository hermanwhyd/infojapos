<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'v0'], function () use ($router) {
    $router->get('test', 'ExampleController@testConnection');
    $router->get('sql-time', 'ExampleController@getSQLTime');
    $router->get('php-time', 'ExampleController@getPHPTime');
});

$router->group(['prefix' => 'v1'], function () use ($router) {
    // check version
    $router->get('/version', function() {
        return array (
            'version_name'=>'1.0',
            'version_code'=>1,
            'prev_version_action'=>'reminder', // reminder, update
            'min_version_allowed'=>1,
            'download_url'=>"http://download.japos.online",
            'changes_log'=>array(
                array('version'=>'1.0', 'remark'=>'Pengembangan awal Aplikasi Infojapos')
            )
        );
    });

    // login
    $router->get('/logout', ['middleware' => 'auth', 'uses' => 'LoginController@logout']);
    $router->post('/login', 'LoginController@login');
    $router->post('/register', 'UserController@register');
    $router->get('/user/{id}', ['middleware' => 'auth', 'uses' => 'UserController@get_user']);

    // Jamaah
    $router->get('jamaah/', 'JamaahController@fetchAll');
    $router->get('jamaah/{id}', 'JamaahController@fetchOne');
    $router->delete('jamaah/{id}', 'JamaahController@delete');

    // Master enumerations
    $router->get('pilihan/{id:[0-9]+}', 'EnumsController@fetchById');
    $router->get('pilihan/{grup}', 'EnumsController@fetchByGrup');
    $router->get('pilihan', 'EnumsController@fetchAll');
    $router->post('pilihan', 'EnumsController@save');
    $router->put('pilihan/{id}', 'EnumsController@update');
    $router->delete('pilihan/{grup:[A-Za-z]+}', 'EnumsController@deleteByGrup');
    $router->delete('pilihan/{id:[0-9]+}', 'EnumsController@deleteById');

    // Jadwal KBM Kelas
    $router->get('class-schedules/{timestamp}', 'KBMController@fetchJadwalKBMAll');
    $router->get('class-schedules/{scdID:[0-9]+}/students', 'KBMController@fetchSiswaByJadwal');
    $router->get('class-schedules/{scdID:[0-9]+}/presences/{timestamp}', 'KBMController@fetchPresensiByJadwal');
    $router->post('class-presences/{scdID:[0-9]+}', ['middleware' => 'auth', 'uses' => 'KBMController@createNewPresensi']);
    $router->put('class-presences/{pscID:[0-9]+}', ['middleware' => 'auth', 'uses' => 'KBMController@updatePresensi']);

    // Kelas
    $router->get('class/{timestamp}', 'KelasController@getKelasAktif');

    // Statistik
    $router->get('statistik/kelas/{id:[0-9]+}/{timestamp1}/{timestamp2}', 'StatistikController@statistikKelasPerPeriode');
    $router->get('statistik/kelas/{id:[0-9]+}/{timestamp1}/{timestamp2}/peserta', 'StatistikController@statistikPesertaPerPeriode');
    $router->get('statistik/kelas/{id:[0-9]+}/{timestamp1}/{timestamp2}/peserta/new', 'StatistikController@statistikPesertaPerPeriode2');
    
});
