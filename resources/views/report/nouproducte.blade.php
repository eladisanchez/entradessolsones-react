@extends('report.base')

@section('content')


	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
		<h1>Nou producte</h1>
	</div>

		
    {{ Form::open(array('action' => 'ProductController@solicitudStore','role'=>'form')) }}

        <div class="row"><div class="col-md-9">

        <div class="form-group">
            {{ Form::label('title_ca','Títol de l\'activitat/espectacle') }}
            {{ Form::text('title_ca',NULL,array('class'=>"form-control")) }}
        </div>
        <div class="form-group">
            {{ Form::label('resum_ca','Resum') }}
            {{ Form::text('resum_ca',NULL,array('class'=>"form-control")) }}
            <small class="form-text text-muted">Màxim 100 caràcters. Apareixerà al passar per sobre de la miniatura.</small>
        </div>
        <div class="form-group">
            {{ Form::label('descripcio_ca','Descripció de l\'activitat/espectacle') }}
            {{ Form::textarea('descripcio_ca',NULL,array('class'=>"form-control")) }}
        </div>
        <div class="form-group">
            {{ Form::label('horaris_ca','Horaris') }}
            {{ Form::textarea('horaris_ca',NULL,array('class'=>"form-control")) }}
        </div>

        <hr>

        <div class="row">

            <div class="form-group col-3">
                {{ Form::label('target','Tipus de producte') }}
                {{ Form::select('target', array(
                    'individual' => 'Activitat turística',
                    'esdeveniments' => 'Esdeveniments, concerts i teatre'
                ),NULL,array('class'=>"custom-select")) }}
            </div>

            <div class="form-group col-3">
                {{ Form::label('categoria_id','Categoria') }}
                {{ Form::select('categoria_id', $categories, Input::old('categoria_id'),array('class'=>"custom-select")) }}
            </div>

            <div class="form-group col-2">
                {{ Form::label('minimEntrades','Mínim entrades') }}
                {{ Form::number('minimEntrades', 1,array('class'=>"form-control")) }}
                <small class="form-text text-muted">Mínim d'entrades que s'han de reservar per comanda.</small>
            </div>
            <div class="form-group col-2">
                {{ Form::label('maximEntrades','Màxim entrades') }}
                {{ Form::number('maximEntrades', 10,array('class'=>"form-control")) }}
                <small class="form-text text-muted">Màxim d'entrades que es poden reservar per comanda.</small>
            </div>
            <div class="form-group col-2">
                {{ Form::label('limitHores','Tancament') }}
                <div class="input-group">
                {{ Form::number('limitHores',12,array('class'=>"form-control","step"=>".01")) }}
                <div class="input-group-append">
                    <span class="input-group-text">hores</span>
                </div></div>
                <small class="form-text text-muted">Fins quantes hores abans de l'espectacle es poden adquirir entrades online</small>
            </div>

        </div>

        <hr>

        <div class="row">

            <div class="form-group col-md-6">
                {{ Form::label('espai_id','Espai') }}
                {{ Form::select('espai_id', $venues, Input::old('espai_id'),array('class'=>"custom-select")) }}
                <small class="form-text text-muted">Escollint un espai el producte serà un esdeveniment amb entrades numerades.</small>
            </div>

            <div class="form-group col-md-6">
                <label>Imatge</label>
                <div class="custom-file">
                    {{ Form::file('image',['class'=>'custom-file-input','id'=>'imageFile']) }}
                    <label class="custom-file-label" for="imageFile">Sel·lecciona un fitxer</label>
                </div>
            </div>

        </div>

        </div><div class="col-md-3">

            <div class="card mt-4"><div class="card-body">
                <p>Abans d'entrar a la plataforma es revisarà la sol·licitud.</p>
                {{ Form::button('Envia la sol·licitud <i class="fas fa-chevron-circle-right ml-1"></i>',array(
                    'class'=>'btn btn-primary btn-block',
                    'type'=>'submit'
                )) }}
            </div></div>

        </div></div>

    {{ Form::close()}}


@stop