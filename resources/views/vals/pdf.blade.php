<!doctype html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<title>Jo compro a Solsona</title>
		<style>
			body {
        font-family: 'Nunito', sans-serif;
				color: #231f20;
      }
			h1 {
				margin-top: 0;
			}
			.header {
				height: 160px;
				font-size: 12pt;
			}
			.qr {
				width: 200px;
				height: 200px;
			}
			table {
				width: 100%;
				border-top: 2px solid #231f20;
				border-bottom: 2px solid #231f20;
			}
			table h2 {
				margin: 0;
				color: #231f20;
			}
			table p {
				margin-top: 0;
			}
			table td.qrtd {
				width: 200px;
			}
			ul {
				margin: 0 0 15pt 0;
				padding: 0;
				list-style: none;
			}
			.condicions {
				font-size: 9pt;
				padding-top: 5pt;
				margin-top: 5pt;
			}
			.comercos {
				font-size: 11px;
				line-height: 16px;
			}
			.comercos span, {
				margin-right: 5px;
				color: #555;
				font-size: 10px;
			}
			.comercos strong {
				margin-right: 5px;
			}
			.page-break {
				page-break-after: always;
			}
			table p {
				margin: 0;
			}
			.quarto {
				display: inline-block;
				padding: 3px 6px;
				background: #231f20;
				color: #FFF;
				border-radius: 8px;
			}
			h2 {
				margin: 0;
			}
			.logo {
				width: 100px;
				height: auto;
				float: left;
				margin-right: 20px;
			}
		</style>
	</head><body>

		@for ($i = 1; $i <= $qr->premi; $i++)
			<div class="header">
				<img src="{{url('vals/jocomproasolsona.png')}}" class="logo">
				<h1>Jo compro a Solsona</h1>
				<p>Aquests són els teus {{$qr->premi}} codis que pots fer servir als comerços adherits a la campanya "Jo compro a Solsona". Són personals i intransferibles.</p>
				
			</div>
	
			<table><tr>
				<td class="qrtd">
					<img src="data:image/png;base64, {!!$qr->qrimage($i)!!}" class="qr" style="margin: 20px; height: 120px; width: 120px;">
				</td>
				<td>
					<p><span class="quarto">Un quarto</span></p>
					<h2>Val de 10 €</h2>
					<p style="font-family: monospace">{{$qr->codi}}/{{$i}}</p>
					@if($qr->name)
					<p><strong>{{$qr->name}} {{$qr->cognoms}}</strong> - DNI: {{$qr->dni}}</p>
					@endif
				</td>
			</tr></table>

			<h4>Aquest val és vàlid fins al 24 de desembre de 2023 als següents comerços:</h4>
			<div class="comercos">
				@php $sector = ''; @endphp
				@foreach($comercos as $comerc)
					@if($sector != $comerc->sector)
						@php $sector = $comerc->sector; @endphp
						<strong>{{$sector}}: </strong>
					@endif
					<span>{{$comerc->name}}</span>
				@endforeach
			</div>

			<div class="condicions">
				<h4>Condicions</h4>
				<p>1. Els vals es poden gastar en els comerços i establiments locals adherits a la campanya fins al 24 de desembre del 2023 (inclòs).</p>
				<p>2. Els vals un cop adquirits no es podran retornar ni canviar novament a euros. Tampoc es reemplaçaran en cas de pèrdua o robatori.</p>
				<p>3. Els vals que no s'hagin bescanviat als comerços i establiments per béns o serveis fins al 24 de desembre del 2023 (inclòs) quedaran sense efecte i sense possibilitat ni de reintegrament ni d'ús en campanyes posteriors.</p>
				<p>4. No es podran fer devolucions de productes adquirits en vals ni en euros ni en vals. El producte haurà de ser bescanviat per un altre producte al mateix establiment. L'establiment podrà emetre un val de compra propi de l'establiment si ho creu oportú.</p>
			</div>

			<p><a href="https://jocomproasolsona.cat">jocomproasolsona.cat</a></p>

			@if($i<2)<div class="page-break"></div>@endif

		@endfor	</body></html>