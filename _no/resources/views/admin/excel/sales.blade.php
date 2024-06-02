@isset($sales)
	<table class="table">
		<tr>
			<th>Data</th>
			<th>Total</th>
            <th>Productes<th>
		</tr>
		@foreach($sales as $item)
		<tr>
			<td>{{$item->created_at}}</td>
			<td>{{$item->total}}</td>
            <td>{{$item->bookings_count}}</td>
            <td>{{$item->bookings_sum_tickets}}</td>
		</tr>
		@endforeach
	</table>
	@endisset