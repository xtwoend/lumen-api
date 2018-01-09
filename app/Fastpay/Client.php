<?php

namespace App\Fastpay;

/**
* Fastpay XML_RC Client lib
*/
class Client
{
    protected $url;
    protected $uid;
    protected $pin;
    protected $httpclient;
    protected $method;
    protected $params;

    public function __construct()
    {
        $this->url = config('fastpay.url');
        $this->uid = config('fastpay.uid');
        $this->pin = config('fastpay.pin');

        $this->httpclient = new \GuzzleHttp\Client();
    }

    public function init(array $config = [])
    {
        if (! empty($config)) {
            $this->url = $config['url'];
            $this->uid = $config['uid'];
            $this->pin = $config['pin'];
        }
    
        return $this;
    }

    public function method($method)
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
        if(empty($this->method)) {
            return ['error' => 'Unknown Method'];
        }

        $xmlmrc_data = xmlrpc_encode_request($this->method, $this->params);
        
        // $response = $this->httpclient->request('POST', $this->url, [
        //     'headers' => [
        //         'Content-Type' => 'text/xml'
        //     ],
        //     'body' => $xmlmrc_data
        // ]);

        // $code = $response->getStatusCode(); // 200
        // $reason = $response->getReasonPhrase(); // OK
        // $content = $response->getBody()->getContents();

        // if($code !== 200) {
        //     return $content;
        // }

        // return xmlrpc_decode($content);
        return $xmlmrc_data;
    }
}