@extends('layouts.app')

@section('head')
    <title>{{ $product->title }} - {{ config('app.name') }}</title>
@stop

@section('content')

    <div class="product-header {{ $product->target }}"
        @if (file_exists('images/header/' . $product->nom . '.jpg')) style="background-image: url('{{ url('img/large/header/' . $product->nom . '.jpg') }}')" @endif>
        {{-- <img src="{{ asset('css/img/cap.svg') }}" class="serra"> --}}
        <div class="product-header-title">
            <h2><strong>{{ $product->title }}</strong></h2>
            @if ($product->organizator)
                <p>{{ $product->organizator->username }}</p>
            @endif
        </div>
        {{-- <div class="fletxa"></div> --}}

    </div>

    <div class="product-info">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <a href="{{ url('img/large/' . $product->name) }}.jpg" class="img-popup"><img
                            src="{{ url('img/medium/' . $product->nom) }}.jpg" alt="{{ $product->title }}"
                            class="img-fluid mb-4"></a>
                </div>
                <div class="col-md-5">
                    {!! $product->description !!}
                    @if (!isset($pack) && $product->has('packs'))
                        @foreach ($product->packs as $pk)
                            <div class="alert alert-light">
                                <i class="fas fa-info-circle"></i> {{ __('Aquest producte forma part del pack') }} <a
                                    href="{{ action('ProducteController@show', $pk->nom) }}">{{ $pk->titol }}</a>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="col-md-4 col-horaris">
                    {!! $product->schedule !!}
                </div>
            </div>
        </div>
    </div>

    <div class="proces-reserva" id="entrades">

        @if (!isset($diesDisponibles))

            <div class="container">
                <div class="alert alert-warning">
                    {{ __('No hi ha entrades disponibles per aquest esdeveniment o activitat.') }} <a
                        href="{{ route('home') }}#{{ $product->target }}">&laquo; {{ __('Torna enrere') }}</a>
                </div>
            </div>
        @else
            <div class="container">

                @if (isset($pack))
                    <div class="alert alert-success">
                        <i class="far fa-bell"></i>
                        {{ __("Estàs reservant l'entrada dins del pack") }} <em>{{ $pack->title }}</em>. <a
                            href="{{ action('PackController@esborraPack', ['id' => $pack->id]) }}">{{ __('Cancel·la el pack') }}</a>
                    </div>
                @endif


                @if (Session::get('message'))
                    <p class="alert alert-danger">{!! Session::get('message') !!}</p>
                @endif

                <div class="passos row">

                    {{-- COLUMNA ESQUERRA --}}
                    <div class="col-lg-4 col-md-6">

                        {{-- DIA --}}
                        @include('passos.dia')

                        @if ($product->venue)
                            {{-- TARIFA --}}
                            @include('passos.tarifa-espai')
                        @else
                            {{-- HORA --}}
                            <div id="content-hora">
                                @include('passos.hora')
                            </div>
                        @endif

                    </div>


                    {{-- COLUMNES DRETA --}}
                    <div class="col-lg-8 col-md-6">

                        <div id="content-preu">

                            @if (isset($error))
                                @include('passos.error')
                            @else
                                @if ((isset($dia) && $product->espai_id) || isset($hora))

                                    @if ($product->venue)

                                        {{-- LOCALITATS --}}
                                        @if (isset($pack))
                                            @include('passos-pack.localitats')
                                        @else
                                            @include('passos.localitats')
                                        @endif
                                    @else
                                        {{-- PREU --}}
                                        @include('passos.preu')

                                    @endif

                                @endif

                            @endif



                        </div>

                    </div>

                </div>

        @endif

    </div>

    </div>


    {{-- SUGGERÈNCIES --}}
    {{-- @include('passos.suggerencies') --}}

@stop
