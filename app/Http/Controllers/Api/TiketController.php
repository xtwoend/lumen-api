<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tiketcom\Client as TiketClient;

class TiketController extends Controller
{
    protected $tiket;

    public function __construct()
    {
        $this->tiket = new TiketClient();
    }

    public function currency(Request $request)
    {
        $res = $this->tiket->method('/general_api/listCurrency')->call();

        dd($res);
    }

    public function searchFlight(Request $request)
    {
        $params = [
            'd' => $request->departure,
            'a' => $request->arrival,
            'date' => $request->flight_date,
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
