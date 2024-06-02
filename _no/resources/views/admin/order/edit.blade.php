@extends('layouts.admin')

@section('content')

    @include('admin.menus.sales', ['active' => 'orders'])


    <h1>Comanda</h1>

    <div class="card">

        <div class="card-header">
            <h2 class="m-0"><strong>{{ $order->number }}</strong> - {{ $order->created_at->format('d/m/Y') }}</h2>
        </div>

        <div class="card-body">

            {{ Form::model($order, ['route' => ['admin.order.update', $order->id]]) }}

            <div class="row">

                <div class="form-group col-md-6">
                    {{ Form::label('name', 'Nom', ['class' => 'control-label']) }}
                    {{ Form::text('name', null, ['class' => 'form-control']) }}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('email', 'Email', ['class' => 'control-label']) }}
                    {{ Form::text('email', null, ['class' => 'form-control']) }}
                </div>
                <div class="form-group col-md-4">
                    {{ Form::label('tel', 'Telèfon', ['class' => 'control-label']) }}
                    {{ Form::text('tel', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group col-md-8">
                    {{ Form::label('observacions', 'Observacions', ['class' => 'control-label']) }}
                    {{ Form::text('observacions', null, ['class' => 'form-control']) }}
                </div>

                <div class="form-group col-md-4">
                    {{ Form::label('payment', 'Mètode de pagament', ['class' => 'control-label']) }}
                    {{ Form::select(
                        'payment',
                        [
                            'card' => 'Tarjeta de crèdit',
                            'credit' => 'Reserva',
                        ],
                        null,
                        ['class' => 'form-control custom-select', 'disabled' => 'disabled'],
                    ) }}
                </div>

                <div class="form-group col-md-4">
                    {{ Form::label('paid', 'Estat de pagament', ['class' => 'control-label']) }}
                    {{ Form::select(
                        'paid',
                        [
                            0 => 'Pendent',
                            1 => 'Pagat',
                            2 => 'Error',
                        ],
                        null,
                        ['class' => 'form-control custom-select'],
                    ) }}
                </div>

                <div class="form-group col-md-4">
                    {{ Form::label('total', 'Preu total comanda', ['class' => 'control-label']) }}
                    {{ Form::text('total', null, ['class' => 'form-control']) }}
                </div>

            </div>

            <div class="form-group">
                {{ Form::submit('Actualitza', ['class' => 'btn btn-primary']) }}
            </div>

            {{ Form::close() }}

            <hr>

            <h2>Reserves de la comanda</h2>

            <table class="table table-striped table-condensed">

                <thead class="thead-dark">
                    <tr>
                        <th>Hora</th>
                        <th>Producte</th>
                        <th>Rate</th>
                        <th>Entrades</th>
                        <th>Preu unitari</th>
                        <th>Lectures QR</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->bookings as $booking)
                        <tr>

                            <td>
                                @if ($booking->hour)
                                    {{ $booking->hour }}
                                @endif
                            </td>

                            <td>
                                @if ($booking->hour)
                                    {{ $booking->product->title }}
                                @else
                                    <strong>{{ $booking->product->title }}</strong>
                                @endif
                            </td>

                            <td>
                                {{ $booking->rate->title }}
                            </td>

                            <td>
                                {{ $booking->tickets }}
                            </td>

                            <td>
                                @if ($booking->price)
                                    {{ $booking->price }} €
                                @endif
                            </td>

                            <td>
                                @if (count($booking->scans))
                                    @foreach ($booking->scans as $scan)
                                        <span class="badge badge-success">{{ $scan->scan_id }}</span>
                                    @endforeach
                                @else
                                    <span class="badge badge-danger">No llegit</span>
                                @endif
                            </td>

                            <td class="text-right">
                                <a href="{{ route('admin.booking.edit', $booking->id) }}"
                                    class="btn btn-primary btn-xs">Edició</a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>


        </div>
    </div>

    <p>
        {{ Form::open(['route' => ['admin.order.destroy', $order->id], 'method' => 'delete']) }}
        {{ Form::submit('Elimina la comanda sencera', ['class' => 'btn btn-danger']) }}
        {{ Form::close() }}
    </p>



@stop
