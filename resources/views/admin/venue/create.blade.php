@extends('layouts.admin')

@section('content')

    @include('admin.menus.entrades', ['active' => 'venues'])


    <h2>Nou espai</h2>

    <div class="row">

        <div class="col-sm-8">

            <table id="drawing-table"></table>
            <div class="escenari">ESCENARI</div>

        </div>

        <div class="col-sm-4">

            {{ Form::open(['action' => 'EspaiController@store', 'role' => 'form', 'class' => 'form']) }}

            <div class="form-group">
                {{ Form::label('name', 'Nom de l\'espai', ['class' => 'control-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control']) }}
            </div>

            <div class="form-group">
                {{ Form::label('adreca', 'Adreça', ['class' => 'control-label']) }}
                {{ Form::text('adreca', null, ['class' => 'form-control']) }}
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    {{ Form::checkbox('escenari', 1, null, ['id' => 'check-escenari', 'class' => 'custom-control-input']) }}
                    <label class="custom-control-label" for="check-escenari">Mostrar escenari</label>
                </div>
            </div>

            <div class="card mb-3">

                <div class="card-header">Generador de seients</div>

                <div class="card-body">

                    <div class="row">

                        <div class="form-group col-6">
                            <label for="fila">Fila</label>
                            <input type="number" min="0" max="100" id="fila" value="1"
                                class="form-control">
                        </div>

                        <div class="form-group col-6">
                            <label for="seient">Seient</label>
                            <input type="number" min="1" max="100" id="seient" value="1"
                                class="form-control">
                        </div>

                    </div>

                    <div class="form">

                        <div class="form-group">
                            <label for="zona">Zona</label>
                            <select id="zona" class="form-control custom-select">
                                <option value="1">Zona 1</option>
                                <option value="2">Zona 2</option>
                                <option value="3">Zona 3</option>
                                <option value="4">Zona 4</option>
                            </select>
                        </div>

                    </div>


                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" value="1" name="parells" id="parells" class="custom-control-input">
                        <label class="custom-control-label" for="parells">Interval x2</label>
                    </div>

                </div>

                <div class="card-footer">

                    <div class"controls_pintar_mapa" role="group">
                        <button id="desfer" class="btn btn-outline-secondary btn-sm"><i class="fas fa-undo"></i> Desfés
                            l'últim pas</button>
                        <button id="neteja" class="btn btn-warning btn-sm"><i class="fas fa-eraser"></i> Reinicia
                            plànol</button>
                    </div>

                </div>
            </div>


            {{ Form::hidden('localitats', null, ['id' => 'localitats']) }}

            <div class="alert alert-warning">Entrades generades: <span id="count-seients">0</span></div>

            <div class="form-group">
                {{ Form::button(' <i class="fas fa-save"></i> Crea l\'espai', [
                    'class' => 'btn btn-primary btn-block btn-lg',
                    'type' => 'submit',
                ]) }}
            </div>



            {{ Form::close() }}

        </div>

    </div>


    <script>
        seientsGuardats = [];
    </script>

@stop
