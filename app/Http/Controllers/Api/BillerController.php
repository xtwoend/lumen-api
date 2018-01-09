<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillerController extends Controller
{

    public function __construct()
    {
        
    }

    public function __invoke(Request $request)
    {
        return $request->all();
    }
}
