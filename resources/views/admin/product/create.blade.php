@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'products'])


    {{ Form::open(['action' => 'ProductController@store', 'role' => 'form']) }}

    <div class="card">

        <div class="card-header bg-secondary text-white">
            <h2 class="my-0">Nou producte</h2>
        </div>

        <div class="card-body">

            <div class="row">

                <div class="form-group col-8">
                    {{ Form::label('user_id', 'Organitzador') }}
                    {{ Form::select('user_id', $entities, Input::old('user_id'), ['class' => 'custom-select']) }}
                </div>

                <div class="form-group col-4">
                    {{ Form::label('ordre', 'Ordre') }}
                    {{ Form::text('ordre', 0, ['class' => 'form-control']) }}
                </div>

            </div>

            <div class="row">

                <div class="form-group col-4">

                    {{ Form::label('target', 'Tipus de producte') }}
                    {{ Form::select(
                        'target',
                        [
                            'individual' => 'Activitat turística',
                            'esdeveniments' => 'Esdeveniments, concerts i teatre',
                            'altres' => 'Altres activitats',
                        ],
                        null,
                        ['class' => 'custom-select'],
                    ) }}

                </div>

                <div class="form-group col-4">

                    {{ Form::label('categoria_id', 'Categoria') }}
                    {{ Form::select('categoria_id', $categories, Input::old('categoria_id'), ['class' => 'custom-select']) }}

                </div>

                <div class="form-group col-4">
                    {{ Form::label('espai_id', 'Espai') }}
                    {{ Form::select('espai_id', $venues, Input::old('espai_id'), ['class' => 'custom-select']) }}
                    <small class="form-text text-muted">Escollint un espai el producte serà un esdeveniment amb entrades
                        numerades.</small>
                </div>


            </div>

            <div class="row">

                <div class="form-group col-2">
                    {{ Form::label('minimEntrades', 'Mínim entrades') }}
                    {{ Form::number('minimEntrades', 1, ['class' => 'form-control']) }}
                    <small class="form-text text-muted">Mínim d'entrades que s'han de reservar per comanda.</small>
                </div>
                <div class="form-group col-2">
                    {{ Form::label('maximEntrades', 'Màxim entrades') }}
                    {{ Form::number('maximEntrades', 10, ['class' => 'form-control']) }}
                    <small class="form-text text-muted">Màxim d'entrades que es poden reservar per comanda.</small>
                </div>
                <div class="form-group col-2">

                    {{ Form::label('limitHores', 'Tancament venda') }}
                    <div class="input-group">
                        {{ Form::number('limitHores', 12, ['class' => 'form-control', 'step' => '.01']) }}
                        <div class="input-group-append">
                            <span class="input-group-text">hores</span>
                        </div>
                    </div>
                    <small class="form-text text-muted">Fins quantes hores abans de l'espectacle es poden adquirir entrades
                        online</small>
                </div>

                <div class="form-group col-3 mt-4 pl-3">
                    <div class="custom-control custom-checkbox mb-3">
                        {{ Form::checkbox('espack', 1, null, ['id' => 'checkPack', 'class' => 'custom-control-input']) }}
                        <label class="custom-control-label" for="checkPack">És un pack <small
                                class="form-text text-muted">El producte estarà compost de varis productes</small></label>
                    </div>
                </div>

                <div class="form-group col-3 mt-4 pl-3">
                    <div class="custom-control custom-checkbox">
                        {{ Form::checkbox('qr', 1, null, ['id' => 'checkQr', 'class' => 'custom-control-input']) }}
                        <label class="custom-control-label" for="checkQr">Entrades amb QR <small
                                class="form-text text-muted">Habilita el control per QR de les entrades</small></label>
                    </div>
                </div>

            </div>
            <div class="row border-top pt-3">

                <div class="form-group col-2">
                    {{ Form::label('validation_start', 'Inici lectura entrades') }}
                    <div class="input-group">
                        {{ Form::number('validation_start', null, ['class' => 'form-control']) }}
                        <div class="input-group-append">
                            <span class="input-group-text">minuts</span>
                        </div>
                    </div>
                    <small class="form-text text-muted">A partir de quants minuts abans de l'inici les entrades es poden
                        validar</small>
                </div>
                <div class="form-group col-2">
                    {{ Form::label('validation_end', 'Fi lectura entrades') }}
                    <div class="input-group">
                        {{ Form::number('validation_end', null, ['class' => 'form-control']) }}
                        <div class="input-group-append">
                            <span class="input-group-text">minuts</span>
                        </div>
                    </div>
                    <small class="form-text text-muted">Caducitat de les entrades a partir de l'hora d'inici</small>
                </div>
                <div class="form-group col-2">
                    {{ Form::label('aforament', 'Aforament màxim') }}
                    <div class="input-group">
                        {{ Form::number('aforament', null, ['class' => 'form-control']) }}
                        <div class="input-group-append">
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <small class="form-text text-muted">Percentatge d'aforament</small>
                </div>
                <div class="form-group col-2 mt-2">
                    <div class="custom-control custom-checkbox">
                        {{ Form::checkbox('distancia_social', 1, null, ['id' => 'distancia_social', 'class' => 'custom-control-input']) }}
                        <label class="custom-control-label" for="distancia_social">Distància social <small
                                class="form-text text-muted">Habilita el bloqueig de butaques adjacents d'una
                                comanda.</small></label>
                    </div>
                </div>
                <div class="form-group col-4">
                    {{ Form::label('lloc', 'Lloc de l\'esdeveniment / punt inicial de la visita') }}
                    {{ Form::text('lloc', null, ['class' => 'form-control']) }}
                </div>

            </div>

        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-6">

            <fieldset class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="my-0" data-toggle="collapse" data-target="#collapseCa" aria-expanded="true"
                        aria-controls="collapseCa">
                        Textos en Català
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::label('title_ca', 'Títol') }}
                        {{ Form::text('title_ca', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('resum_ca', 'Resum') }}
                        {{ Form::text('resum_ca', null, ['class' => 'form-control']) }}
                        <small class="form-text text-muted">Màxim 100 caràcters</small>
                    </div>
                    <div class="form-group">
                        {{ Form::label('descripcio_ca', 'Descripció') }}
                        {{ Form::textarea('descripcio_ca', null, ['class' => 'form-control']) }}
                    </div>
                </div>
            </fieldset>

        </div>
        <div class="col-md-6">

            <fieldset class="card">
                <div class="card-header" id="headingEs">
                    <h5 class="my-0">
                        Textos en Castellà
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        {{ Form::label('title_es', 'Títol') }}
                        {{ Form::text('title_es', null, ['class' => 'form-control']) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('resum_es', 'Resum') }}
                        {{ Form::text('resum_es', null, ['class' => 'form-control']) }}
                        <small class="form-text text-muted">Màxim 100 caràcters</small>
                    </div>
                    <div class="form-group">
                        {{ Form::label('descripcio_es', 'Descripció') }}
                        {{ Form::textarea('descripcio_es', null, ['class' => 'form-control']) }}
                    </div>
                </div>
            </fieldset>

        </div>
    </div>

    <p class="text-right my-4">{{ Form::submit('Crea el producte', ['class' => 'btn btn-primary']) }}</p>

    {{ Form::close() }}

@stop
