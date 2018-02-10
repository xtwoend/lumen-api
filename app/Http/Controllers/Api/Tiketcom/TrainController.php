<?php

namespace App\Http\Controllers\Api\Tiketcom;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Tiketcom\Client as TiketClient;

class TrainController extends Controller
{
    protected $tiket;

    public function __construct()
    {
        $this->tiket = new TiketClient();
    }

    public function search(Request $request)
    {
        //endpoint https://api-sandbox.tiket.com/search/train?d=GMR&a=BD&date=2012-06-03&ret_date=&adult=1&child=0&class=all&token=80bfe5297f7c4fbaa7a1e6c022585946&output=json

        $params = [
            'd' => $request->departure,
            'a' => $request->arrival,
            'date' => $request->date,
            'ret_date' => $request->input('return_date', ''),
            'adult' => $request->adult,
            'child' => $request->input('child', 0),
            'class' => $request->input('class', 'all')
        ];

        $res = $this->tiket->method('/search/train')->params($params)->call();

        return response()->json([
            'data' => $res
        ], 200);
    }

    public function station(Request $request)
    {
        # https://api-sandbox.tiket.com/train_api/train_station?token=11b534a0394d9bb140f18bc1a87adf1a&output=json

        $res = $this->tiket->method('/train_api/train_station')->params([])->call();

        return response()->json([
            'data' => $res
        ], 200);
    }

    public function seatMap(Request $request)
    {
        # http://api-sandbox.tiket.com/general_api/get_train_seat_map?date=2015-06-30&train_id=A32&subclass=A&org=GMR&dest=BD&token=a86d0826af2e445be7c8ad36a5ab2b601358c040

        $params = [
            'date' => $request->date,
            'train_id' => $request->train_id,
            'subclass' => $request->input('subclass', ''),
            'org' => $request->departure,
            'dest' => $request->arrival
        ];

        $res = $this->tiket->method('/general_api/get_train_seat_map')->params($params)->call();

        return response()->json([
            'data' => $res
        ], 200);
    }

    public function order(Request $request)
    {
        #https://api-sandbox.tiket.com/order/add/train?d=GMR&a=BD&date=2012-06-03&ret_date=&adult=1&child=0&token=13fc239d306bf7085708566b3d085b29&train_id=IVHAN1&subclass=A&output=json&conSalutation=Mr&conFirstName=b&conLastName=bl&conEmailAddress=be@scom&conPhone=0878121&nameAdult1=a&IdCardAdult1=111&noHpAdult1=%2B62878222&salutationAdult1=Mr&birthDateAdult1=1990-02-02

        $this->validate($request, [
            'date' => 'required',
            'adult' => 'required',
            'train_id' => 'required',
            'departure' => 'required',
            'arrival' => 'required',
            'contact' => 'required|array',
            'passenger' => 'required|array'
        ]);

        $params = [
            'date' => $request->date,
            'ret_date' => $request->input('return_date', ''),
            'adult' => $request->adult,
            'child' => $request->input('child', 0),
            'train_id' => $request->train_id,
            'subclass' => $request->input('subclass', ''),
            'd' => $request->departure,
            'a' => $request->arrival,
            'conSalutation' => $request->contact['salutation'],
            'conFirstName' => $request->contact['first_name'],
            'conLastName' => $request->contact['last_name'],
            'conEmailAddress' => $request->contact['email'],
            'conPhone' => $request->contact['phone']
        ];

        $passenger = [];
        $no = 1;
        foreach ($request->passenger as $key => $val) {
            foreach ($val as $key => $value) {
                # code...
            }
            $i++;
        }

        $res = $this->tiket->method('/order/add/train')->params([])->call();

        return response()->json([
            'data' => $res
        ], 200);
    }

    public function saldo()
    {
        return $this->tiket->getBalance();
    }
}
