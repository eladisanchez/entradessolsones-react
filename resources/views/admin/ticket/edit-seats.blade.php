@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'products'])

    <div class="wrapper">

        <h1>{{ $entrada->producte->title }}</h1>

        @include('admin.menus.productes', ['product' => $product, 'active' => 'entrades'])

        <p class="lead mt-4">Entrades per al {{ $entrada->dia->format('d/m/Y') }} a les {{ $entrada->hora->format('H:i') }} h
            </h2>

            <!-- Editor d'espais -->
        <div class="row">

            <div class="col-sm-8">

                <table id="drawing-table"></table>
                <div class="escenari">ESCENARI</div>

            </div>

            <div class="col-sm-4">

                <div class="card mb-3">

                    <div class="card-header">Generador de seients</div>

                    <div class="card-body">

                        <div class="row">

                            <div class="form-group col-6">
                                <label for="fila">Fila</label>
                                <input type="number" min="1" max="100" id="fila" value="1"
                                    class="form-control">
                            </div>

                            <div class="form-group col-6">
                                <label for="seient">Seient</label>
                                <input type="number" min="1" max="100" id="seient" value="1"
                                    class="form-control">
                            </div>

                        </div>

                        <div class="form-group">
                            <label for="zona">Zona</label>
                            <select id="zona" class="form-control custom-select">
                                <option value="1">Zona 1</option>
                                <option value="2">Zona 2</option>
                                <option value="3">Zona 3</option>
                                <option value="4">Zona 4</option>
                            </select>
                        </div>

                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" value="1" name="parells" id="parells"
                                class="custom-control-input">
                            <label class="custom-control-label" for="parells">Interval x2</label>
                        </div>

                    </div>

                    <div class="card-footer">

                        <div class"controls_pintar_mapa" role="group">
                            <button id="desfer" class="btn btn-outline-secondary btn-sm"><i class="fas fa-undo"></i>
                                Desfés l'últim pas</button>
                            <button id="neteja" class="btn btn-warning btn-sm"><i class="fas fa-eraser"></i> Reinicia
                                plànol</button>
                        </div>

                    </div>

                </div>

                {{ Form::open(['action' => ['TicketController@update']]) }}

                {{ Form::hidden('producte_id', $entrada->producte->id) }}
                {{ Form::hidden('dia', $entrada->dia->toDateString()) }}
                {{ Form::hidden('hora', $entrada->hora->toTimeString()) }}

                {{ Form::hidden('localitats', $entrada->seats, ['id' => 'localitats']) }}

                <p>Entrades generades: <span id="count-seients" class="text-info">0</span></p>

                <div class="form-group">
                    {{ Form::button('<i class="fas fa-save"></i> Actualitza les localitats', ['class' => 'btn btn-primary btn-block', 'type' => 'submit']) }}
                </div>

                {{ Form::close() }}

                <p class="d-flex mb-5">
                    <a href="{{ URL::previous() }}" class="btn btn-default">Enrere</a>
                    <a href="{{ action('\App\Http\Controllers\ProductController@index', [$entrada->producte->name, $entrada->dia->toDateString(), $entrada->hora->toTimeString()]) }}"
                        class="btn btn-outline-secondary">Reserva</a>
                </p>

                @if (count($entrada->seatsReservades))
                    <div class="alert alert-info">
                        <p>
                        <p class="h5 mb-4">Hi ha <strong>{{ $entrada->bookings() }} entrades</strong> reservades.
                        <p>
                        <p><a href="{{ url('/admin/excel?dia=' . $entrada->dia->toDateString() . '&hora=' . $entrada->hora->toTimeString() . '&producte_id=' . $entrada->producte_id) }}"
                                class="btn btn-info btn-block">Descarrega l'Excel de reserves</a></p>
                        </p>
                    </div>
                    @if ($entrada->cancelat)
                        <div class="alert alert-danger">
                            Sessió cancel·lada.
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <p class="h5 mb-4">Cancel·lar sessió</p>
                            <p>Si es cancel·la la sessió s'enviarà un email als usuaris que hagin comprat entrades per
                                aquest dia/hora amb un enllaç per poder generar la devolució.</p>
                            <form
                                action="{{ action('TicketController@destroy', [$entrada->producte->id, $entrada->dia->toDateString(), $entrada->hora->toTimeString()]) }}"
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
                @else
                    <form
                        action="{{ action('TicketController@destroy', [$entrada->producte->id, $entrada->dia->toDateString(), $entrada->hora->toTimeString()]) }}"
                        method="post">
                        @csrf
                        <button class="btn btn-danger">Elimina la sessió</button>
                    </form>
                @endif

            </div>

        </div>


    </div>


    <script>
        seientsGuardats = {!! $entrada->seats !!}
    </script>



@stop
