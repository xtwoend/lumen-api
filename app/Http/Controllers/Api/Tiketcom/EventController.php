<?php

namespace App\Http\Controllers\Api\Tiketcom;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tiketcom\Client as TiketClient;

class EventController extends Controller
{
    protected $tiket;

    public function __construct()
    {
        $this->tiket = new TiketClient();
    }

    public function search(Request $request)
    {
        #https://api-sandbox.tiket.com/search/event?token=c551ad2aee8e7acf14907c0fac2644d9&output=json

        $res = $this->tiket->method('/search/event')->params([])->call();

        return response()->json([
            'data' => $res
        ], 200);
    }
}
