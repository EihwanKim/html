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

<table>
	<tr>
		<th>
		</th>
		<td>
			{{$jp_price}}
		</td>
	</tr>
	<tr>
		<th>
		</th>
		<td>
			{{$kr_price}}
		</td>
	</tr>
	<tr>
		<th>
		</th>
		<td>
			{{$one_jpy_to_btc_to_krw}}
		</td>
	</tr>
	<tr>
		<th>
		</th>
		<td>
			{{$one_jp_won_at_real}}
		</td>
	</tr>
	<tr>
		<th>
		</th>
		<td>
			{{$one_btc_jpy_to_krw_at_real}}
		</td>
	</tr>
	<tr>
		<th>
		</th>
		<td>
			{{$btc_fee_jp_to_kr}}
		</td>
	</tr>
	<tr>
		<th>
		</th>
		<td>
			{{$real_btc_send_jp_to_kr}}
		</td>
	</tr>
	<tr>
		<th>
		</th>
		<td>
			{{$estimated_krw}}
		</td>
	</tr>
	<tr>
		<th>
		</th>
		<td>
			{{$estimated_jpy}}
		</td>
	</tr>
	<tr>
		<th>
		</th>
		<td>
			{{$bank_fee_kr_to_jp}}
		</td>
	</tr>
	<tr>
		<th>
		</th>
		<td>
			{{$recieve_jp_fee}}
		</td>
	</tr>
	<tr>
		<th>
		</th>
		<td>
			{{$bank_fee_kr_to_jp_at_jpy}}
		</td>
	</tr>
	<tr>
		<th>
		</th>
		<td>
			{{$final_jpy}}
		</td>
	</tr>	
	<tr>
		<th>
		</th>
		<td>
			{{$gap}}
		</td>
	</tr>
</table> 

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
