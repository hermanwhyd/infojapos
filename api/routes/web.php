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
});

$router->group(['prefix' => 'v1'], function () use ($router) {
    // Jamaah
    $router->get('jamaah/', 'JamaahController@fetchAll');
    $router->get('jamaah/{id}', 'JamaahController@fetchOne');
    $router->delete('jamaah/{id}', 'JamaahController@delete');

    // Master enumerations
    $router->get('enums/list', 'EnumsController@fetchByListGrup');
    $router->get('enums/list/all', 'EnumsController@fetchAll');
    $router->get('enums/{grup}', 'EnumsController@fetchByGrup');
    $router->get('enums/{id:[0-9]+}', 'EnumsController@fetchById');
    $router->post('enums', 'EnumsController@save');
    $router->put('enums/{id}', 'EnumsController@update');
    $router->delete('enums/{grup:[A-Za-z]+}', 'EnumsController@deleteByGrup');
    $router->delete('enums/{id:[0-9]+}', 'EnumsController@deleteById');

    // Jadwal KBM Kelas
    $router->get('class-schedules/{timestamp}', 'KBMController@fetchJadwalKBMAll');
    $router->get('class-schedules/{scdID:[0-9]+}/students', 'KBMController@fetchSiswaByJadwal');
    $router->get('class-schedules/{scdID:[0-9]+}/presences/{timestamp}', 'KBMController@fetchPresensiByJadwal');
    $router->post('class-presences/{scdID:[0-9]+}', 'KBMController@createNewPresensi');
    $router->put('class-presences/{pscID:[0-9]+}', 'KBMController@updatePresensi');

});
