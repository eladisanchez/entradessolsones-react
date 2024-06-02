<!doctype html>
<html lang="{{LaravelLocalization::setLocale()}}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		@section('head')
		<title>{{ config('app.name') }}</title>
		@show
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="{{ asset('css/main.css') }}">
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,900,900i" rel="stylesheet">
		<meta name="facebook-domain-verification" content="s4v5q53w9uodaq2rr8zcx86rlzrb22" />
		@yield('head-scripts')
	</head>

	<body class="{{ $class??'page' }}">


		<div class="site-header fixed-top navbar navbar-expand">
			<div class="container">
				<a class="logo navbar-brand" href="{{route('home')}}">
					<img src="{{url('images/logo-solsones.png')}}">
				</a>
				<div class="collapse d-none d-md-block">
					<ul class="navbar-nav mx-auto">
						<li class="mx-2 nav-item{{isset($product)&&$product->target=='individual'?' current':''}}">
							<a href="{{ route('home') }}#activitats" class="nav-link">
								{{ __("Activitats turístiques") }}
							</a>
						</li>
						<li class="mx-2 nav-item{{isset($product)&&$product->target=='esdeveniments'?' current':''}}">
							<a href="{{ route('home') }}#esdeveniments" class="nav-link">
								{{ __("Teatre i concerts") }}
							</a>
						</li>
						<li class="mx-2 nav-item">
							<a href="{{ route('home') }}#altres" class="nav-link">
								{{ __("Altres activitats") }}
							</a>
						</li>
						<li class="mx-2 nav-item">
							<a href="{{-- route('calendar') --}}" class="nav-link">
								{{ __("Calendari") }}
							</a>
						</li>
					</ul>
				</div>
				
				<ul class="navbar-nav ml-auto">
					{{-- <li class="nav-item d-lg-block d-none"><a href="http://turismesolsones.com" class="nav-link">Turisme Solsonès</a></li> --}}
					<li class="nav-item ml-2 btn-cistell"><a class="btn btn-outline-light" href="{{ route('cart') }}"><i class="fas fa-shopping-cart"></i>
					@if(Cart::count()>0) <span>{{ Cart::count() }}</span> @endif
					</a></li>
					<li class="nav-item ml-2">
						<a class="nav-link @if(LaravelLocalization::getCurrentLocale()=='ca') font-weight-bold @endif" href="/ca">
							CA
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link @if(LaravelLocalization::getCurrentLocale()=='es') font-weight-bold @endif" href="/es">
							ES
						</a>
					</li>
				</ul>
				
				
			</div>
		</div>

		{{-- @include('parts.cistell') --}}


		@if(App::isDownForMaintenance())
			<p class="manteinance">WEB EN MANTENIMENT</p>
		@endif


		@yield('content')

		
		<div class="site-contact">
			<div class="container">
				<div class="row">

					<div class="col-lg-2 col-sm-4">
						<img src="{{url('images/logo-solsones.png')}}" class="logo-footer">
					</div>

					<div class="col-lg-4 col-sm-4">
						<p>
							<strong>Oficina de Turisme del Solsonès</strong><br>
							Ctra. de Bassella, 1<br>
							25280 Solsona<br>
							Tel. 973 48 23 10<br>
							<img src="{{url('images/mail.gif')}}">
						</p>
					</div>

					<div class="col-lg-3 text-sm-right">
						<img src="{{url('images/logo_ara_lleida_diputacio.png')}}" class="logo-footer">
					</div>

					<div class="col-lg-3 col-sm-12 text-sm-right">
						{{-- <a href="{{route('organitzadors')}}" class="d-block">Com puc vendre entrades?</a>
						<a href="{{route('condicions')}}" class="d-block">Condicions d'ús</a>
						<a href="{{route('politica-privacitat')}}" class="d-block">Protecció de dades</a> --}}
						<a href="http://turismesolsones.com/avis-legal-i-politica-de-privacitat/" target="_blank" class="d-block">Avís legal</a>
						<a href="http://turismesolsones.com/contacte" target="_blank" class="d-block">Contacte</a>
					</div>
						
					</div>
				</div>
			</div>
		</div>

		@if(session()->has('itemAdded'))
		<script>var itemAdded=true;</script>
		@endif

		<script
		src="https://code.jquery.com/jquery-3.3.1.min.js"
		integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
		crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
		<script type="text/javascript" src="{{ asset('js/vendor/jquery.magnific-popup.min.js') }}"></script>
		<script src="https://cdn.tiny.cloud/1/9jg5iq7bd9mj4sqf4nt5i147e73xqcv6vcgkjk4rvvxh7wln/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
		<script src="https://kit.fontawesome.com/d64d6b5aa5.js" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/locale/ca.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/locale/es.js"></script>
		<script src="{{ asset('js/vendor/underscore-min.js') }}"></script>
		<script src="{{ asset('js/vendor/clndr.min.js') }}"></script>
		<script src="{{ asset('js/main.js') }}"></script>

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-80729092-45"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-80729092-45');
		</script>
		<script type="text/javascript">
			(function(c,l,a,r,i,t,y){
				c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
				t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
				y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
			})(window, document, "clarity", "script", "k4bioel153");
		</script>


	</body>
	
</html>