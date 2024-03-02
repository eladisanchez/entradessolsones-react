@extends('layouts.admin')

@section('content')


	<h1>Codis promocionals</h1>

	@if(count($coupons))

		<table class="table table-sm">

			<thead><tr>
				<th scope="col" width="130px">Descompte</th>
				<th scope="col">Productes vàlids</th>
				<th scope="col">Validesa</th>
				<th scope="col">&nbsp;</th>
			</tr></thead>

			<tbody>

			@foreach($coupons as $coupon=>$items)

				<tr class="bg-secondary text-light">
					<th>&nbsp;</th>
					<th colspan="2" style="vertical-align: middle">
						{{ $coupon }}
					</th>
					<td class="text-right">
						{{Form::open(array('route'=>'admin.coupon.destroy-all','method'=>'delete'))}}
						{{Form::hidden('id_codi',$coupon)}}
						{{Form::button('<i class="fas fa-minus-circle"></i> Elimina el codi',array('class'=>'btn btn-danger btn-sm','type'=>'submit'))}}
						{{Form::close()}}
					</td>
				</tr>

				@foreach($items as $item)

				<tr>
					<th class="bg-light text-center">
						{{$item->descompte}}%
					</th>
					<td>
						@if(isset($item->product)) <strong>{{$item->product->title_ca}}</strong> / <span class="text-muted">{{$item->rate->title_ca}}</span></small> @endif
					</td>
					<td>
						{{{ date('d-m-Y', strtotime($item->validesa)) }}}
					</td>
					<td class="text-right">
						{{Form::open(array('route'=>'admin.coupon.destroy','method'=>'delete'))}}
						{{Form::hidden('id_codi',$item->id)}}
						{{Form::button('<i class="fas fa-minus-circle"></i> Elimina el descompte',array('class'=>'btn btn-outline-danger btn-sm','type'=>'submit'))}}
						{{Form::close()}}
					</td>
				</tr>

				@endforeach
				
			@endforeach

		</tbody>

		</table>


	@endif


	<div class="card">

		<div class="card-header">
			<h3 class="my-0">Nou codi</h3>
		</div>
		
		<div class="card-body">

		{{Form::open()}}

			<div class="row">

			<div class="col-md-4">
				<div class="form-group">
					{{ Form::label('codi','Codi de descompte') }}
					{{ Form::text('codi',NULL,array('class'=>"form-control")) }}
				</div>
				<div class="form-group">
					{{ Form::label('descompte','Descompte') }}
					<div class="input-group">
						{{ Form::number('descompte',NULL,array(
							'class'=>"form-control",
							'aria-describedby'=>"inputGroupPrepend",
							'max' => 100,
							'min'=>0
						)) }}
						<div class="input-group-append">
								<span class="input-group-text" id="inputGroupPrepend">%</span>
						</div>
						</div>
				</div>
				<div class="form-group" style="position: relative">
					{{ Form::label('validesa','Validesa') }}
					{{ Form::date('validesa',NULL,array(
						'class'=>"form-control")
					) }}
				</div>
			</div>

			<div class="col-md-8">
				<div class="form-group">
					{{ Form::label('tarifes',"Tarifes dels productes on es podrà aplicar el descompte") }}
					{{ Form::select('tarifes[]', $products, NULL, array('class'=>"form-control custom-select",'multiple' => 'multiple','size'=>9)) }}
				</div>
			</div>

			</div>
			
			<div class="form-group mb-0">
				{{Form::submit('Crea codi',array('class'=>'btn btn-primary'))}}
			</div>

		{{Form::close()}}


	</div></div>

@stop