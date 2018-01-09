<?php

//api fastpay middleware

$router->group([
    'middleware' => 'api',
    'prefix' => 'api/v1'
], function($router){
    
    $router->get('/biller', function(){
        return 'Oh, no...';
    });

    $router->post('biller/process', 'BillerController');
});
