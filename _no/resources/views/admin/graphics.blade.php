@extends('layouts.admin')

@section('content')

	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
		<h1>Dades d'ús</h1>
	</div>

	@isset($cp)
	<table class="table">
		<tr>
			<th>Codi postal</th>
			<th>Quantitat</th>
			<th>Població</th>
			<th>Província</th>
		</tr>
		@foreach($cp as $item)
		<tr>
			<td>{{$item->cp}}</td>
			<td>{{$item->quantitat}}</td> 
			<td>{{$item->poblacio}}</td> 
			<td>{{$item->provincia}}</td>
		</tr>
		@endforeach
	</table>
	@endisset

	@isset($sales)
	<table class="table">
		<tr>
			<th>Data</th>
			<th>Total</th>
		</tr>
		@foreach($sales as $item)
		<tr>
			<td>{{$item->created_at}}</td>
			<td>{{$item->total}}</td>
		</tr>
		@endforeach
	</table>
	@endisset
	
@stop