<p>S'ha efectuat la devolució de {{ $devolucio->total }} € de la comanda <strong>{{ $devolucio->comanda_id }}</strong></p>

<p>
	Producte cancel·lat: {{ $devolucio->producte->title }}<br>
	Dia i hora: {{$devolucio->dia_cancelat->format('d-m-Y')}} - {{$devolucio->dia_cancelat->format('H:i')}}<br>
	Usuari: {{ $devolucio->comanda->name }}
</p>