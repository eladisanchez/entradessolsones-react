@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'products'])

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1>{{ $product->title }}</h1>
        @include('admin.menus.productes', ['product' => $product, 'active' => 'preus'])
    </div>

    <div class="row mt-5">
        @if (count($prices))
            @foreach ($prices as $preu)
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $preu->title }}</h4>
                            <p class="card-subtitle mb-4 text-muted">{{ $preu->preu }}</p>
                            {{ Form::open(['route' => ['admin.price.destroy', $product->id, $preu->id], 'method' => 'delete']) }}
                            <button type="submit" class="btn btn-outline-danger btn-sm">Elimina</button>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12 alert alert-warning text-center">Encara no s'han definit preus per aquest producte</div>
        @endif
    </div>

    <div class="card mt-5">
        <div class="card-header">
            Afegeix un preu al producte
        </div>
        <div class="card-body">

            {{ Form::open(['route' => ['admin.price.store', $product->id], 'class' => 'form-horizontal']) }}

            <div class="row">

                <div class="col-sm-6">
                    <div class="form-group">
                        {{ Form::label('Rate_id', 'Rate', ['class' => 'control-label']) }}
                        {{ Form::select('Rate_id', $tarifes_disponibles, null, ['class' => 'custom-select']) }}
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        {{ Form::label('preu', 'Preu global', ['class' => 'control-label']) }}
                        {{ Form::input('number', 'preu', null, ['class' => 'form-control', 'step' => '0.50', 'min' => 0]) }}
                    </div>
                </div>

            </div>


            @if ($product->espai_id)
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('preu_zona[1]', 'Preu Zona 1', ['class' => 'control-label']) }}
                            {{ Form::input('number', 'preu_zona[1]', null, ['class' => 'form-control', 'step' => '0.50', 'min' => 0]) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('preu_zona[2]', 'Preu Zona 2', ['class' => 'control-label']) }}
                            {{ Form::input('number', 'preu_zona[2]', null, ['class' => 'form-control', 'step' => '0.50', 'min' => 0]) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('preu_zona[3]', 'Preu Zona 3', ['class' => 'control-label']) }}
                            {{ Form::input('number', 'preu_zona[3]', null, ['class' => 'form-control', 'step' => '0.50', 'min' => 0]) }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {{ Form::label('preu_zona[4]', 'Preu Zona 4', ['class' => 'control-label']) }}
                            {{ Form::input('number', 'preu_zona[4]', null, ['class' => 'form-control', 'step' => '0.50', 'min' => 0]) }}
                        </div>
                    </div>

                </div>
            @endif


            <div class="form-group">
                {{ Form::submit('Afegeix el preu al producte', ['class' => 'btn btn-primary']) }}
            </div>

            {{ Form::close() }}

        </div>


    @stop
