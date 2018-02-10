<?php

//api fastpay middleware

$router->group([
    // 'middleware' => 'auth:api',
    'prefix' => 'api/v1'
], function($router){
    
    $router->get('/biller', function(){
        return 'Oh, no...';
    });

    $router->get('/private', function(){
        return Auth::user();
    });

    $router->post('biller/process', 'BillerController');

    // $router->get('currency', 'TiketController@currency');
    // $router->post('flight/search', 'TiketController@searchFlight');

    // tiket.com api train
    $router->group([
        'prefix' => 'train',
        'namespace' => 'Tiketcom'
    ], function() use ($router) {
        $router->get('/search', 'TrainController@search');
        $router->get('/station', 'TrainController@station');
        $router->get('/seatmap', 'TrainController@seatMap');
        $router->post('/order', 'TrainController@order');
    });
    // tiket.com api flight
    $router->group([
        'prefix' => 'flight',
        'namespace' => 'Tiketcom'
    ], function() use ($router) {
        $router->post('/search', 'FlightController@search');
    });

    // tiket.com api Event
    $router->group([
        'prefix' => 'event',
        'namespace' => 'Tiketcom'
    ], function() use ($router) {
        $router->post('/search', 'EventController@search');
    });

    // tiket.com api Event
    $router->group([
        'prefix' => 'hotel',
        'namespace' => 'Tiketcom'
    ], function() use ($router) {
        $router->post('/search', 'HotelController@search');
    });
});

