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

    public function __construct()
    {
        $this->url = config('fastpay.url');
        $this->uid = config('fastpay.uid');
        $this->pin = config('fastpay.pin');
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

    public function call(Request $request)
    {
        
    }
}