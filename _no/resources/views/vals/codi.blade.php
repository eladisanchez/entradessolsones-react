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

    <div style="padding: 50px 20px; margin: 0 auto; max-width: 400px; " class="text-center">

      @if (date('Y-m-d H:i:s') < date('Y-m-d H:i:s', strtotime($qr_inici)))

      <h1>La promoció encara no ha començat</h1>

      @elseif (date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime($qr_fi)))

      <h1>La promoció ha finalitzat</h1>

      @else

      <h1>Val de 10€</h1>
      <p>
        @if($qr->name)
        <i class="fas fa-user"></i> <strong>{{$qr->name}} {{$qr->cognoms}}</strong><br>
        @endif
        <i class="fas fa-qrcode"></i> {{$qr->qr_count}} de {{$qr->premi}} vals utilitzats
      </p>

      <hr>

      @if(session('error'))
        <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{session('error')}}</div>
      @endif

      @if(session('message'))
        <div class="alert alert-success"><i class="fas fa-qrcode"></i> {{session('message')}}</div>
      @endif

      @if($qr->checkActivation($count))
        @if(!session('message'))
          <div class="alert alert-danger"><i class="far fa-frown"></i> Aquest val ja s'ha utilitzat.</div>
        @endif
      @else 

        <form method="post" action="{{action('ValController@activate',['qr'=>$qr->codi,'count'=>$count])}}">
          @csrf
          <h2 class="text-center mt-4">Activació</h2>
          <p class="alert alert-warning">Atenció! Aquest QR ha de ser escanejat i activat pel comerç on es vol gastar el val.</p>
          <div class="row">
            <div class="col"><input type="text" maxlength="4" name="clau" class="form-control form-control-lg" placeholder="Clau del comerç" size="4"></div>
            <div class="col-auto"><button class="btn btn-primary btn-lg">ACTIVAR <i class="ml-2 fas fa-angle-right"></i></button></div>
          </div>
          <p class="mt-4 text-muted text-center">Un cop utilitzat el val no s'admetran devolucions.</p>
        </form>

      @endif

      @endif
      </div>
      <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js" integrity="sha384-3LK/3kTpDE/Pkp8gTNp2gR/2gOiwQ6QaO7Td0zV76UFJVhqLl4Vl3KL1We6q6wR9" crossorigin="anonymous"></script>
      <script
		src="https://code.jquery.com/jquery-3.3.1.min.js"
		integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
		crossorigin="anonymous"></script>
      <script>
        jQuery(function($){
          // Límit 4 caràcters
          var $input = $('[name="clau"]');
          $input.keyup(function(e) {
            var max = 4;
            if ($input.val().length > max) {
                $input.val($input.val().substr(0, max));
            }
          });
          $('form').submit(function(){
            $(this).children('button').prop('disabled', true);
          });
        });
      </script>
</body></html>