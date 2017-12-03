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

$router->group(['prefix' => 'v1'], function () use ($router) {
    // Jamaah
    $router->get('jamaah/', 'JamaahController@fetchAll');
    $router->get('jamaah/{id}', 'JamaahController@fetchOne');
    $router->delete('jamaah/{id}', 'JamaahController@delete');

    // Master Pilihan
    $router->get('pilihan/', 'EnumsController@fetchAll');
    $router->get('pilihan/{grupStrList}', 'EnumsController@fetchByListGrup');
    $router->get('pilihan/grup/{grup}', 'EnumsController@fetchByGrup');
    $router->get('pilihan/id/{id}', 'EnumsController@fetchById');
    $router->post('pilihan/{id}', 'EnumsController@update');
    $router->post('pilihan/', 'EnumsController@save');
    $router->delete('pilihan/{id}', 'EnumsController@delete');
});
