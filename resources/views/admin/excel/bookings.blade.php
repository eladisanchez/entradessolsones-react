@isset($bookings)
	<table class="table">
		<tr>
			<th>Data</th>
			<th>Producte</th>
            <th>Productes<th>
		</tr>
		@foreach($bookings as $item)
		<tr>
			<td>{{$item->created_at}}</td>
			<td>{{$item->product->title}}</td>
            <td>{{$item->product->target}}</td>
		</tr>
		@endforeach
	</table>
	@endisset