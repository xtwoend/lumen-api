<?php

namespace App\Http\Controllers\Api;

use App\Fastpay\Client as FastpayClient;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillerController extends Controller
{
    const FASTPAY_METHOD = [
        'PULSA' => 'fastpay.pulsa',
        'GAME'  => 'fastpay.game',
        'PAYMENT' => 'fastpay.pay',
        'INQUIRY' => 'fastpay.inq'
    ];

    protected $fastpayclient;

    public function __construct()
    {
        $this->fastpayclient = new FastpayClient;
    }

    public function __invoke(Request $request)
    {
        $fastpayclient = $this->fastpayclient;

        switch ($request->process_code) {
            case 'PULSA':
                    $fastpayclient = $fastpayclient
                        ->method(self::FASTPAY_METHOD[$request->process_code])
                        ->params([
                            'productcode' => $request->product_code,
                            'client_phone' => $request->phone_number,
                            'unknown' => '',
                            'uid' => config('fastpay.uid'),
                            'pin' => config('fastpay.pin'),
                            'transaction_id' => random_int(11314,123123123)
                        ]);
                break;
            
            default:
                # code...
                break;
        }

        $params = [];

        $data = $fastpayclient->call();
        return $data;
    }
}
