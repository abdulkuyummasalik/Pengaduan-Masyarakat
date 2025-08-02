<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{
    public function index(){
        $provinceUrl = env('APP_PROVINCE_URL');
        $province = Http::get($provinceUrl);
        $dataProvince = $province->json();

        return view('area', compact('dataProvince'));
    }
}
