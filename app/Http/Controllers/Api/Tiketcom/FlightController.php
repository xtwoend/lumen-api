<?php

namespace App\Http\Controllers\Api\Tiketcom;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tiketcom\Client as TiketClient;

class FlightController extends Controller
{

    protected $tiket;

    public function __construct()
    {
        $this->tiket = new TiketClient();
    }
    
    public function search(Request $request)
    {
        #http://api-sandbox.tiket.com/search/flight?d=CGK&a=DPS&date=2014-05-25&ret_date=2014-05-30&adult=1&child=0&infant=0&token=626de6cbccc25cf3f7a652fc933e49187efdbc54&v=3&output=xml

        $params = [
            'd' => $request->departure,
            'a' => $request->arrival,
            'date' => $request->date,
            'adult' => $request->adult,
            'child' => $request->get('child', 0),
            'infant' => $request->get('infant', 0),
            'v' => '3'
        ];

        if ($request->has('return_date')) {
            $params = array_merge($params,  ['ret_date' => $request->return_date]);
        }

        $res = $this->tiket->method('/search/flight')->params($params)->call();

        return response()->json([
            'data' => $res
        ], 200);
    }
}
