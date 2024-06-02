@extends('layouts.app',['class'=>'front-page'])

@section('content')


	<div class="product-header">
		{{-- <img src="{{ asset('css/img/cap.svg') }}" class="serra"> --}}
		<div class="product-header-title">
			<h2>{{__("Entrades")}} <strong>Solsonès</strong></h2>
			<p>{{__("Planifica la teva estada al Solsonès")}}</p>
		</div>
		{{-- <div class="fletxa"></div> --}}
		{{-- <div class="site-menu">
			<div class="container">
				<a href="">Activitats turístiques</a>
				<a href="">Teatre i concerts</a>
			</div>
		</div> --}}
	</div>


	@if(count($products['activities']))

		<h2 id="activitats" class="titol-categoria">
			<div class="container">
				<i class="fa-sharp fa-solid fa-camera-retro mr-3"></i> {{ __("Activitats turístiques") }}
			</div>
		</h2>

		<div class="bg-categoria"><div class="container">

			@foreach($products['activities'] as $cat)
				@include('category', ['cat' => $cat])
			@endforeach

		</div></div>

	@endif


	@if(count($products['events']))

		<h2 id="esdeveniments" class="titol-categoria">
			<div class="container">
				<i class="fas fa-music mr-3"></i> {{ __("Teatre, concerts i esdeveniments") }}
			</div>
		</h2>

		<div class="bg-categoria"><div class="container">

			@foreach($products['events'] as $cat)
				@include('category', ['cat' => $cat])
			@endforeach

		</div></div>
		
	@endif


	@if(count($products['others']))

		<h2 id="activitats" class="titol-categoria">
			<div class="container">
				{{ __("Altres activitats") }}
			</div>
		</h2>

		<div class="bg-categoria"><div class="container">

			@foreach($products['activities'] as $cat)
				@include('category', ['cat' => $cat])
			@endforeach

		</div></div>

	@endif

	<h2 id="activitats" class="titol-categoria">
		<div class="container">
			<a href="/calendari"><i class="fa-regular fa-calendar mr-3"></i> {{ __("Calendari d'activitats") }}</a>
		</div>
	</h2>

@stop