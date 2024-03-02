@extends('vals.admin.base')

@section('content')

	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
		<h1>Comerços</h1>
	</div>

			<form action="{{route('vals.admin.comercos.update',['id'=>$comerc->id])}}" class="card" method="post">
				<div class="card-header">
					<h4 class="m-0">Edició "{{$comerc->name}}"</h4>
				</div>
				<div class="card-body">
					@csrf
					<div class="row">
						<p class="form-group col-9">
							<label for="c-nom">Nom</label>
							<input type="text" name="nom" class="form-control" id="c-nom" value="{{$comerc->name}}">
						</p>
						<p class="form-group col-md-3">
							<label for="c-clau">Clau</label>
							<input type="text" name="clau" class="form-control" id="c-clau"  value="{{$comerc->clau}}">
						</p>
						<p class="form-group col-md-4">
							<label for="c-adreca">Adreça</label>
							<input type="text" name="adreca" class="form-control" id="c-adreca" value="{{$comerc->adreca}}">
						</p>
						<p class="form-group col-md-4">
							<label for="c-sector">Sector</label>
							<input type="text" name="sector" class="form-control" id="c-sector"  value="{{$comerc->sector}}">
						</p>
						<p class="form-group col-md-4">
							<label for="c-web">Web</label>
							<input type="url" name="web" class="form-control" id="c-web" value="{{$comerc->web}}">
						</p>
						
						<p class="form-group col-12">
							<button class="btn btn-primary">Envia</button>
						</p>
					</div>
				</div>
			</form>

		</div>

@stop