<!doctype html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{{config('app.name')}}</title>
		@vite('resources/scss/admin/app.scss')
	</head>

	<body class="admin">

		<nav class="navbar navbar-expand-lg navbar-dark bg-secondary"><div class="container">

			<a class="navbar-brand" href="{{ url('/admin') }}">{{config('app.name')}}</a>

			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				
				@if(isset($menu))
				<ul class="navbar-nav ml-auto">
				
					<li class="nav-item @if($menu=='inici') active @endif">
						<a href="{{ url('/admin') }}" class="nav-link"><i class="fas fa-tachometer-alt mr-2"></i> Inici</a>
					</li>
					<li class="nav-item dropdown @if($menu=='products') active @endif">
						<a class="nav-link dropdown-toggle" href="{{ route('admin.product.index') }}" id="productesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i class="fas fa-ticket-alt mr-2"></i> Entrades
						</a>
						<div class="dropdown-menu" aria-labelledby="productesDropdown">
							<a class="dropdown-item" href="{{ route('admin.product.index') }}">Productes</a>
							<a class="dropdown-item" href="{{ route('admin.category.index') }}">Categories</a>
							<a class="dropdown-item" href="{{ route('admin.rate.index') }}">Tarifes</a>
							<a class="dropdown-item" href="{{ route('admin.venue.index') }}">Espais</a>
						</div>
					</li>
					<li class="nav-item dropdown @if($menu=='orders'||$menu=='bookings'||$menu=='sales') active @endif">
						<a href="{{ route('admin.order.index') }}" class="nav-link dropdown-toggle" id="comandesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-money-check-alt mr-2"></i> Vendes
						</a>
						<div class="dropdown-menu" aria-labelledby="comandesDropdown">
							<a class="dropdown-item" href="{{ route('admin.order.index') }}" class="nav-link">Comandes</a>
							<a class="dropdown-item" href="{{ route('admin.booking.index') }}" class="nav-link">Reserves</a>
							<a class="dropdown-item" href="{{ route('admin.extract.index') }}" class="nav-link">Extractes</a>
						</div>
					</li>
					<li class="nav-item @if($menu=='coupons') active @endif">
						<a href="{{ route('admin.coupon.index') }}" class="nav-link"><i class="fas fa-percent mr-2"></i> Codis</a>
					</li>
					<li class="nav-item @if($menu=='users') active @endif">
						<a href="{{ route('admin.user.index') }}" class="nav-link"><i class="fas fa-user mr-2"></i> Usuaris</a>
					</li>
					<li class="nav-item @if($menu=='options') active @endif">
						<a href="{{ route('admin.options.index') }}" class="nav-link"><i class="fas fa-cogs mr-2"></i> Opcions</a>
					</li>
					<li class="nav-item">
						<a href="{{ route('logout') }}" class="nav-link"><i class="fas fa-sign-out-alt mr-2"></i> Surt</a>
					</li>
				</ul>
				@endif

			</div>

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

		<script
			src="https://code.jquery.com/jquery-3.7.0.min.js"
			integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g="
			crossorigin="anonymous"></script>
		<script src="{{ asset('js/admin/vendor/jquery-ui.min.js') }}"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
		<script src="https://cdn.tiny.cloud/1/9jg5iq7bd9mj4sqf4nt5i147e73xqcv6vcgkjk4rvvxh7wln/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
		<script src="https://kit.fontawesome.com/d64d6b5aa5.js" crossorigin="anonymous"></script>		

		{{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.21.0/moment.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/locale/ca.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha18/js/tempusdominus-bootstrap-4.min.js"></script>
		<script src="{{ asset('js/admin/main.js') }}"></script> --}}

		@yield('codispeu')

	</body>
	
</html>