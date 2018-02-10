<?php

namespace App\Fastpay\Response;

use App\Fastpay\ResponseInterface;


/**
* 
*/
class BpjsPayment implements ResponseInterface
{
    
    public $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getResponse()
    {
        return (array) $this->format($this->data);
    }

    public function format(array $data = [])
    {
        return [
            'status' => $this->data[8],
            'transaction_date' => $this->data[1],
            'phone_number' => $this->data[2],
            'serial_number' => $this->data[5],
            'product_code' => $this->data[0],
            'description' => $this->data[9]
        ];
    }
}
