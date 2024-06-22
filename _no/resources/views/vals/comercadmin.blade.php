<!doctype html>
<html lang="{{LaravelLocalization::setLocale()}}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		@section('head')
		<title>Jo compro a Solsona</title>
		@show
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="{{ asset('css/main.css') }}">
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,900,900i" rel="stylesheet">
		<meta name="facebook-domain-verification" content="s4v5q53w9uodaq2rr8zcx86rlzrb22" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;900&display=swap" rel="stylesheet">
    

<style>
    body,h1,h2,h3,h4 {
      font-family: 'Nunito',sans-serif;
    }
  </style>
	</head>

	<body class="{{ $class??'page' }}">

    <div class="container pt-5" style="width: 600px;">
			
			@isset($comerc)

				<div class="row mb-4">
					<div class="col">
						<h2 class="m-0">{{$comerc->name}}</h2>
					</div>
					<div class="col-auto text-right">
						<a href="{{route('vals.comerclogout')}}"><i class="fas fa-sign-out-alt mr-2"></i> Surt</a>
					</div>
				</div>
				<table class="table">
					<tr>
						<th></th>
						<th>Data i hora</th>
						<th>Client</th>
					</tr>
					@php $i=1 @endphp
					@foreach($comerc->qr as $qr)
						<tr>
							<td>{{$i}}</td>
							<td style="width: 180px;"><strong>{{$qr->created_at->format('d/m/Y')}}</strong>
							{{$qr->created_at->format('H:i')}}</td>
							<td>{{$qr->usuari->name}} {{$qr->usuari->cognoms}}</td>
							@php $i++ @endphp
					@endforeach
				</table>

			@else 

			<form action="{{route('vals.comerclogin')}}" method="post" class="container" style="width: 400px;">
				@if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{session('error')}}</div>
      	@endif
				<h2>Accés comerços</h2>
				@csrf
				<div class="row">
					<div class="col">
						<input type="text" class="form-control form-control-lg" name="clau" maxlength="4" minlength="4">
					</div>
					<div class="col-auto">
						<button class="btn btn-lg btn-primary">Accedeix</button>
					</div>
			</form>

			@endif
      
    </div>

    <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous"></script>

  </body>
</html>