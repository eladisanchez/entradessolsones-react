<!doctype html>
<html>

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Entrades Solsonès {{ $product->title_ca }}</title>
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

		<div class="ticket">

			<div class="ticket-image">
				@if(is_file(public_path('images/'.$product->name.'.jpg')))
				<img src="{{url('img/medium/'.$product->name)}}.jpg" class="cartell">
				@endif
			</div>

				<div class="ticket-info">
					<h1>{{{ $product->title }}}</h1>

					<p class="ticket-Rate"><strong class="Rate Rate-1">{{$product->rates[0]->title_ca}}</strong> @if($product->espai_id)FILA 3 SEIENT 16 @endif</p>

					<p>
						<strong>{{ trans('calendar.'.date('l'))}}
						{{ date('j') }} {{ trans('calendar.'.date('F'))}} {{ date('Y') }} - {{{ date('H:i') }}}</strong> - Preu: {{{ number_format($product->rates[0]->pivot->preu,2,',','.') }}} &euro;<br>
						@if($product->espai)	
						{{$product->espai->name}}<br>
						{{$product->espai->adreca}}
						@else @if($product->lloc)
						Lloc de l'esdeveniment / inici de la visita:<br>{{$product->lloc}}
						@endif @endif
					</p>
				</div>
				

				<div class="ticket-qr">
					<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z/C/HgAGgwJ/lK3Q6wAAAABJRU5ErkJggg==" class="qr">
					<p>123456789ABCDEFG<br>Nom Cognoms</p>
				</div>

			</div>

	
	</body>


</html>