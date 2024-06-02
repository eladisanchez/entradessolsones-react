@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'rates'])

    <h1>Nova tarifa</h1>

    {{ Form::open(['route' => ['admin.rate.store']]) }}

    <div class="row">

        <div class="col-md-5">
            <div class="form-group">
                {{ Form::label('title', 'Títol Català') }}
                {{ Form::text('title', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('descripcio_ca', 'Descripció català') }}
                {{ Form::textarea('descripcio_ca', null, ['class' => 'form-control raw', 'rows' => 3]) }}
            </div>
        </div>

        <div class="col-md-5">
            <div class="form-group">
                {{ Form::label('title_es', 'Títol Castellà') }}
                {{ Form::text('title_es', null, ['class' => 'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('descripcio_es', 'Descripció castellà') }}
                {{ Form::textarea('descripcio_es', null, ['class' => 'form-control raw', 'rows' => 3]) }}
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                {{ Form::label('ordre', 'Ordre') }}
                {{ Form::text('ordre', 0, ['class' => 'form-control']) }}
            </div>
        </div>

    </div>

    {{ Form::submit('Crea la tarifa', ['class' => 'btn btn-primary']) }}

    {{ Form::close() }}

@stop
