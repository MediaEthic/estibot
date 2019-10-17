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

//        $router->group(['middleware' => 'auth'], function ($router) {
            $router->group(['prefix' => 'quotations'], function ($router) {
                $router->get('/', 'QuotationController@index');
                $router->post('/', 'QuotationController@store');
    //            $router->get('/{id}', 'QuotationController@show');
                $router->post('/price', 'QuotationController@getPrice');
                $router->get('/{id}/edit', 'QuotationController@edit');
                $router->post('/{id}', 'QuotationController@update');
                $router->delete('/{id}', 'QuotationController@destroy');

                $router->get('/{id}/email', 'QuotationController@sendEmail');


                $router->get('/printings', 'QuotationController@getPrintings');
                $router->get('/finishings', 'QuotationController@getFinishings');
    //            $router->get('/substrates', 'QuotationController@getSubstrates');
    //            $router->get('/consumables', 'QuotationController@getConsumables');
    //            $router->get('/cuttings', 'QuotationController@getCuttings');
            });
            $router->post('/logout', 'AuthController@logout');
//        });
        $router->get('/quotations/{id}/pdf', 'QuotationController@generatePDF');
    });
});


$router->get('/{route:.*}/', function ()  {
    return view('app');
});
