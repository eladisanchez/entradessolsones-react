@extends('layouts.admin')

@section('content')

    @include('admin.menus.sales', ['active' => 'bookings'])


    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1>Reserves</h1>
    </div>

    {{ Form::open(['route' => 'admin.booking.index', 'method' => 'get']) }}

    <div class="row">

        <div class="form-group col-md-4" style="position: relative">
            {{ Form::date('dia', request()->input('dia'), [
                'class' => 'date form-control',
                'placeholder' => 'Dia',
                'id' => 'datetimepicker',
            ]) }}
        </div>

        <div class="form-group col-md-4">
            {{ Form::select('id', $products, request()->input('id'), ['class' => 'custom-select']) }}
        </div>

        <div class="form-group col-md-2">
            {{ Form::button('<i class="fas fa-filter"></i> Aplica els filtres', ['class' => 'btn btn-primary btn-block', 'type' => 'submit']) }}
        </div>

        <div class="form-group col-md-2">
            <a href="{{ route('admin.booking.index') }}" class="btn btn-outline-primary btn-block">
                <i class="fas fa-eraser"></i> Reinicia
            </a>
        </div>

    </div>

    {{ Form::close() }}


    @if (isset($bookings))

        @if (request()->input('dia'))
            <h2>Reserves per al {{ request()->input('dia') }} </h2>
        @endif

        <table class="table table-striped table-condensed">

            <thead class="thead-dark">
                <tr>
                    <th>Dia</th>
                    <th>Hora</th>
                    <th></th>
                    <th>Comanda</th>
                    <th>Producte</th>
                    <th>Entrades</th>
                    <th>Lectures QR</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($bookings as $booking)
                    <tr @if ($booking->order->trashed()) class="bg-warning" @endif>

                        <td>
                            {{ $booking->day->format('d/m/Y') }}
                        </td>

                        <td>
                            {{ $booking->hour }}
                        </td>

                        <td>
                            @if (!$booking->order->trashed())
                                <a href="{{ route('order.pdf', ['session' => $booking->order->session, 'id' => $booking->order->id]) }}"
                                    class="link-pdf" target="_blank">Descarregar</a>
                            @endif
                        </td>

                        <td>
                            @if ($booking->order)
                                <a href="{{ route('admin.order.edit', $booking->order->id) }}">
                                    <strong>{{ $booking->order->number }}</strong><br>
                                    {{ $booking->order->name }}
                                </a>
                            @endif
                        </td>

                        <td>
                            {{ $booking->product->title }}
                        </td>

                        <td>
                            @if ($booking->seat)
                                <strong>{{ \App\Helpers\Common::seient($booking->seat) }}</strong><br>
                                {{ $booking->rate->title }}
                            @else
                                <strong>{{ $booking->numEntrades }} &times;</strong>
                                {{ $booking->rate->title }}
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

                        <td>
                            <a href="{{ route('admin.booking.edit', $booking->id) }}"
                                class="btn btn-primary btn-xs">Edició</a>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>

        {{ $bookings->appends(request()->except('page'))->links() }}


        <p>
            @if (request()->input('id'))
                <a href="{{ route('product', [App\Producte::findOrFail(request()->input('id'))->name, request()->input('dia')]) }}"
                    class="btn btn-outline-primary">Reserva entrades de
                    {{ App\Producte::findOrFail(request()->input('id'))->title }}</a>
            @endif
            <a href="{{ url('/admin/excel?dia=' . request()->input('dia') . '&producte_id=' . request()->input('id')) }}"
                class="btn btn-outline-primary" target="_blank">Descarrega Excel</a>

        </p>


    @endif

    </div>

@stop
