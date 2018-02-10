<?php

namespace App\Tiketcom;

use Illuminate\Support\Facades\Cache;

/**
* 
*/
class Client
{   
    protected $method;
    protected $params = [];
    protected $headers;
    protected $api_secret;
    protected $confirm_key;
    protected $user_id;
    protected $httpClient;
    
    public function __construct()
    {
        $this->api_secret = config('tiket.api_secret');
        $this->confirm_key = config('tiket.confirm_key');
        $this->user_id = config('tiket.user_id');
        $this->httpClient = new \GuzzleHttp\Client([
            'base_uri' => config('tiket.api_url'),
            'timeout'  => 5.0,
        ]);
    }

    public function method($method = '')
    {
        $this->method = $method;
        return $this;
    }

    public function params(array $params = [])
    {
        $this->params = $params;
        return $this;
    }

    public function call()
    {   
        $token = Cache::remember('tiketcomtoken', 120, function () {
            return $this->getToken();
        });

        $response = $this->httpClient->request('GET', $this->method, [
            'headers' => $this->headers,
            'query' => array_merge($this->params, ['token' => $token, 'output' => 'json'])
            ]
        );

        $code = $response->getStatusCode(); // 200
        $body = $response->getBody()->getContents();

        if ($code !== 200){
            throw new \Exception("Error Processing Request", 1);
        }

        return json_decode($body, TRUE);
    }

    public function getToken()
    {
        $response = $this->httpClient->request('GET', '/apiv1/payexpress', [
            'headers' => $this->headers,
            'query' => [
                'method' => 'getToken',
                'secretkey' => $this->api_secret,
                'output' => 'json'
            ]
        ]);
        $code = $response->getStatusCode(); // 200
        $body = $response->getBody()->getContents();

        if ($code !== 200){
            throw new \Exception("Error Processing Request", 1);
        }

        $data = json_decode($body);
        
        return $data->token;
    }

    public function getBalance()
    {
        #http://api-sandbox.tiket.com/partner/transactionApi/get_saldo?secretkey=[SECRET_KEY]&confirmkey=[CONFIRM_KEY]&username=[USERNAME]

        $response = $this->httpClient->request('GET', '/partner/transactionApi/get_saldo', [
            'headers' => $this->headers,
            'query' => [
                'confirmkey' => $this->confirm_key,
                'secretkey' => $this->api_secret,
                'username' => $this->user_id,
                'output' => 'json'
            ]
        ]);
        $code = $response->getStatusCode(); // 200
        $body = $response->getBody()->getContents();

        if ($code !== 200){
            throw new \Exception("Error Processing Request", 1);
        }

        $data = json_decode($body);
        
        return $data->results;
    }

    public function setHeaders(array $headers = [])
    {
        $this->headers = array_merge($headers, config('tiket.headers'));
        return $this;
    }
}