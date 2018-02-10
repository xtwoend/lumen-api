<?php

namespace App\Http\Controllers\Api;

use App\Fastpay\Client as FastpayClient;
use App\Fastpay\Response\Game;
use App\Fastpay\Response\Pulsa;
use App\Fastpay\Response\BpjsInquiry;
use App\Fastpay\Response\BpjsPayment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillerController extends Controller
{
    const FASTPAY_METHOD = [
        'PULSA' => 'fastpay.pulsa',
        'GAME'  => 'fastpay.game',
        'PAYMENT' => 'fastpay.pay',
        'INQUIRY' => 'fastpay.inq',
        'BPJSINQ' => 'rajabiller.bpjsinq',
        'BPJSPAY' => 'rajabiller.bpjspay'
    ];

    protected $fastpayclient;

    public function __construct()
    {
        $this->fastpayclient = new FastpayClient;
    }

    public function __invoke(Request $request)
    {
        $this->validate($request, [
            'process_code' => 'required',
            'product_code' => 'required'
        ]);
        
        $fastpayclient = $this->fastpayclient;
        $data = [];
        switch ($request->process_code) {
            case 'PULSA':
                    // format pembelian pulsa 
                    // [namaproduct, notlp] => params
                    // [ref] => kode uniq yang digunakan untuk mengbungkan transaksi nusapay dengan bimasakti
                    $res = $fastpayclient
                        ->method(self::FASTPAY_METHOD[$request->process_code])
                        ->params([
                            $request->product_code,
                            $request->phone_number
                        ])
                        ->refs([str_random(5)])->call();
                    $data = (new Pulsa($res))->getResponse();
                break;
            case 'GAME':
                    // format pembelian vochers games 
                    // [namaproduct, notlp] => params
                    // [ref] => kode uniq yang digunakan untuk mengbungkan transaksi nusapay dengan bimasakti
                    $res = $fastpayclient
                        ->method(self::FASTPAY_METHOD[$request->process_code])
                        ->params([
                            $request->product_code,
                            $request->phone_number,

                        ])
                        ->refs([str_random(5)])->call();
                    $data = (new Game($res))->getResponse();
                break;
            case 'INQUIRY':
                    // inquiry tagihan yang akan dibayar
                    // [namaproduct, id1, id2, id3] => params
                    // [ref] => kode uniq yang digunakan untuk mengbungkan transaksi nusapay dengan bimasakti
                    $params = [
                        $request->product_code,
                        $request->get('param1', ''), 
                        $request->get('param2', ''),
                        $request->get('param3', '')
                    ]; 
                    
                    // keterangan parameter
                    // product_code = Berisi kode produk layanan
                    // param1 = Berisi ID Pelanggan untuk transaksi. Khusus pada transaksi Telepon, berisi kode area
                    // param2 = Berisi ID Pelanggan ke-2 (jika diperlukan). Khusus pada transaksi Telepon, berisi nomor telepon
                    // param3 = Berisi ID Pelanggan ke-3 (jika diperlukan). Khusus pada transaksi PLN Prabayar, berisi nomor HP pelanggan

                    $res = $fastpayclient
                        ->method(self::FASTPAY_METHOD[$request->process_code])
                        ->params($params)
                        ->refs([str_random(5)])->call();
                break;
            case 'PAYMENT':
                    // inquiry tagihan yang akan dibayar
                    // [namaproduct, id1, id2, id3] => params
                    // [ref] => kode uniq yang digunakan untuk mengbungkan transaksi nusapay dengan bimasakti
                    $params = [
                        $request->product_code,
                        $request->get('param1', ''), 
                        $request->get('param2', ''),
                        $request->get('param3', '')
                    ]; 
                    
                    // keterangan parameter
                    // product_code = Berisi kode produk layanan
                    // param1 = Berisi ID Pelanggan untuk transaksi. Khusus pada transaksi Telepon, berisi kode area
                    // param2 = Berisi ID Pelanggan ke-2 (jika diperlukan). Khusus pada transaksi Telepon, berisi nomor telepon
                    // param3 = Berisi ID Pelanggan ke-3 (jika diperlukan). Khusus pada transaksi PLN Prabayar, berisi nomor HP pelanggan

                    $refs = [
                        $request->get('ref1', ''), 
                        $request->get('ref2', ''),
                        $request->get('ref3', '')
                    ];
                    // Ref1. Untuk keperluan anggota RajaBiller, yaitu berupa angka referensi di sistem milik anggota yang connect ke sistem RajaBiller (jika ada dan diperlukan).                     
                    // Ref2. Berisi nomor resi transaksi RajaBiller. Pada request payment, field ini berisi Ref2 yang didapat dari response Inquiry.                     
                    // Ref3. Berisi bulan periode tagihan yang akan dibayarkan (jika diperlukan).
                    
                    $res = $fastpayclient
                        ->method(self::FASTPAY_METHOD[$request->process_code])
                        ->params($params)
                        ->refs([str_random(5)])->call();
                break;
            case 'BPJSINQ':
                $params = [
                    $request->product_code,
                    $request->bpjs_number,
                    $request->date
                ];
                $refs = [
                    $request->get('ref1', '')
                ];
                $res = $this->bpjs('INQUIRY', $params, $refs);
                break;

            case 'BPJSPAY':
                $res = $this->bpjs('PAYMENT', $params, $refs);
                break;

            default:
                # code...
                break;
        }

        dd($data);

        // return $data;
    }

    public function bpjs($method, $params, $refs)
    {
        if($method === 'INQUIRY') {
            
        } else if($method === 'PAYMENT') {

        }

        return false;
    }
}
