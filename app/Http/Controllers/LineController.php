<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LINE;

class LineController extends Controller
{
    //
	public function index (Request $request) {
		logger($request);
		return view('line');	
	}
}
