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

	public function push() {
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(env('jgQmD4gpcmIDkul+aPkcoX9jHFIeca5eVJmGnM0XkAqRDlqOVkpnZK05+ShTavJoW/JogxyZeyLKW09hs5VY7m09wOnZuccHlEW51ruz9/Ez5GxbHTQsordBP+HFVEN/V6+0JJ3ULb6DKUoxyis7rAdB04t89/1O/w1cDnyilFU='));
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => env('3c0471165f32012f83c81004528bf952')]);
        $messageBuilder= new \LINE\LINEBot\MessageBuilder\TextMessageBuilder('こんにチワワ！');
        $response = $bot->pushMessage('U30c0aa013d3b293f8e54d048731e29b5', $messageBuilder);
        dd($response);
    }
}
