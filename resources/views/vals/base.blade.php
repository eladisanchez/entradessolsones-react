<!doctype html>
<html lang="{{LaravelLocalization::setLocale()}}">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		@section('head')
		<title>{{__("Entrades Solsonès")}} | Turisme Solsonès</title>
		@show
		<meta name="viewport" content="width=device-width, initial-scale=1">
		@vite('resources/scss/ensolsonat/app.scss')
		<meta name="facebook-domain-verification" content="s4v5q53w9uodaq2rr8zcx86rlzrb22" />
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" />
		<link rel="shortcut icon" href="{{ asset('comproasolsona/favicon.png')}}" />
	</head>

	<body class="jocomproasolsona" style="padding-bottom: 0;">

		@yield('content')

		<div class="pre-footer">
			<a href="{{ route('vals.condicions-us') }}">Condicions d'ús</a>
			<a href="/assets/ensolsonat/bases_comproasolsona_2023.pdf" target="_blank">Condicions de compra</a>
			<a href="{{route('vals.proteccio-dades')}}">Protecció de dades</a>
		</div>

		<div class="footer-vals">
			<div class="container">

				<a href="http://ajsolsona.cat" target="_blank">
					<img src="{{url('assets/ensolsonat/img/logos/ajsolsona.png')}}" alt="Ajuntament de Solsona">
				</a>
				<a href="https://www.adlsolcar.cat/" target="_blank">
					<img src="{{url('assets/ensolsonat/img/logos/adlsolcar.png')}}" alt="Agència de Desenvolupament Local Solsona - Cardona" style="width: 120px; height: auto;">
				</a>
				<a href="http://solsones.ddl.net/" target="_blank">
					<img src="{{url('assets/ensolsonat/img/logos/ccs.png')}}" alt="Consell Comarcal del Solsonès">
				</a>
				<a href="http://turismesolsones.com" target="_blank">
					<img src="{{url('assets/ensolsonat/img/logos/elsolsones.png')}}" alt="El Solsonès" style="width: 90px; height: auto;">
				</a>
				<a href="http://www.aralleida.cat/" target="_blank">
					<img src="{{url('assets/ensolsonat/img/logos/aralleida-dll.png')}}" alt="Ara Lleida - Diputació de Lleida">
				</a>
				<img src="{{url('assets/ensolsonat/img/logos/generalitat.png')}}" alt="Generalitat de Catalunya">
				<img src="{{url('assets/ensolsonat/img/logos/ministerio-trabajo.jpg')}}" alt="Ministerio de Trabajo">
				<img src="{{url('assets/ensolsonat/img/logos/soc2.png')}}" alt="SOC" style="width: 140px; height: auto;">
				
				
				
				
				<!--
				<a href="https://web.gencat.cat/" target="_blank">
					<img src="{{url('assets/ensolsonat/img/logos/gencat.png')}}" alt="Generalitat de Catalunya" style="width: 110px; height: auto;">
				</a>
				<a href="https://sepe.es/" target="_blank">
					<img src="{{url('assets/ensolsonat/img/logos/sepe.png')}}" alt="SEPE" style="width: 120px; height: auto;">
				</a>
				<a href="https://serveiocupacio.gencat.cat/ca/inici" target="_blank">
					<img src="{{url('assets/ensolsonat/img/logos/soc.png')}}" alt="SOC" style="width: 120px; height: auto;">
				</a>
				-->

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
		<script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous"></script>
		<script src="{{asset('vals/js/jquery.dataTables.min.js')}}"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js" integrity="sha512-XtmMtDEcNz2j7ekrtHvOVR4iwwaD6o/FUJe6+Zq+HgcCsk3kj4uSQQR8weQ2QVj1o0Pk6PwYLohm206ZzNfubg==" crossorigin="anonymous"></script>
		<script type="text/javascript" src="{{ asset('js/vendor/jquery.magnific-popup.min.js') }}"></script>
		<script src="{{asset('comproasolsona/js/main.js?v2')}}"></script>
		<script>
			jQuery(function($){

				$(".btn-comprar-vals").prop('disabled',true);
				$("#checkbox-condicions,#checkbox-legal").on("change",function(){
					if($("#checkbox-condicions").is(":checked") && $("#checkbox-legal").is(":checked")) {
						$(".btn-comprar-vals").prop('disabled',false);
					} else {
						$(".btn-comprar-vals").prop('disabled',true);
					}
				})

				var $taula = $('#taula-comercos').DataTable({
					'language': {
						'url': '//cdn.datatables.net/plug-ins/1.10.24/i18n/Catalan.json'
					}
				});
				$('#cerca-sector').on('keyup change', function () {
					if ($taula.column(1).search() !== this.value ) {
						$taula.column(1).search( this.value ).draw();
					}
        });
				$taula.on( 'draw', function(){
					$(".dataTables_filter input").addClass("form-control");
					$(".dataTables_length select").addClass("custom-select d-inline-block");
				});
				

			})
		</script>


		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-80729092-45"></script>
		<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'UA-80729092-45');
		</script>


	</body>
	
</html>