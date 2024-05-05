<!doctype html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Entrades Solsonès {{ $order->id }}</title>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700" rel="stylesheet">
		<style>
			body{
				line-height: 1.1;
				font-family: Helvetica, Arial, sans-serif;
				font-size:13px;
				color: #000;
			}
			.pdf-title {
				margin: 0;
				font-family: 'Roboto Condensed', Arial;
				font-size: 38px;
				text-transform: uppercase;
				width: 100%;
			}
			.dades-client {
				text-align: right;
			}
			.header{position:relative;display:block;width:100%; font-size: 11px;}
			.header img{height:60px;width:auto}
			
			table{width:100%;padding:0;border-spacing:0;padding-bottom: 10px;}
			table th{text-align:left;color:#000;font-size:15px}
			ol li {
				margin: .5em 0;
			}
			.condicions{font-size:10px;font-family: sans-serif;}
		.productes {
			border-bottom: 1px solid #ddd;
		}
		table td, table hr {
			vertical-align: top;
			padding: 5px 0;
		}
		h2 {
			font-weight: normal;
		}
		.titol-entrades {
			border-bottom: 1px solid #ddd;
			margin-bottom: 5px;
			padding: 5px;
			background: #ddd;
			font-size: 18px;
		}
		.ticket {
			position: relative;
			font-family: 'Roboto Condensed', Arial;
			padding: 0 120px 15px 190px;
			border-bottom: 1px dotted #999;
			line-height: .9;
			margin-bottom: 20px;
		}
		.ticket-image {
			position: absolute;
			top: 0; 
			left: 0;
			width: 160px;
			
		}
		.ticket-image img {
			height: auto;
			width: 160px;
			border-radius: 4px;
		}
		.ticket h1 {
			margin: 0;
			font-size: 36px;
			text-transform: uppercase;
			line-height: .6;
		}
		.ticket-info {
			min-height: 170px;
		}
		.ticket-Rate {
			margin: 0;
			font-size: 26px;
		}
		.ticket-qr {
			position: absolute;
			top: -10px; 
			right: 0;
			width: 100px;
			text-align: right;
			font-size: 9px;
		}
		.ticket-qr img {
			width: 120px;
			height: auto;
		}
		.ticket-qr p {
			padding-right: 15px;
		}
		.Rate {
			border-bottom-width: 1px;
			border-bottom-style: solid;
		}
		.Rate-10 {
			color: #036057;
			border-bottom-color: #036057;
		}
		.entrada .infocomanda {
			position: absolute;
			font-size: 9px;
			top: 8px;
			right: 8px;
			margin: 0;
		}
		.header p,.header h1 {
			margin: 0;
		}
		.codicomanda {
			position: absolute;
			top: 0;
			right: 0;
			width: 160px;
			padding: 5px;
			font-size: 9px;
			color: #999;
			z-index: 100;
			text-align: center;
			transform: rotate(90deg)
		}
		.codicomanda strong {
		}
		.total {
			margin-top: 0;
			font-size: 18px;
			text-align: right;
			padding: 5px;
			background: #DDD;
		}
		</style>
	</head>

	<body>
		
		<div class="header">
			<table><tr>
				<td width="75%">
			<img src="data:image/png;base64,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">
			
				</td><td>
					<table class="dades-client">

						<tr>
							<td>
								<strong>
								Núm. comanda: {{str_pad($order->id, 6, '0', STR_PAD_LEFT)}}<br> 
								Data: {{$order->created_at->format('d/m/Y')}}</strong><br>
								{{$order->name}}<br>
								{{$order->email}}<br>T. {{$order->tel}}
								@if($order->contact) 
								<strong>{{trans('comanda.Persona de contacte')}}:</strong> {{$order->contact}} 
								@endif
							</td>
							<td>
								@if($order->observations)
								<strong>{{trans('comanda.Observacions')}}:</strong><br>{{$order->observations}}
								@endif
								@if($order->coupon)
								<br><strong>Codi descompte:</strong> {{$order->coupon}}
								@endif
							</td>
						</tr>
			
					</table>
				</td></tr></table>
		</div>

		<h1 class="pdf-title">Entrades Solsonès</h1>

		<h2 class="titol-entrades">{{__('La teva compra')}}</h2>

		<table class="productes">

			<tr>
				<th>{{__("Entrades")}}</th>
				<th>Dia, hora i lloc</th>
				<th>{{__("Qt / Rate")}}</th>
				<th style="text-align: right">{{__("Preu")}}</th>
			</tr>

			@php $is_entrades = false; @endphp

			@foreach($order->bookings as $booking)

				<tr>
					<td>
						{{$booking->product->title}}
						@if($booking->seat)
							- {{ \App\Helpers\Common::seient($booking->seat) }} @php $localitats = true; @endphp
						@endif
						@if($booking->is_pack==1) 
							<small>(PACK)</small>
						@endif
					</td>
					<td>
						@if(isset($booking->day)&&$booking->is_pack==0)
						{{$booking->day->format('d/m/Y')}} - {{$booking->hour}} h
						@endif
						<br>{{$booking->product->lloc ?? ''}}
					</td>
					<td>
						{{$booking->numEntrades}} x {{$booking->rate->title}}
					</td>
					<td style="text-align: right">
						@if($booking->preu>0)
						{{number_format($booking->numEntrades*$booking->preu,2,',','.')}} &euro;
						@endif
					</td>
				</tr>

				@php
				if($booking->product->qr || $booking->seat) {
					$is_entrades = true; 
				}
				//$is_entrades = false;
				@endphp


			@endforeach

		</table>

		<p class="total"><strong>Total: {{number_format($order->total,2,',','.')}} &euro;</strong></p>

		

		<div class="condicions">
			{!! $text["pdf_condicions"] !!}
			
			{{--
			@if(count($order->organitzadors()))
			@foreach($order->organitzadors() as $org)
				<hr style="border:0;border-bottom:1px solid #ccc">
				<h4>{{$org->username}}</h4>
				{{$org->condicions}}
			@endforeach
			@endif
			--}}
			
		</div>

		@if($is_entrades)

			<div style="page-break-before: always;"></div>

		@foreach($order->bookings as $booking)

			@php $i = 1; @endphp

			@if ($booking->seat || $booking->product->qr)

			@while($i <= $booking->tickets)

			<div class="ticket">

				<div class="ticket-image">
					@if(is_file(public_path('images/'.$booking->product->name.'.jpg')))
					<img src="{{url('img/medium/'.$booking->product->name)}}.jpg" class="cartell">
					@endif
				</div>

				<div class="ticket-info">
					<h1>{{{ $booking->product->title }}}</h1>

					<p class="ticket-Rate"><strong class="Rate Rate-{{$booking->rate->id}}">{{$booking->rate->title}}</strong> @if($booking->seat){{ \App\Helpers\Common::seient($booking->seat) }}@endif</p>

					<p>
						<strong>{{ trans('calendar.'.$booking->day->format('l'))}}
						{{ $booking->day->format('j') }} {{ trans('calendar.'.$booking->day->format('F'))}} {{ $booking->day->format('Y') }} - {{{ $booking->hour }}}</strong> - Preu: {{{ number_format($booking->preu,2,',','.') }}} &euro;<br>
						@if($booking->product->espai)	
						{{$booking->product->espai->name}}<br>
						{{$booking->product->espai->adreca}}
						@else @if($booking->product->lloc)
						Lloc de l'esdeveniment / inici de la visita:<br>{{$booking->product->lloc}}
						@endif @endif
					</p>
				</div>
				

				<div class="ticket-qr">
					<img src="data:image/png;base64, {!!$booking->qrimage($i)!!}" class="qr">
					<p>{{$booking->qrcode($i)}}<br>{{{ $order->name }}}</p>
				</div>

			</div>

			@php $i++; @endphp

			@endwhile

			@endif
		@endforeach
	

		@endif

		
		
	</body>


</html>