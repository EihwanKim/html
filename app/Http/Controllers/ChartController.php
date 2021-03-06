<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trail;
use Carbon\Carbon;

class ChartController extends Controller
{

	/**
     	* Create a new controller instance.
 	*
     	* @return void
     	*/
	public function __construct()
	{
		$this->middleware('auth');
	}

	//
	public function index() {

		$all = Trail::all();
		$time = [];
		$margin = [];
		foreach ($all as $val) {
			array_push($time, Carbon::createFromFormat('Y-m-d H:i:s', $val->created_at)->timestamp);
			array_push($margin, intval($val->gap));
		}
		$data['times'] = json_encode($time);
		$data['margins'] = json_encode($margin);
		return view('chart', $data); 
	}
}
