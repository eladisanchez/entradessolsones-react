@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'categories'])

    <h1>{{ $categoria->title }}</h1>

    {{ Form::model($categoria, ['action' => ['CategoriaController@update', $categoria->id]]) }}

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('title_ca', 'Títol Català') }}
                {{ Form::text('title_ca', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('resum_ca', 'Resum Català') }}
                {{ Form::textarea('resum_ca', null, ['class' => 'form-control']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('title_es', 'Títol Castellà') }}
                {{ Form::text('title_es', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('resum_es', 'Resum Castellà') }}
                {{ Form::textarea('resum_es', null, ['class' => 'form-control']) }}
            </div>
        </div>
    </div>

    <div class="form-group">
        {{ Form::label('ordre', 'Ordre') }}
        {{ Form::text('ordre', null, ['class' => 'form-control']) }}
    </div>

    {{ Form::submit('Guarda els canvis', ['class' => 'btn btn-primary']) }}

    {{ Form::close() }}

@stop
