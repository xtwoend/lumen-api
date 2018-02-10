<?php

use Illuminate\Http\Request;

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
    return 'Service for biller nusapay';
});

$router->get('/balance', 'Api\Tiketcom\TrainController@saldo');
// $router->get('callback', function (Request $request) use ($router) {
//     if (! $request->has('token')) return redirect()->to('/');
//     $token = $request->token;

//     $client = new GuzzleHttp\Client();
//     $res = $client->request('POST', 'http://account.mdirect.loc/api/v1/token_request', [
//         'headers' => [
//             'Content-Type'  => 'application/json',
//             'appid'         => 'pasarkita.id',
//             'appsecret'     => 'xz6aOA4PaQMMoSz5M6YSMVLZidEl4guO'
//         ],
//         'json'       => [
//             'token' => $token
//         ]
//     ]);
//     $content = json_decode($res->getBody()->getContents());
//     $token = $content->data->token;

//     setcookie('APPTK', $token, null, "/"); // 86400 = 1 day
    
//     return redirect()->to('/');
// });

$router->get('callback', function (Request $request) use ($router) {

    $http = new GuzzleHttp\Client;

    $response = $http->post('http://accounts.pasarkita.loc/oauth/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => '9009',
            'client_secret' => 'GZRinqlKVKFta0QNId689QvgqLrC2witRmnSgZV5',
            'redirect_uri' => 'http://nusapay.loc/callback',
            'code' => $request->code,
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});


$router->get('/login', function(){

    $http = new GuzzleHttp\Client;

    $response = $http->post('http://nusauser.loc/oauth/token', [
        'form_params' => [
            'grant_type' => 'password',
            'client_id' => 'client-id',
            'client_secret' => 'client-secret',
            'username' => 'taylor@laravel.com',
            'password' => 'my-password',
            'scope' => 'starter',
        ],
    ]);

    return json_decode((string) $response->getBody(), true);
});

$router->get('tool', function () {
    // $array1 = ['param1', 'param2', 'param3', 'param4'];
    // $array2 = ['uid', 'pin', 'ref'];
    $xml = '<?xml version="1.0"?>
                                                <methodResponse>
                                                <params>
                                                <param>
                                                <value>
                                                <array>
                                                <data>
                                                <value><string>KODEPRODUK</string></value>
                                                <value><string>WAKTU(YYYYMMDDHHIISS)</string></value>
                                                <value><string>IDPELANGGAN1</string></value>
                                                <value><string>IDPELANGGAN2</string></value>
                                                <value><string>IDPELANGGAN3</string></value>
                                                <value><string>NAMAPELANGGAN</string></value>
                                                <value><string>PERIODETAGIHAN</string></value>
                                                <value><string>NOMINAL</string></value>
                                                <value><string>BIAYAADMIN</string></value>
                                                <value><string>UID</string></value>
                                                <value><string>PIN</string></value>
                                                <value><string>REF1</string></value>
                                                <value><string>REF2</string></value>
                                                <value><string>REF3</string></value>
                                                <value><string>STATUS</string></value>
                                                <value><string>KETERANGAN</string></value>
                                                <value><string>SALDOTERPOTONG</string></value>
                                                <value><string>SISASALDO</string></value>
                                                <value><string>URLSTRUK</string></value>
                                                <value>
                                                <struct>
                                                <member>
                                                <name>CATATAN</name>
                                                <value><string>CATATANVALUE</string></value>
                                                </member>                                               
                                                <member>
                                                <name>SUBSCRIBERSEGMENTATION</name>
                                                <value><string>SUBSCRIBERSEGMENTATIONVALUE</string></value>
                                                </member>                                               
                                                <member>
                                                <name>POWERCONSUMINGCATEGORY</name>
                                                <value><string>POWERCONSUMINGCATEGORYVALUE</string></value>
                                                </member>                                               
                                                <member>
                                                <name>SLALWBP1</name>
                                                <value><string>SLALWBP1VALUE</string></value>
                                                </member>                                               
                                                <member>
                                                <name>SAHLWBP1</name>
                                                <value><string>SAHLWBP1VALUE</string></value>
                                                </member>                                               
                                                <member>
                                                <name>SAHLWBP2</name>
                                                <value><string>SAHLWBP2VALUE</string></value>
                                                </member>                                               
                                                <member>
                                                <name>SAHLWBP3</name>
                                                <value><string>SAHLWBP3VALUE</string></value>
                                                </member>                                   
                                                <member>
                                                <name>SAHLWBP4</name>
                                                <value><string>SAHLWBP4VALUE</string></value>
                                                </member>                                               
                                                </struct>
                                                </value>                                                
                                                </data>
                                                </array>
                                                </value>
                                                </param>
                                                </params>
                                                </methodResponse>
        
            ';
    // $array = array_merge($array1, $array2);
    $array = xmlrpc_decode($xml);
    return response()->json($array, 200);
});
