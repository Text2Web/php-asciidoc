<?php

namespace App\Http\Controllers;

use App\TexToWeb\TextToWeb;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index(Request $request){
        $texToWeb = new TextToWeb();
        return $texToWeb->getPage($request->path());
    }
}
