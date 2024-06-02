@extends('layouts.admin')

@section('content')

	
	<div class="mb-5">

		<div class="row">

			<div class="col-md-4">
				<div class="card">
					<div class="card-header">Fulla de reserves</div>
					<div class="card-body">
						<form action="{{route('admin.analytics')}}" method="get" id="formdies" target="_blank">
							<div class="form-group">
								<label for="data-inici">Data d'inici</label>
								<input type="date" name="inici" value="{{\Carbon\Carbon::now()->addDays(-7)->format('Y-m-d')}}" class="date form-control" placeholder="Data inici">
							</div>
							<div class="form-group">
								<label for="data-fi">Data fi</label>
								<input type="date" name="fi" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}" class="date form-control" placeholder="Data fi">
							</div>
							<div class="form-group">
								<button class="btn btn-primary btn-block"><i class="fas fa-file-excel"></i> Descarrega Excel</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div class="col-md-8">
				<div class="row">

					<div class="col-6 mb-4"><div class="card">
						<div class="card-header bg-light"><h5 class="card-title m-0">Entrades en venda</h5></div>
						<div class="card-body">
							<p class="lead m-0"><?php echo DB::table('products_tickets')->where('day','>=',date('Y-m-d'))->sum('tickets'); ?></p>
						</div>
					</div></div>
					<div class="col-6 mb-4"><div class="card">
						<div class="card-header bg-light">
							<h5 class="card-title m-0">Productes</h5>
						</div>
						<div class="card-body">
							<p class="lead m-0"><?php echo DB::table('products')->count(); ?></p>
						</div>
					</div></div>
					<div class="col-6 mb-4"><div class="card">
						<div class="card-header bg-light">
								<h5 class="card-title m-0">Comandes</h5>
						</div>
						<div class="card-body">
							<p class="lead m-0"><?php echo App\Models\Order::count(); ?></p>
						</div>
					</div></div>
					<div class="col-6 mb-4"><div class="card">
						<div class="card-header bg-light">
							<h5 class="card-title m-0">Total vendes</h5>
						</div>
						<div class="card-body">
							<p class="lead m-0">{{ number_format(App\Models\Order::where('paid',1)->orWhere('payment','credit')->sum('total'),2,',','.') }} €</p>
						</div>
					</div>

				</div>
			</div>

		</div>


		<p class="mt-5"><a href="{{url('admin/vals')}}" class="btn btn-outline-primary">Jo compro a Solsona</a>

	</div>

@stop