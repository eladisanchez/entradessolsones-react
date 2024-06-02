@extends('layouts.admin')

@section('content')

    @include('admin.menus.sales', ['active' => 'bookings'])

    <div class="wrapper">

        <div class="page-header">
            <h1>Reserva</h1>
        </div>

        <div class="card">
            <div class="card-header">
                <p class="m-0"><a href="{{ route('admin.order.edit', $booking->order_id) }}">
                        <strong>{{ $booking->order->number }}</strong> - {{ $booking->order->name }} -
                        {{ $booking->product->title }}</a></p>
            </div>
            <div class="card-body">

                {{ Form::model($booking, ['route' => ['admin.booking.update', $booking->id]]) }}

                <div class="row">

                    <div class="form-group col-md-4">
                        {{ Form::label('day', 'Dia') }}
                        {{ Form::date('day', $booking->day->toDateString(), [
                            'class' => 'form-control',
                        ]) }}
                    </div>

                    <div class="form-group col-md-4">
                        {{ Form::label('hour', 'Hora') }}
                        {{ Form::time('hour', null, [
                            'class' => 'form-control',
                        ]) }}
                    </div>

                    @if ($booking->product->venue)
                        <div class="form-group col-md-2">
                            {{ Form::label('localitat', 'Localitat') }}
                            {{ Form::text('localitat', null, ['class' => 'form-control']) }}
                        </div>
                    @else
                        <div class="form-group col-md-2">
                            {{ Form::label('tickets', 'Entrades') }}
                            {{ Form::text('tickets', null, ['class' => 'form-control']) }}
                        </div>
                    @endif

                    <div class="form-group col-md-2">
                        {{ Form::label('price', 'Preu unitari') }}
                        {{ Form::text('price', null, ['class' => 'form-control']) }}
                    </div>

                </div>

                <div class="form-group">
                    {{ Form::submit('Edita', ['class' => 'btn btn-primary']) }}
                </div>

                {{ Form::close() }}

            </div>

        @stop
