<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Goutte\Client as CrawlerClient;
use GuzzleHttp\Client as GuzzleClient;
use App\Mail\MailgunMailer;
use Illuminate\Support\Facades\Mail;
use App\Trail;

class HomeController extends Controller
{

    const BTC_JP_API_URL = 'https://api.bitflyer.jp/v1/getticker';
    const BTC_KR_API_URL = 'https://api.bithumb.com/public/ticker/BTC';
    const ETH_JP_API_URL = 'https://api.zaif.jp/api/1/last_price/eth_jpy';
    const ETH_KR_API_URL = 'https://api.bithumb.com/public/ticker/ETH';
    const REAL_CURRENCY_CONVERTER = 'http://www.xe.com/currencyconverter/convert/?Amount=1&From=JPY&To=KRW';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $client = new Client();
        $btc_jp_api_response = $client->request('GET', self::BTC_JP_API_URL);
        $btc_kr_api_response = $client->request('GET', self::BTC_KR_API_URL);
//        $btc_jp_api_response = $client->request('GET', self::ETH_JP_API_URL);
//        $btc_kr_api_response = $client->request('GET', self::ETH_KR_API_URL);
        $crawlerClient = new CrawlerClient();
        $crawler = $crawlerClient->request('GET', self::REAL_CURRENCY_CONVERTER);
        $one_jp_won_at_real = $crawler->filter('.uccResultAmount')->text();
        $one_jp_won_at_real = floatval($one_jp_won_at_real);
        $btc_jp_body = json_decode($btc_jp_api_response->getBody());
        $btc_kr_body = json_decode($btc_kr_api_response->getBody());
        $one_btc_jp_price = $btc_jp_body->best_bid;   //JP BTC
//        $one_btc_jp_price = $btc_jp_body->last_price;   //JP ETH
        $one_btc_kr_price = $this->get_price_from_bithumb_api($btc_kr_body);
        $one_jpy_to_btc_to_krw = $one_btc_kr_price / $one_btc_jp_price ;
        $one_btc_jpy_to_krw_at_real = $one_btc_jp_price * $one_jp_won_at_real;
        $send_btc_amount = 1;
        $send_btc_amount = $send_btc_amount - ($send_btc_amount * (0.15 /100)); //BTC
        $btc_fee_jp_to_kr = $this->get_btc_sending_fee_jp_to_kr($send_btc_amount);
        $real_btc_send_jp_to_kr = $send_btc_amount - $btc_fee_jp_to_kr;
        $real_btc_send_jp_to_kr  = $real_btc_send_jp_to_kr  - ($real_btc_send_jp_to_kr  * (0.15 /100)); //BTC
        $estimated_krw = $real_btc_send_jp_to_kr * $one_btc_kr_price;
        $estimated_jpy = $estimated_krw / $one_jp_won_at_real;
        $bank_fee_kr_to_jp = $this->get_bank_sending_fee_kr_to_jp($estimated_krw);
        $recieve_jp_fee = 4000;
        $bank_fee_kr_to_jp_at_jpy = ($bank_fee_kr_to_jp / $one_jp_won_at_real) + $recieve_jp_fee; //1
        $final_jpy = $estimated_krw / $one_jp_won_at_real - $bank_fee_kr_to_jp_at_jpy;
        $gap = $final_jpy -  ($one_btc_jp_price * $send_btc_amount);
        $data['jp_price'] = $one_btc_jp_price;
        $data['kr_price'] = $one_btc_kr_price;
        $data['one_jpy_to_btc_to_krw'] = floatval($one_jpy_to_btc_to_krw);
        $data['one_jp_won_at_real'] = $one_jp_won_at_real;
        $data['one_btc_jpy_to_krw_at_real'] = $one_btc_jpy_to_krw_at_real;
        $data['btc_fee_jp_to_kr'] = $btc_fee_jp_to_kr;
        $data['real_btc_send_jp_to_kr'] = $real_btc_send_jp_to_kr;
        $data['estimated_krw'] = $estimated_krw;
        $data['estimated_jpy'] = $estimated_jpy;
        $data['bank_fee_kr_to_jp'] = $bank_fee_kr_to_jp;
        $data['recieve_jp_fee'] = $recieve_jp_fee;
        $data['bank_fee_kr_to_jp_at_jpy'] = $bank_fee_kr_to_jp_at_jpy;
        $data['final_jpy'] = $final_jpy;
        $data['gap'] = $gap;

        $trail = Trail::all();


        return view('home', $data);
    }

    /**
     * Bithumb API からコインの最終取引値を抽出
     * @param $api_body
     * @return mixed
     */
    private function get_price_from_bithumb_api ($api_body) {
        return $api_body->data->closing_price;
    }
    /**
     * 日本から韓国へBTC送金時手数料
     * @param $btc_amount
     * @return float|int
     */
    private function get_btc_sending_fee_jp_to_kr($btc_amount) {
        return 0.0004;
    }
    /**
     * 韓国から日本へBTC送金時手数料
     * @param $btc_amount
     * @return float|int
     */
    private function get_btc_sending_fee_kr_to_jp ($btc_amount) {
        return 0.0004;
    }
    /**
     * 日本から韓国へ銀行送金時の手数料
     * @param $jpy_amount
     * @return float|int
     */
    private function get_bank_sending_fee_jp_to_kr($jpy_amount) {
        //TODO 具体的な手数料は調査して実装　戻り値は日本円
        $fee = 5000;
        if ($jpy_amount > 2000000) {
            $fee = $fee * 0.001;
        } else if ($jpy_amount > 1000000) {
            $fee = 10000;
        } else {
            $fee = 5000;
        }
        return $fee;
    }
    /**
     * 韓国から日本へ銀行送金時の手数料
     * @param $krw_amount
     * @return float|int
     */
    private function get_bank_sending_fee_kr_to_jp($krw_amount) {
        //TODO 具体的な手数料は調査して実装、戻り値は韓国W
        $fee = 8000;
//        if ($krw_amount > 20000000) {
//            $fee = $krw_amount * 0.0001;
//        } else if ($krw_amount > 10000000) {
//            $fee = $krw_amount * 0.0001;
//        } else {
//            $fee = $krw_amount * 0.0001;
//        }
        return $fee ;
    }

}
