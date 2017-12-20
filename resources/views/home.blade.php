@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <table class="table">
                            <tr>
                                <th>
                                    1BTCの日本円価格(¥)
                                </th>
                                <td>
                                    {{number_format($jp_price)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    1BTCの韓国ウォン価格(W)
                                </th>
                                <td>
                                    {{number_format($kr_price)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    1BTC基準円ウォン為替
                                </th>
                                <td>
                                    {{number_format($one_jpy_to_btc_to_krw, 5)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    銀行為替
                                </th>
                                <td>
                                    {{number_format($one_jp_won_at_real, 5)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    送るBTC量
                                </th>
                                <td>
                                    {{number_format($send_btc_amount, 5)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    日本から韓国へBTC送金手数料(BTC)
                                </th>
                                <td>
                                    {{number_format($btc_fee_jp_to_kr, 4)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    日本から韓国へ送られるBTC
                                </th>
                                <td>
                                    {{number_format($real_btc_send_jp_to_kr, 5)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    韓国に送られたBTCの韓国相場(W)
                                </th>
                                <td>
                                    {{number_format($estimated_krw)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    上記金額の銀行基準日本円換算(¥)
                                </th>
                                <td>
                                    {{number_format($estimated_jpy)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    韓国から日本への送金手数料(W)
                                </th>
                                <td>
                                    {{number_format($bank_fee_kr_to_jp)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    送金受取手数料
                                </th>
                                <td>
                                    {{number_format($recieve_jp_fee)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    韓国から日本への送金手数料総額の円換算(¥)
                                </th>
                                <td>
                                    {{number_format($bank_fee_kr_to_jp_at_jpy)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    最終的に得られる日本円の想定額
                                </th>
                                <td>
                                    {{number_format($final_jpy)}}
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    BTC購入額と戻ってくる金額の差分
                                </th>
                                <td>
                                    {{number_format($gap)}}
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
