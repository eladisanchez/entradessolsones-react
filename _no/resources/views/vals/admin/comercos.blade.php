@extends('vals.admin.base')

@section('content')

	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
		<h1>Comerços</h1>
	</div>

	<div class="row">

		<div class="col-md-8">

			<table class="table">
				<tr>
					<th></th>
					<th>Nom</th>
					<th>Info</th>
					<th>Clau</th>
					<th>Codis</th>
				</tr>
				@foreach($comercos as $c)
					<tr>
						<td>
							<a href="{{route('vals.admin.comercos.toggle',['id'=>$c->id])}}" class="btn btn-{{$c->actiu?'success':'outline-warning'}}">
								<i class="fa-solid fa-toggle-{{$c->actiu?'on':'off'}}"></i>
							</a>
						</td>
						<th><a href="{{route('vals.admin.comercos.edit',['id'=>$c->id])}}">{{$c->name}}</a></th>
						<td>
							<i class="fa-solid fa-link mr-2"></i> {{$c->web}}<br>
							<i class="fa-solid fa-location-dot mr-2"></i> {{$c->adreca}}<br>
							<i class="fa-solid fa-tag mr-2"></i> {{$c->sector}}</td>
						<td>{{$c->clau}}</td>
						<td>{{$c->qr_count}}</td>
					</tr>
				@endforeach
			</table>

		</div>

		<div class="col-md-4">

			<form action="{{route('vals.admin.comercos.store')}}" class="card" method="post">
				<div class="card-header">
					<h4 class="m-0">Nou comerç:</h4>
				</div>
				<div class="card-body">
					@csrf
					<p class="form-group">
						<label for="c-nom">Nom</label>
						<input type="text" name="nom" class="form-control" id="c-nom">
					</p>
					<p class="form-group">
						<label for="c-adreca">Adreça</label>
						<input type="text" name="adreca" class="form-control" id="c-adreca">
					</p>
					<p class="form-group">
						<label for="c-sector">Sector</label>
						<input type="text" name="sector" class="form-control" id="c-sector">
					</p>
					<p class="form-group">
						<label for="c-web">Web</label>
						<input type="url" name="web" class="form-control" id="c-web" placeholder="https://">
					</p>
					<p class="form-group">
						<button class="btn btn-primary">Envia</button>
					</p>
				</div>
			</form>

		</div>

	</div>

	</div>

@stop