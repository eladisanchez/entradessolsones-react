@if($cat->products->count())

	<h3 id="cat{{ $cat->id }}" class="categoria">{{ $cat->title }}</h3>

	<div class="resum-categoria">
		{!! $cat->resum !!}
	</div>

	<div class="row my-5">

		@foreach($cat->products as $item)

			@include('components.thumbnail',array('item'=>$item))

		@endforeach

	</div>

@endif