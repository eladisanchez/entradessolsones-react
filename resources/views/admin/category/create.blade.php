@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'categories'])


    <h1>Nova categoria</h1>

    {{ Form::open(['action' => 'CategoriaController@store', 'role' => 'form']) }}

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('title_ca', 'Títol Català') }}
                {{ Form::text('title_ca', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
            <div class="form-group">
                {{ Form::label('resum_ca', 'Resum Català') }}
                {{ Form::textarea('resum_ca', null, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('title_es', 'Títol Castellà') }}
                {{ Form::text('title_es', null, ['class' => 'form-control', 'required' => 'required']) }}
            </div>
            <div class="form-group">
                {{ Form::label('resum_es', 'Resum Castellà') }}
                {{ Form::textarea('resum_es', null, ['class' => 'form-control']) }}
            </div>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('ordre', 'Ordre') }}
        {{ Form::text('ordre', null, ['class' => 'form-control', 'required' => 'required']) }}
    </div>

    {{ Form::submit('Crea la categoria', ['class' => 'btn btn-primary']) }}

    {{ Form::close() }}

@stop
