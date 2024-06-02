@extends('report.base')

@section('content')

	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
		<h1>Productes</h1>
		<a href="{{ action('ProductController@solicitud') }}" class="mb-2 mb-md-0 btn btn-outline-primary"><i class="fas fa-plus"></i> Nova sol·licitud</a>
	</div>

		<table class="table">
			@foreach($products as $product)

			<tr>
				<td width="100">
					@if(file_exists('images/'.$product->name.'.jpg')) 
						<a href="{{ action('TicketController@indexReport', $product->id) }}"><img class="img-fluid border-rounded" src="{{url('img/th/'.$product->name)}}.jpg" style="width: 80px;"></a>
					@endif
				</td>

				<td class="align-top">
					<a href="{{ action('TicketController@indexReport', $product->id) }}" class="h5">
						<strong>{{ $product->title }}</strong>
					</a><br>
					<div class="mt-2">
						<strong>{{ $product->bookingspagament('targeta') }}</strong> entrades venudes (pagament amb targeta)<br>
						<strong>{{ $product->bookingspagament('credit') }}</strong> entrades reservades per l'organitzador
					</div>
				</td>

				<td class="text-right">
					<a href="{{ action('TicketController@indexReport', $product->id) }}" class="btn btn-outline-primary btn-xs"><i class="fas fa-ticket-alt"></i> Entrades</a>
				</td>


			{{-- <div class="card">
				@if(file_exists('images/'.$product->name.'.jpg')) 
					<a href="{{ action('TicketController@indexReport', $product->id) }}">
						<img src="{{url('img/medium/'.$product->name)}}.jpg" class="card-img-top">
					</a>
				@endif
				<div class="card-header">
					{{$product->title}}
				</div>
				<ul class="list-group list-group-flush">
					<li class="list-group-item">
						{{ $product->bookingspagament('targeta') }} entrades venudes<br>
						(pagament amb targeta)
					</li>
					<li class="list-group-item">
						{{ $product->bookingspagament('credit') }} entrades reservades per l'organitzador
					</li>
				</ul>
				<div class="card-body">
					<a href="{{ action('TicketController@indexReport', $product->id) }}" class="btn btn-outline-primary btn-xs"><i class="fas fa-ticket-alt"></i> Entrades</a>
				</div>
			</div> --}}
		</tr>
			@endforeach
	</table>


	</div>

@stop