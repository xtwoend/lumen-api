<?php

namespace App\Http\Controllers\Api\Tiketcom;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tiketcom\Client as TiketClient;

class HotelController extends Controller
{
    protected $tiket;

    public function __construct()
    {
        $this->tiket = new TiketClient();
    }

    public function search(Request $request)
    {
        #https://api-sandbox.tiket.com/search/hotel?q=Indonesia&startdate=2012-06-11&night=1&enddate=2012-06-12&room=1&adult=2&child=0&token=1c78d7bc29690cd96dfce9e0350cfc51&output=json
        
        $params = [
            'q' => 'Indonesia',
            'startdate' => '2012-06-11',
            'enddate' => '2012-06-12',
            'night' => 1,
            'room' => 1,
            'adult' => 2,
            'child' => $request->get('child', 0)
        ];

        $res = $this->tiket->method('/search/hotel')->params($params)->call();

        return response()->json([
            'data' => $res
        ], 200);
    }
}
