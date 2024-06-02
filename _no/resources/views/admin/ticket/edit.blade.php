@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'products'])

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1>{{ $ticket->product->title }}</h1>
        @include('admin.menus.productes', ['product' => $ticket->product, 'active' => 'entrades'])
    </div>

    <div class="row mb-4">

        <div class="col-md-6">

            <div class="card">
                <div class="card-body">

                    <p class="h5 mt-0 mb-4">
                        Entrades per al <strong>{{ $ticket->day->format('d/m/Y') }}</strong> a les
                        <strong>{{ $ticket->hour->format('H:i') }}</strong> h
                        </strong></p>

                    {{ Form::open(['route' => ['admin.ticket.update']]) }}

                    {{ Form::hidden('product_id', $ticket->product->id) }}
                    {{ Form::hidden('day', $ticket->day->toDateString()) }}
                    {{ Form::hidden('hour', $ticket->hour->toTimeString()) }}

                    <div class="row">

                        <div class="form-group col-md-6">

                            {{ Form::label('entrades', 'Entrades disponibles') }}
                            {{ Form::text('tickets', $ticket->tickets, ['class' => 'form-control']) }}

                        </div>

                        <div class="form-group col-md-6">

                            {{ Form::label('language', 'Idioma') }}
                            {{ Form::select(
                                'language',
                                [
                                    '' => '',
                                    'ca' => 'Català',
                                    'es' => 'Castellà',
                                    'en' => 'Anglès',
                                    'fr' => 'Francès',
                                ],
                                $ticket->language,
                                ['class' => 'custom-select'],
                            ) }}

                        </div>

                        <div class="form-group">
                            {{ Form::submit('Desa els canvis', ['class' => 'btn btn-primary']) }}
                        </div>

                    </div>

                    {{ Form::close() }}

                </div>
            </div>

            <div class="form-group mt-4">

                <a href="{{ URL::previous() }}" class="btn btn-link">Torna enrere</a>

                <a href="{{ route('product', [$ticket->product->name, $ticket->day->toDateString(), $ticket->hour->toTimeString()]) }}"
                    class="btn btn-link">Reserva entrades per aquesta hora</a>

            </div>

        </div>
        <div class="col-md-6">

            @if (!$ticket->bookings())

                <div class="card">
                    <div class="card-body">

                        <div class="alert alert-warning">No hi ha entrades reservades per aquesta sessió.</div>

                        <form
                            action="{{ route('admin.ticket.destroy', [$ticket->product->id, $ticket->day->toDateString(), $ticket->hour->toTimeString()]) }}"
                            method="post">
                            @csrf
                            <button class="btn btn-danger">Elimina la sessió</button>
                        </form>

                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <p class="h5 mb-4">Hi ha <strong>{{ $ticket->bookings() }} entrades</strong> reservades per aquesta
                        sessió.
                    <p>
                    <p><a href="{{ url('/admin/excel?dia=' . $ticket->day->toDateString() . '&hora=' . $ticket->hour->toTimeString() . '&product_id=' . $ticket->product_id) }}"
                            class="btn btn-info">Descarrega l'Excel de reserves</a></p>
                </div>

                @if ($ticket->cancelled)
                    <div class="alert alert-danger">
                        <h4>Aquesta sessió està cancel·lada.</h4>
                    </div>
                @else
                    <div class="alert alert-danger">

                        <p class="h5 mb-4">Cancel·lar sessió</p>

                        <p>Si es cancel·la la sessió s'enviarà un email als usuaris que hagin comprat entrades per aquest
                            dia/hora amb un enllaç per poder generar la devolució.</p>

                        <form
                            action="{{ action('TicketController@destroy', [$ticket->producte->id, $ticket->day->toDateString(), $ticket->hour->toTimeString()]) }}"
                            method="post">
                            @csrf
                            <p><strong>Omple els següents camps per canviar de dia i hora la sessió</strong></p>
                            <div class="row mb-3">
                                <div class="col"><input type="date" class="form-control" name="new-dia"></div>
                                <div class="col"><input type="time" class="form-control" name="new-hora"></div>
                            </div>
                            <p><button class="btn btn-danger">Cancel·la la sessió</button></p>
                        </form>

                    </div>
                @endif

            @endif

        </div>

    </div>

@stop
