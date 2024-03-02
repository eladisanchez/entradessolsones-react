@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'products'])

    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1>{{ $product->title }}</h1>
        @include('admin.menus.productes', ['product' => $product, 'active' => 'entrades'])
    </div>


    @if (count($days))

        <h2>Entrades disponibles</h2>

        <table class="table table-striped table-condensed">

            <tr>
                <th>Dia</th>
                <th>Sessions</th>
                <th>Entrades disponibles</th>
                <th>&nbsp;</th>
            </tr>

            @foreach ($days as $day => $hours)
                <tr>
                    <td class="th">{{ $hours['day'] }}</td>
                    <td class="small">
                        @foreach ($hours['hours'] as $hour)
                            <a href="{{ route('admin.ticket.edit', [$product->id, $day, $hour['hour']]) }}"
                                class="btn @if (!$hour['cancelled']) btn-outline-secondary @else btn-outline-danger @endif">
                                <strong><i class="fas fa-ticket-alt"></i> {{ $hour['hour'] }}</strong>
                                @if ($hour['language'])
                                    ({{ $hour['language'] }})
                                @endif
                                (
                                <strong>{{ $hour['available'] }}</strong>/{{ $hour['tickets'] }})
                                @if ($hour['cancelled'])
                                    <span class="badge badge-danger">CANCEL·LAT</span>
                                @endif
                            </a>
                            @if ($product->venue)
                                <a href="{{ route('ticket.map', [$product->id, $day, $hour['hour']]) }}" class="btn btn-primary"
                                    target="_blank"><i class="fas fa-print"></i></a>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        <strong>{{ $hours['disponiblesDia'] }}</strong>/{{ $hours['entradesDia'] }}
                    </td>
                    <td class="text-right">
                        <a href="{{ route('product', [$product->name, $day]) }}"
                            class="btn btn-outline-secondary btn-sm"><i class="fas fa-external-link-alt"></i> Reserva</a>
                    </td>
                </tr>
            @endforeach

        </table>

    @endif

    {{ $tickets->links() }}

    <div class="card">
        <div class="card-header">
            <h3 class="my-0">Crea entrades</h3>
        </div>
        <div class="card-body">

            @if (!$product->venue)
                {{ Form::open(['route' => 'admin.ticket.store']) }}

                <p class="text-muted">Les entrades es generaran a partir de les sessions definides.</p>

                <div class="row">

                    <div class="form-group col-3">
                        {{ Form::label('data-inici', 'Data inicial', ['class' => 'control-label']) }}
                        {{ Form::date('data-inici', null, ['class' => 'date form-control']) }}
                    </div>

                    <div class="form-group col-3">
                        {{ Form::label('data-fi', 'Data final', ['class' => 'control-label']) }}
                        {{ Form::date('data-fi', null, ['class' => 'date form-control']) }}
                    </div>

                    <div class="form-group col-6">
                        <label>Dies de la setmana</label>
                        <div class="d-flex">
                            <div class="custom-control custom-checkbox mr-3">
                                <input class="custom-control-input" id="dia1" name="w[]" type="checkbox"
                                    value="1">
                                <label class="custom-control-label" for="dia1">Dl</label>
                            </div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input class="custom-control-input" id="dia2" name="w[]" type="checkbox"
                                    value="2">
                                <label class="custom-control-label" for="dia2">Dm</label>
                            </div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input class="custom-control-input" id="dia3" name="w[]" type="checkbox"
                                    value="3">
                                <label class="custom-control-label" for="dia3">Dc</label>
                            </div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input class="custom-control-input" id="dia4" name="w[]" type="checkbox"
                                    value="4">
                                <label class="custom-control-label" for="dia4">Dj</label>
                            </div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input class="custom-control-input" id="dia5" name="w[]" type="checkbox"
                                    value="5">
                                <label class="custom-control-label" for="dia5">Dv</label>
                            </div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input class="custom-control-input" id="dia6" name="w[]" type="checkbox"
                                    value="6">
                                <label class="custom-control-label" for="dia6">Ds</label>
                            </div>
                            <div class="custom-control custom-checkbox mr-3">
                                <input class="custom-control-input" id="dia7" name="w[]" type="checkbox"
                                    value="0">
                                <label class="custom-control-label" for="dia7">Dg</label>
                            </div>
                        </div>
                    </div>

                </div>

                <!--
       <hr>
       <p class="text-muted">Si s'omplen els següents camps no es tindran en compte les sessions definides per al producte:</p>
       -->

                <div class="row">

                    <div class="form-group col-4">
                        {{ Form::label('hour', 'Hora', ['class' => 'control-label']) }}
                        {{ Form::time('hour', null, ['class' => 'hour form-control']) }}
                    </div>

                    <div class="form-group col-4">
                        {{ Form::label('entrades', 'Entrades per sessió', ['class' => 'control-label']) }}
                        {{ Form::text('entrades', null, ['class' => 'form-control']) }}
                    </div>

                    <div class="form-group col-4">
                        {{ Form::label('language', 'Idioma', ['class' => 'control-label']) }}
                        {{ Form::select(
                            'language',
                            [
                                '' => '',
                                'ca' => 'Català',
                                'es' => 'Castellà',
                                'en' => 'Anglès',
                                'fr' => 'Francès',
                            ],
                            null,
                            ['class' => 'custom-select'],
                        ) }}
                    </div>

                </div>


                {{ Form::hidden('producte_id', $product->id) }}

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        {{ Form::checkbox('neteja', 1, null, [
                            'class' => 'custom-control-input',
                            'id' => 'checkElimina',
                        ]) }}
                        <label class="custom-control-label" for="checkElimina">Elimina totes les entrades creades
                            actualment.</label>
                    </div>
                </div>

                <div class="form-group">
                    {{ Form::submit('Crea les disponibilitats', ['class' => 'btn btn-primary']) }}
                </div>

                {{ Form::close() }}
            @else
                {{ Form::open(['action' => 'TicketController@store']) }}

                <p>Les entrades a la venda es crearan a partir de les localitats definides al plànol de
                    <strong>{{ $product->espai->name }}</strong></p>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('data-inici', 'Data', ['class' => 'control-label']) }}
                            {{ Form::date('data-inici', null, ['class' => 'date form-control']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('hour', 'Hora', ['class' => 'control-label']) }}
                            {{ Form::time('hour', null, ['class' => 'hour form-control']) }}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            {{ Form::label('&nbsp;') }}
                            {{ Form::submit('Crea les disponibilitats', ['class' => 'btn btn-primary btn-block']) }}
                        </div>
                    </div>
                </div>

                {{ Form::hidden('producte_id', $product->id) }}

                {{ Form::close() }}
            @endif

        </div>
    </div>

@stop
