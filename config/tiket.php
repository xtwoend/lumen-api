<?php

return [
    'api_secret' => env('TIKET_SECRET', ''),
    'confirm_key' => env('CONFIRM_KEY', ''),
    'user_id' => 'abdul@nusi.co.id',
    'api_url' => 'https://api-sandbox.tiket.com',
    'headers' => [
        'User-Agent' => 'twh:27211917;PT. Nusapay Solusi Indonesia',
        'Accept'     => 'application/json'
    ]
];