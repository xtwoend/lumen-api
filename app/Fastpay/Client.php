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
    protected $refs;

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

    public function refs(array $refs = [])
    {
        $this->refs = $refs;
        return $this;
    }

    public function call()
    {
        if(empty($this->method)) {
            return ['error' => 'Unknown Method'];
        }

        $params = array_merge($this->params, [$this->uid, $this->pin], $this->refs);
        $xmlmrc_data = xmlrpc_encode_request($this->method, $params);
        
        $response = $this->httpclient->request('POST', $this->url, [
            'headers' => [
                'Content-Type' => 'text/xml'
            ],
            'body' => $xmlmrc_data
        ]);
        $code = $response->getStatusCode(); // 200
        $reason = $response->getReasonPhrase(); // OK
        $content = $response->getBody()->getContents(); // repsonse server
        if($code !== 200) {
            return $content;
        }

        return xmlrpc_decode($content);
    }
}