<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Ensolsona't</title>
		@vite('resources/scss/admin/app.scss')
	</head>

	<body class="admin">

		<nav class="navbar navbar-expand-lg navbar-dark bg-secondary"><div class="container">

			<a class="navbar-brand" href="{{ url('/admin') }}">Jo compro a Solsona</a>

			<ul class="navbar-nav ml-auto">
				<li class="nav-item @if($menu=='vals') active @endif">
					<a href="{{ route('vals.admin.index') }}" class="nav-link"> Vals generats</a>
				</li>
				<li class="nav-item @if($menu=='comercos') active @endif">
					<a href="{{ route('vals.admin.comercos.index') }}" class="nav-link"> Comerços</a>
				</li>
				<li class="nav-item">
					<a href="{{ route('logout') }}" class="nav-link"><i class="fas fa-sign-out-alt"></i> Surt</a>
				</li>
			</ul>

		</div></nav>

		@if(Session::get('message'))
			<div class="container">
				<div class="alert alert-warning">{!! Session::get('message') !!}</div>
			</div>
		@endif

		@if ($errors->any())
			<div class="container"><div class="alert alert-danger">
				<p>Errors de validació:</p>
				<ul>
					@foreach ($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			</div>
		@endif

		<div class="container">
			@yield('content')
		</div>

		<footer class="peu">
			<div class="wrapper">
				
			</div>
		</footer>

		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
		<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
		{{-- <script src="//cdn.tinymce.com/4/tinymce.min.js"></script> --}}
		<script src="https://kit.fontawesome.com/d64d6b5aa5.js" crossorigin="anonymous"></script>

		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/locale/ca.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha18/js/tempusdominus-bootstrap-4.min.js"></script>
		<script src="{{ asset('js/admin/main.js') }}"></script>

		@yield('codispeu')

	</body>
	
</html>