@extends('layouts.admin')

@section('content')

    @include('admin.menus.sales', ['active' => 'orders'])

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1>Comandes</h1>
    </div>

    @if (Session::get('message'))
        <p class="error">{{ Session::get('message') }}</p>
    @endif

    {{ Form::open(['route' => 'admin.order.index', 'method' => 'get']) }}

    <div class="row">

        <div class="form-group col-md-3" style="position: relative">
            {{ Form::date('dia', request()->input('dia'), [
                'class' => 'date form-control',
                'placeholder' => 'Dia',
                'id' => 'datetimepicker',
            ]) }}
        </div>

        <div class="form-group col-md-3">
            {{-- Form::label('id','Cerca per ID de Comanda') --}}
            {{ Form::text('id', request()->input('id'), [
                'class' => 'form-control',
                'id' => 'id',
                'placeholder' => 'ID de comanda',
            ]) }}
        </div>

        <div class="form-group col-md-2">
            {{ Form::button('<i class="fas fa-filter"></i> Aplica els filtres', ['class' => 'btn btn-primary btn-block', 'type' => 'submit']) }}
        </div>

        <div class="form-group col-md-2">
            <a href="{{ route('admin.order.index') }}" class="btn btn-outline-primary btn-block">
                <i class="fas fa-eraser"></i> Reinicia
            </a>
        </div>

        <div class="form-group col-md-2">
            <a href="{{ route('admin.order.index') }}?trashed=1" class="btn btn-outline-warning btn-block">
                <i class="fas fa-trash"></i> Eliminades
            </a>
        </div>

    </div>

    {{ Form::close() }}

    @if (request()->has('dia'))
        <h2>Comandes del dia {{ request()->input('dia') }}</h2>
    @endif

    <table class="table table-striped table-condensed table-comandes">

        <thead class="thead-dark">
            <tr>
                <th></th>
                <th>ID</th>
                <th>Data compra</th>
                <th>Dades client</th>
                <th>Productes reservats</th>
                <th>Total</th>
                <th>Pagat</th>
                <th>&nbsp;</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('order.pdf', ['session' => $order->session, 'id' => $order->id]) }}" target="_blank"
                            class="btn btn-primary"><i class="fas fa-file-pdf"></i></a>
                    </td>
                    <td>

                        <strong>{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
                        <span class="text-muted">{{ substr($order->tpv_id, -3) }}</span><br>
                        @foreach ($order->refunds as $dev)
                            <small class="badge badge-danger">Devolució {{ $dev->total }} €</small>
                        @endforeach

                    </td>
                    <td>
                        <strong>{{ $order->created_at->format('d/m/Y') }}</strong><br>
                        {{ $order->created_at->format('H:i') }} h
                    </td>
                    <td>
                        <strong>{{ $order->name }}</strong><br>
                        {{ $order->email }}<br>
                        T. {{ $order->telefon }}
                    </td>
                    <td style="font-size: 13px;">
                        @foreach ($order->bookings as $booking)
                            @if ($booking->is_pack)
                                <strong>
                            @endif
                            <a href="{{ route('admin.booking.edit', $booking->id) }}">{{ $booking->product->title }}</a>
                            @if ($booking->seat)
                                <small>{{ $booking->filaseient }}</small>
                            @endif
                            <br>
                            @if ($booking->is_pack)
                                </strong>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        {{ number_format($order->total, 2, ',', '.') }}&nbsp;€<br>
                        <small>{{ $order->pagament }}</small>
                    </td>
                    <td class="text-center">
                        @if ($order->pagament == 'targeta')
                            @if ($order->paid == 1)
                                <i class="fas fa-check text-success"></i>
                            @elseif($order->paid == 0)
                                <i class="far fa-clock text-warning"></i>
                            @elseif($order->paid == 2)
                                <i class="fas fa-times text-danger"></i>
                            @endif
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.order.edit', $order->id) }}"
                            class="btn btn-secondary btn-sm btn-block mb-0"><i class="fas fa-edit"></i> Edició</a>
                        <a href="{{ route('admin.order.edit', $order->id) }}/reenviar"
                            class="btn btn-outline-secondary btn-sm btn-block mt-0"><i class="fas fa-envelope"></i> Reenviar
                            email</a>
                    </td>
                </tr>
            @endforeach
        </tbody>

    </table>

    {{ $orders->appends(request()->query())->links() }}

    </div>

@stop
