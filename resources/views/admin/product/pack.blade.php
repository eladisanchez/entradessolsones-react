@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'products'])

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1>{{ $product->title }}</h1>
        @include('admin.menus.productes', ['product' => $product, 'active' => 'pack'])
    </div>


    <div class="row mt-5">
        @foreach ($product->productesDelPack as $item)
            <div class="col-md-3">
                <div class="card">
                    @if (file_exists('images/' . $item->name . '.jpg'))
                        <img class="card-img-top" src="{{ url('img/medium/' . $item->name) }}.jpg">
                    @endif
                    <div class="card-body">
                        <h4 class="card-title">{{ $item->title }}</h4>
                        {{ Form::open(['action' => ['PackController@destroy', $product->id, $item->id], 'method' => 'delete']) }}
                        <button type="submit" href="{{ action('PackController@destroy', [$product->id, $item->id]) }}"
                            class="btn btn-outline-danger btn-sm">Elimina</button>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>


    <div class="card mt-5">
        <div class="card-header bg-secondary text-light">
            Afegeix producte
        </div>
        <div class="card-body">
            {{ Form::open(['action' => ['PackController@store', $product->id]]) }}
            <div class="input-group">
                {{ Form::select('producte_id', $products_disponibles, null, ['class' => 'custom-select']) }}
                <div class="input-group-append">
                    {{ Form::button('<i class="fas fa-plus-square"></i> Afegeix al pack', ['class' => 'btn btn-primary', 'type' => 'submit']) }}
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>

@stop
