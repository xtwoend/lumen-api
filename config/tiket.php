<?php

return [
    'api_secret' => env('TIKET_SECRET', ''),
    'api_url' => 'https://api-sandbox.tiket.com',
    'headers' => [
        'User-Agent' => 'twh:27211917;PT. Nusapay Solusi Indonesia',
        'Accept'     => 'application/json'
    ]
];