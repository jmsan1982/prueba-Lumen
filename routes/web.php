<?php

/** @var \Laravel\Lumen\Routing\Router $router */
use App\Http\Controllers\PreguntasController;

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

$router->group(['prefix'=>'api','json.response'], function() use($router){
    $router->post('/corregirTest','PreguntasController@corregirTest');
});

$router->get('/listadoTests/{oposicionId}/{tipoTestId}/{bloqueId}', 'PreguntasController@getTests');
$router->get('/listadoTestsEstados/{oposicionId}/{tipoTestId}/{bloqueId}/{estado}', 'PreguntasController@getTestWithEstados');

$router->get('/enviar-json', function () {
    return view('json');
});



