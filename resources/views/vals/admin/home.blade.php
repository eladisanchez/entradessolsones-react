@extends('vals.admin.base')

@section('content')

	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
		<h1>Vals generats</h1>
	</div>

			<table class="table">
				<tr>
					<th>Codi butlleta</th>
					<th>Data</th>
					<th>Nom</th>
					<th>Email</th>
					<th>DNI</th>
					<th>Localitat</th>
					<th>Vals</th>
					<th>PDF</th>
				</tr>
				@foreach($users as $q)
					<tr>
						<td>
							<strong>{{$q->codi_butlleta}}</strong><br>
							<small>@if($q->premi) {{$q->premi}}0€ @else Sense premi @endif</small>
						 </td>
						<td>{{$q->created_at->format('d/m/Y H:i')}}h</td>
						<td>{{$q->name??''}} {{$q->cognoms}}</td>
						<td>{{$q->email}}</td>
						<td>{{$q->dni}}</td>
						<td>
							{{$q->seat}}<br>
							@if($q->solsona && !$q->premi) <span class="badge badge-warning">Opta a sorteig</span> @endif
						</td>
						<td class="small">
						@if($q->premi)
							@for ($i = 1; $i <= $q->premi; $i++)
								@if(isset($q->comercos[$i]))
								{{$i}}: {{$q->comercos[$i]['name']??'-'}}<br>
								@endif
							@endfor
						@endif
						</td>
						
						<td>
							@if($q->premi)
							<a href="{{route('vals.pdf',['codi'=>$q->codi])}}" class="btn btn-outline-primary" target="_blank"><i class="fas fa-file-pdf"></i></a>
							@endif
						</td>
					</tr>
				@endforeach
			</table>

			{{ $users->links() }}

			<p>
				<a href="{{route('vals.admin.excel')}}" class="btn btn-primary"><i class="fas fa-file-excel mr-1"></i> Excel codis comprovats</a>
				<a href="{{route('vals.admin.excelqr')}}" class="btn btn-primary"><i class="fas fa-file-excel mr-1"></i> Excel vals gastats</a>
			</p>

			<form action="{{ route('vals.storeAnonymous') }}" method="post" class="card mt-5">
				<div class="card-body">
					<h4>Generador de vals sense usuari</h4>
					@csrf
					<div class="row">
						<label for="quant" class="col-auto">Quantitat</label>
						<div class="col-auto">
						<input type="number" name="quant" value="5" step="1" min="1" max="100" class="form-control" style="width: 100px">
						</div>
						<div class="col-auto">
							<button class="btn btn-primary">Genera</button>
						</div>
					</div>
				</div>
			</form>

			<div class="card mt-4">
				<div class="card-body">
					<form action="{{ route('vals.admin.storeDates')}}" method="POST">
						@csrf
						<div class="row">
							<div class="col">
								<h4>Periode de comprovació de codis</h4>
								<div class="row">
									<div class="col">
										<label for="vals_codis_inici">Data d'inici</label>
										<input type="datetime-local" name="vals_codis_inici" class="form-control" value="{{$codis_inici}}">
									</div>
									<div class="col">
										<label for="vals_codis_fi">Data de fi</label>
										<input type="datetime-local" name="vals_codis_fi" class="form-control" value="{{$codis_fi}}">
									</div>
								</div>
							</div>
							<div class="col">
								<h4>Periode de validació de QR</h4>
								<div class="row">
									<div class="col">
										<label for="vals_qr_inici">Data d'inici</label>
										<input type="datetime-local" name="vals_qr_inici" class="form-control" value="{{$qr_inici}}">
									</div>
									<div class="col">
										<label for="vals_qr_fi">Data de fi</label>
										<input type="datetime-local" name="vals_qr_fi" class="form-control" value="{{$qr_fi}}">
									</div>
								</div>
							</div>
						</div>
						<p class="text-right mt-3"><button class="btn btn-primary">Desar</button></p>
						</div>
					</form>
				</div>
			</div>

	</div>

@stop