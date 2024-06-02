@extends('layouts.admin')

@section('content')

	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
		<h1>Opcions</h1>
	</div>

	{{ Form::open(array('route' => 'admin.options.update','role'=>'form')) }}

		<div class="form-group mb-5">
			{{ Form::label('pdf_condicions','Condicions de compra (PDF comanda)') }}
			{{ Form::textarea('pdf_condicions',$values["pdf_condicions"],array('class'=>"form-control",'rows'=>12)) }}
		</div>

		<div class="form-group mb-5">
			{{ Form::label('email_comanda','Text email comanda client') }}
			{{ Form::textarea('email_comanda',$values["email_comanda"],array('class'=>"form-control",'rows'=>12)) }}
			<p class="mt-1 form-text text-muted">Camps reservats: <strong>[nom_client]</strong>, <strong>[link_pdf]</strong>
			</p>
		</div>


		<div class="form-group mb-5">
			{{ Form::label('organitzadors','Apartat "Com puc vendre entrades?"') }}
			{{ Form::textarea('organitzadors',$values["organitzadors"],array('class'=>"form-control",'rows'=>12)) }}
		</div>


		<div class="form-group mb-5">
			{{ Form::label('condicions','Condicions d\'ús (web)') }}
			{{ Form::textarea('condicions',$values["condicions"],array('class'=>"form-control",'rows'=>12)) }}
		</div>

		<div class="form-group mb-5">
			{{ Form::label('politica-privacitat','Protecció de dades') }}
			{{ Form::textarea('politica-privacitat',$values["politica-privacitat"],array('class'=>"form-control",'rows'=>12)) }}
		</div>

		<div class="form-group mb-5">
			{{ Form::label('email_solicitud_alta','Email de sol·licitud alta activitat') }}
			{{ Form::textarea('email_solicitud_alta',$values["email_solicitud_alta"],array('class'=>"form-control",'rows'=>12)) }}
		</div>

		<hr>

		{{ Form::button('Guarda els canvis',['class'=>'btn btn-primary','type'=>'submit'])}}

	{{ Form::close() }}

@stop