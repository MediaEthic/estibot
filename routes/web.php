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
    $router->get('/quote', 'QuotationController@getQuote');

    $router->group(['prefix' => 'auth'], function ($router) {
        $router->post('/login', 'AuthController@login');
        $router->get('/quotations', 'QuotationController@index');

        $router->group(['prefix' => 'quotation'], function ($router) {
            $router->get('/printings', 'QuotationController@getPrintings');
//            $router->get('/substrates', 'QuotationController@getSubstrates');
            $router->get('/finishings', 'QuotationController@getFinishings');
//            $router->get('/consumables', 'QuotationController@getConsumables');
//            $router->get('/cuttings', 'QuotationController@getCuttings');
            $router->post('/price', 'QuotationController@getPrice');
            $router->post('/', 'QuotationController@store');
            $router->get('/{id}/edit', 'QuotationController@edit');
            $router->post('/{id}/edit', 'QuotationController@update');
            $router->delete('/{id}', 'QuotationController@destroy');
        });

        $router->group(['middleware' => 'jwt.auth'], function ($router) {
            $router->post('/logout', 'AuthController@logout');
        });
    });
});


$router->get('/{route:.*}/', function ()  {
    return view('app');
});
