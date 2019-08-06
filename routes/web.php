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


$router->group(['prefix' => 'api'], function ($router) {
    $router->group(['prefix' => 'auth'], function ($router) {
        $router->post('/login', 'AuthController@login');

        $router->get('/quotations', 'QuotationController@index');
        $router->group(['middleware' => 'jwt.auth'], function ($router) {
            $router->post('/logout', 'AuthController@logout');
        });
    });
});


$router->get('/{route:.*}/', function ()  {
    return view('app');
});
