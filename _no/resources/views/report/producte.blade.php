@extends('report.base')

@section('content')


	<h1>{{ $product->title }}</h1>

	<ul class="nav nav-tabs my-4">
		<li class="nav-item">
			<a class="nav-link {{request()->input('ant')?'':'active'}}" href="{{ action('TicketController@indexReport', $product->id) }}">Entrades disponibles</a>
		</li>
		<li class="nav-item">
			<a class="nav-link {{request()->input('ant')?'active':''}}" href="{{ action('TicketController@indexReport', $product->id) }}?ant=1">Entrades anteriors</a>
	  	</li>
	</ul>

	<table class="table table-striped table-condensed">

		<thead class="thead-dark">
		<tr><th>Dia</th><th>Sessions</th><th>Disponibles</th></tr>
		</thead>

		<tbody>
		@foreach($dies as $dia => $hores)
			<tr>
				<td class="th" width=260>{{ \App\Helpers\Common::data($hores['dia']) }}</td>
				<td>
					@foreach($hores['hores'] as $hora)
						<strong class="mr-3">{{ $hora['hora'] }} h:</strong>
						<div class="btn-group mr-3" role="group" aria-label="Reserva">
							<span class="btn btn-outline-secondary">
								<strong>{{ $hora['disponibles'] }}</strong> / {{ $hora['entrades'] }}
							</span>
							@if(!request()->input('ant'))
							<a href="{{ route('product',array($product->name,$dia,$hora['horaok'])) }}" class="btn btn-secondary" title="Reserva"><i class="fas fa-arrow-right mr-1"></i> Reservar</a>
							@endif
							@if($product->espai)
							<a href="{{route('mapa',[$product->id,$dia,$hora['hora']])}}" class="btn btn-primary" target="_blank"><i class="fas fa-print mr-1"></i> Plànol</a>
							@endif
						</div>
					@endforeach
				</td>
				<td>
					<strong>{{ $hores['disponiblesDia'] }}</strong>/{{ $hores['entradesDia'] }}
				</td>
				
			</tr>
		@endforeach
		</tbody>

	</table>

	{{ $entrades->appends(request()->input())->links() }}

@stop