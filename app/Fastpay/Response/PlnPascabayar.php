<?php

namespace App\Fastpay\Response;

use App\Fastpay\ResponseInterface;


/**
* 
*/
class PlnPascabayar implements ResponseInterface
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
            'status' => $this->data[14],
            'trasaction_date' => $this->data[2],
            'product_code' => $this->data[0],
            'description' => $this->data[15],
            'costumer' => [
                'name' => $this->data[5],
                'bill_number' => $this->data[2]
            ],
            'trasaction' => [
                'bill_date' => $this->data[6],
                'amount' => $this->data[7],
                'admin_fee' => $this->data[8],
                'notes' => $this->data[18]->CATATAN,
            ]
        ];
    }
    /**
     * [
        "KODEPRODUK", 0
        "WAKTU(YYYYMMDDHHIISS)", 1
        "IDPELANGGAN1",2
        "IDPELANGGAN2",3
        "IDPELANGGAN3",4
        "NAMAPELANGGAN",5
        "PERIODETAGIHAN",6
        "NOMINAL",7
        "BIAYAADMIN",8
        "UID",9
        "PIN",10
        "REF1",11
        "REF2",12
        "REF3",13
        "STATUS",14
        "KETERANGAN",15
        "SALDOTERPOTONG",16
        "SISASALDO",17
        "URLSTRUK",18
        {
        CATATAN: "CATATANVALUE",
        SUBSCRIBERSEGMENTATION: "SUBSCRIBERSEGMENTATIONVALUE",
        POWERCONSUMINGCATEGORY: "POWERCONSUMINGCATEGORYVALUE",
        SLALWBP1: "SLALWBP1VALUE",
        SAHLWBP1: "SAHLWBP1VALUE",
        SAHLWBP2: "SAHLWBP2VALUE",
        SAHLWBP3: "SAHLWBP3VALUE",
        SAHLWBP4: "SAHLWBP4VALUE"
        }
        ]
     */
}
