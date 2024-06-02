@isset($cp)
	<table class="table">
		<tr>
			<th>Codi postal</th>
			<th>Quantitat</th>
			<th>Població</th>
			<th>Província</th>
		</tr>
		@foreach($cp as $item)
		<tr>
			<td>{{$item->cp}}</td>
			<td>{{$item->quantitat}}</td> 
			<td>{{$item->poblacio}}</td> 
			<td>{{$item->provincia}}</td>
		</tr>
		@endforeach
	</table>
@endisset