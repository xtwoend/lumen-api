<?php

namespace App\Tiketcom;

/**
* 
*/
class Client
{   
    protected $method;
    protected $params = [];
    protected $headers;
    protected $api_secret;
    protected $httpclient;
    
    public function __construct()
    {
        $this->api_secret = config('tiket.api_secret');
        $this->httpclient = new \GuzzleHttp\Client([
            'base_uri' => config('tiket.api_url'),
            // 'timeout'  => 2.0,
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
        $response = $this->httpclient->request('GET', $this->method, [
            'headers' => $this->headers,
            'query' => array_merge($this->params, ['token' => $this->getToken(), 'output' => 'json'])
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
        $response = $this->httpclient->request('GET', '/apiv1/payexpress', [
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

    public function setHeaders(array $headers = [])
    {
        $this->headers = array_merge($headers, config('tiket.headers'));
        return $this;
    }
}