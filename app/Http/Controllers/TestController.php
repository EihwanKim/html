<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class TestController extends Controller
{
	const coin_names = ['BTC','ETH', 'ETC', 'XMR', 'XRP', 'LTC', 'DASH', 'BCH'];
	const coin_send_fee_coincheck = [
		'BTC' => 0.002,
		'ETH' => 0.01,
		'ETC' => 0.01,
		'XMR' => 0.05,
		'XRP' => 0.15,
		'LTC' => 0.001,
		'DASH' => 0.003,
		'BCH' => 0.001,
	];
	const coin_send_fee_bithumb = [
		'BTC' => 0.0005,
		'ETH' => 0.01,
		'ETC' => 0.01,
		'XMR' => 0.05,
		'XRP' => 0.01,
		'LTC' => 0.01,
		'DASH' => 0.01,
	        'BCH' => 0.001,
	];

	
	public function index() {
		
		$data = [];
		$max_rate = 0;
		$max_name = '';
		$max_price_kr = 0;
		$max_price_jp = 0;
		$min_rate = 99999;
		$min_name = '';	
		$min_price_kr = 0;
		$min_price_jp = 0;	
	
		foreach (self::coin_names as $name) {
			$res_kr_json = exec(' curl https://api.bithumb.com/public/ticker/'.$name);
			$res_kr = json_decode($res_kr_json);
			$price_kr = $res_kr->data->closing_price;
                        $res_jp_json = exec(' curl https://coincheck.com/api/rate/'.$name.'_JPY');
			$res_jp = json_decode($res_jp_json);
			$price_jp = $res_jp->rate;
			$rate = $price_kr / $price_jp;
			$rate = number_format($rate, 5);

			$data[$name]['price_kr'] = $price_kr;
			$data[$name]['price_jp'] = $price_jp;
			$data[$name]['rate'] = $rate;

			if ($max_rate < $rate) {
				$max_rate = $rate;
				$max_name = $name;
				$max_price_kr = $price_kr;
				$max_price_jp = $price_jp;
			}
			if ($min_rate > $rate) {
				$min_rate = $rate;
				$min_name = $name;
				$min_price_kr = $price_kr;
				$min_price_jp = $price_jp;
			}
		}
		$data[$max_name]['max'] = true;
		$data[$min_name]['min'] = true;

		$amount_jp = 100000;
		$buy_coin_max = $amount_jp / $max_price_jp;
		$buy_coin_max = $buy_coin_max - ($buy_coin_max * (0.15/100));//buy fee
 		$sell_coin_max = $buy_coin_max - $this->getSendFeeCoinCheck($max_name);
		$sell_coin_max = $sell_coin_max - ($sell_coin_max * (0.15/100));//sell fee
		$amount_kr = $sell_coin_max * $max_price_kr;
		$buy_coin_min = $amount_kr / $min_price_kr;
		$buy_coin_min = $buy_coin_min - ($buy_coin_min * (0.15/100)); //buy fee
		$sell_coin_min = $buy_coin_min - $this->getSendFeeBithumb($min_name);
		$sell_coin_min = $sell_coin_min - ($sell_coin_min * (0.15/100));//sell fee
		$final_amount_jp = $sell_coin_min * $min_price_jp;	
		
		$calc = [];
		$calc['max_name'] = $max_name;
		$calc['min_name'] = $min_name;
		$calc['amount_jp'] = $amount_jp;
		$calc['buy_coin_max'] = $buy_coin_max;
		$calc['sell_coin_max'] = $sell_coin_max;
		$calc['amount_kr'] = $amount_kr;
		$calc['buy_coin_min'] = $buy_coin_min;
		$calc['sell_coin_min'] = $sell_coin_min;
		$calc['final_amount_jp'] = $final_amount_jp;

		return view('test', ['data' => $data, 'calc' => $calc]);
	}
	
	private function getSendFeeCoinCheck($coin_name) {
		return self::coin_send_fee_coincheck[$coin_name];	
	}
	private function getSendFeeBithumb($coin_name) {
		return self::coin_send_fee_bithumb[$coin_name];
	}
		
}
