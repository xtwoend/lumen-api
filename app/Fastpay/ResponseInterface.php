<?php

namespace App\Fastpay;


interface ResponseInterface
{
    public function __construct(array $data = []);
    public function getResponse();
    public function format(array $data = []);
}