@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'products'])


    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
        <h1>{{ $product->title }}</h1>
        @include('admin.menus.productes', ['product' => $product, 'active' => 'info'])
    </div>

    {{ Form::model($product, ['route' => ['admin.product.update', $product->id], 'class' => 'form-horizontal mt-5']) }}


    <div class="row">

        <div class="form-group col-6">
            {{ Form::label('name', 'URL del producte') }}
            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'url-del-producte']) }}
            <small class="form-text text-muted">En minúscules, sense accents ni espais. Pot contenir guions</small>
        </div>

        <div class="form-group col-4">
            {{ Form::label('user_id', 'Organitzador') }}
            {{ Form::select('user_id', $entities, request()->old('user_id'), ['class' => 'custom-select']) }}
        </div>

        <div class="form-group col-2">
            {{ Form::label('ordre', 'Ordre') }}
            {{ Form::text('ordre', null, ['class' => 'form-control']) }}
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
            {{ Form::select('categoria_id', $categories, null, ['class' => 'custom-select']) }}

        </div>

        <div class="form-group col-4">
            {{ Form::label('espai_id', 'Espai') }}
            {{ Form::select('espai_id', $venues, null, ['class' => 'custom-select']) }}
            <small class="form-text text-muted">Escollint un espai el producte serà un esdeveniment amb entrades
                numerades.</small>
        </div>

    </div>

    <div class="row">

        <div class="form-group col-2">
            {{ Form::label('minimEntrades', 'Mínim entrades') }}
            {{ Form::number('minimEntrades', null, ['class' => 'form-control']) }}
            <small class="form-text text-muted">Mínim d'entrades que s'han de reservar per comanda.</small>
        </div>
        <div class="form-group col-2">
            {{ Form::label('maximEntrades', 'Màxim entrades') }}
            {{ Form::number('maximEntrades', null, ['class' => 'form-control']) }}
            <small class="form-text text-muted">Màxim d'entrades que es poden reservar per comanda.</small>
        </div>
        <div class="form-group col-2">

            {{ Form::label('limitHores', 'Tancament venda') }}
            <div class="input-group">
                {{ Form::number('limitHores', null, ['class' => 'form-control', 'step' => '0.01']) }}
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
                <label class="custom-control-label" for="checkPack">És un pack <small class="form-text text-muted">El
                        producte estarà compost de varis productes</small></label>
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

    <div class="row mt-5">
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
                        {{ Form::textarea('descripcio_ca', null, ['class' => 'form-control', 'rows' => 8]) }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('horaris_ca', 'Horaris i informació d\'interès') }}
                        {{ Form::textarea('horaris_ca', null, ['class' => 'form-control', 'rows' => 8]) }}
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
                    <div class="form-group">
                        {{ Form::label('horaris_es', 'Horaris i informació d\'interès') }}
                        {{ Form::textarea('horaris_es', null, ['class' => 'form-control']) }}
                    </div>
                </div>
            </fieldset>

        </div>
    </div>

    <p class="text-right my-4">
        <a href="{{ route('admin.product.preview-pdf', ['id' => $product->id]) }}" class="btn btn-outline-primary mr-2"
            target="_blank"><i class="fa-solid fa-file-pdf"></i> Previsualitza PDF entrada</a>
        {{ Form::submit('Guarda els canvis', ['class' => 'btn btn-primary']) }}
    </p>

    {{ Form::close() }}


    <div class="row">

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="my-0">Foto</h3>
                </div>
                <div class="card-body">
                    @if (file_exists('images/' . $product->name . '.jpg'))
                        <div class="form-group">
                            <label class="control-label">Imatge actual</label><br>
                            <img src="{{ url('img/medium/' . $product->name) }}.jpg" class="img-fluid">
                        </div>
                    @else
                        <p>El producte no té foto</p>
                    @endif

                    {{ Form::open(['route' => ['admin.product.image', $product->id], 'files' => true, 'method' => 'post', 'class' => 'mb-3']) }}

                    <div class="input-group">
                        <div class="custom-file">
                            {{ Form::file('image', ['class' => 'custom-file-input', 'id' => 'imageFile']) }}
                            <label class="custom-file-label" for="imageFile">Puja una imatge nova</label>
                        </div>
                        <div class="input-group-append">
                            {{ Form::button('<i class="fas fa-upload"></i>  Puja imatge', ['class' => 'btn btn-primary', 'type' => 'submit']) }}
                        </div>
                    </div>

                    {{ Form::close() }}

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="my-0">Imatge de capçalera</h3>
                </div>
                <div class="card-body">
                    @if (file_exists('images/header/' . $product->name . '.jpg'))
                        <div class="form-group">
                            <label class="control-label">Imatge actual</label><br>
                            <img src="{{ url('img/medium/header/' . $product->name) }}.jpg" class="img-fluid">
                        </div>
                    @else
                        <p>No s'ha pujat cap imatge de capçalera. Es farà servir la imatge per defecte.</p>
                    @endif

                    {{ Form::open(['route' => ['admin.product.header-image', $product->id], 'files' => true, 'method' => 'post', 'class' => 'mb-3']) }}

                    <div class="input-group">
                        <div class="custom-file">
                            {{ Form::file('image', ['class' => 'custom-file-input', 'id' => 'imageFile']) }}
                            <label class="custom-file-label" for="imageFile">Puja una imatge nova</label>
                        </div>
                        <div class="input-group-append">
                            {{ Form::button('<i class="fas fa-upload"></i>  Puja imatge', ['class' => 'btn btn-primary', 'type' => 'submit']) }}
                        </div>
                    </div>

                    {{ Form::close() }}

                </div>
            </div>
        </div>

    </div>

    {{ Form::open(['route' => ['admin.product.destroy', $product->id], 'method' => 'delete']) }}
    <div class="form-group mt-5 text-right">
        {{ Form::button('<i class="fas fa-trash-alt"></i> Elimina el producte', ['class' => 'btn btn-danger', 'type' => 'submit']) }}
    </div>
    {{ Form::close() }}


@stop
