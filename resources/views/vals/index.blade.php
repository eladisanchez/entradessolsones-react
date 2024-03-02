@extends('vals.base')

@section('head')
	<title>EnSolsona't - L'alegria es multiplica aquest Nadal comprant a Solsona!</title>
@stop

@section('content')

<header class="comproasolsona-top">
  <a href="https://jocomproasolsona.cat">Jo compro a Solsona</a>
</header>

<div class="comproasolsona-header">

  <div class="grid">
    <div class="grid-col header-col">
      <img src="{{ asset('assets/ensolsonat/img/logo.svg') }}" alt="Ensolsona't!" class="comproasolsona-logo">
    </div>
    <div class="grid-col header-col">
      <h1>L'alegria es multiplica aquest Nadal comprant a Solsona!</h1>
      <p>Busca els establiments adherits a la campanya <strong>"Jo compro a Solsona"</strong> quan vagis a comprar roba, complements, electrodomèstics o vagis a sopar <strong>entre els dies 1 i 15 de desembre. Centenars de premis t'estan esperant!</strong></p>
    </div>
  </div>

  <div class="grid">
    <div class="grid-col">
      <div class="butlletes">
        <h3>Hi ha més de <strong>700</strong> butlletes premiades.</h3>
        <p>Desenes de comerços i establiments repartiran més de 1.000 butlletes premiades amb vals de compra de 20 euros.</p>
      </div>
    </div>
    <div class="grid-col">
      @if($open)
        <form class="form-codi" action="" method="post">
          <h3>Introdueix el teu codi</h3>
          <input type="text" name="codi" class="input-codi" placeholder="XXXXXXXX" maxlength="8">
          @if (\Session::has('error_codi'))
            <p class="error-codi">{!! \Session::get('error_codi') !!}</p>
          @endif
          <p><input type="submit" class="envia-codi" value="Comprova el codi" size="8"></p>
        </form>
      @endif
    </div>
  </div>

</div>

<section class="comproasolsona-comfunciona">
  <h2 class="lobster text-gradient mb-3">Com funciona la campanya?</h2>
  <p><strong>Entre els dies 1 i 15 de desembre</strong> per cada compra o consumició que facis als comerços o establiments adherits a la campanya aconseguiràs una butlleta. Després, només caldrà que introdueixis el codi per descobrir si té premi.</p>
</section>

<div class="grid">
  <div class="grid-col">
    <div class="bg-purple">
      <div>
        <h3>Si la butlleta està premiada</h3>
        <p>Rebràs al correu electrònic el premi de 20 euros, desglossat en vals de 10 euros cada un. Imprimeix-los o guarda'ls al mòbil. Tens temps de gastar els vals fins al 24 de desembre (inclòs).</p>
      </div>
    </div>
  </div>
  <div class="grid-col">
    <div class="bg-orange">
      <div>
        <h3>Si la butlleta no té premi continua comprant i buscant la sort!</h3>
        <p>Si estàs empadronat/da a Solsona, entraràs en el sorteig de 3 premis de 250 euros en vals. El sorteig es farà el 19 de desembre, a les 4 de la tarda, al compte d'Instagram de l'Agència de Desenvolupament Local de Solsona i Cardona (@adlsolcar). Tindràs temps de gastar els vals fins al 24 de desembre (inclòs).</p>
      </div>
    </div>
  </div>
</div>

<section class="comproasolsona-comgastar">
  <h2 class="lobster mb-5">Com podràs gastar els vals?</h2>
  <div class="grid">
    <div class="grid-col">
      <div class="bg-trans-left">
        <div>
          <h3>Tens temps fins el <strong>24 de desembre</strong> (inclòs)</h3>
          <p>per gastar-los en qualsevol dels <strong>comerços o establiments adherits</strong>.</p>
        </div>
      </div>
    </div>
    <div class="grid-col">
      <div class="bg-trans-right">
        <div>
          <h3><strong>Quan siguis al comerç o establiment, entrega els vals o ensenya’ls</strong> (si els tens al mòbil)</h3>
          <p><strong>La despesa mínima ha de ser de 10 euros</strong>, ja que no es tornen diners a canvi dels vals. Pots gastar-los en una única compra o en diverses.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<aside class="dubtes">Si tens qualsevol dubte, pots trucar o enviar un WhatsApp al 690 87 26 94</aside>

<section class="comproasolsona-comercos">
  <div class="container">

  <h2 class="text-center lobster text-gradient">Comerços adherits</h2>

  <select class="custom-select mb-3" id="cerca-sector">
    <option value="">Tots els comerços</option>
    @foreach($sectors as $sector)
    <option value="{{$sector}}">{{$sector}}</option>
    @endforeach
  </select>
  <div class="table-responsive-md mb-5">
  <table class="table my-4" id="taula-comercos">
    <thead>
      <tr>
        <th width="25%">Comerç</th>
        <th width="25%">Sector</th>
        <th width="25%">Adreça</th>
        <th width="25%">Web</th>
      </tr>
    </thead>
    <tbody>
      @foreach($comercos as $comerc)
      @php 
      $web = $comerc->web;
      if($web) {
      $scheme = parse_url($comerc->web, PHP_URL_SCHEME);
      if (empty($scheme))
        $web = 'https://' . ltrim($web, '/');
      }
      @endphp
      <tr>
        <td><strong>{{$comerc->name}}</strong></td>
        <td>{{$comerc->sector}}</td>
        <td>{{$comerc->adreca}}</td>
        <td><a href="{{$web}}" target="_blank">{{$web}}</a></td>
      </tr>
      @endforeach
    </tbody>
  </table>
  </div>

  <img src="{{url('assets/ensolsonat/img/footer.svg')}}" alt="Jo compro a Solsona" class="skyline">
  </div>
</div>


@if(session('email'))
<div id="premiat" class="mfp-hide">
  <div class="bg-verd text-center">
    <p><strong>Comprova al teu correu electrònic</strong> si has rebut el premi de 20 euros, desglossat en vals de 10 cada un. <strong>Imprimeix-los o guarda'ls al mòbil</strong>.</p>
    <!--<p><a href="#" class="btn btn-dark btn-close-popup">He rebut el meu correu</a> <a href="" class="btn btn-outline-dark">Torna a enviar</a></p>-->
    <p>Si no veus el correu electrònic, comprova la bústia de correu brossa. Si tens qualsevol dubte, pots trucar o enviar un WhatsApp al 690 87 26 94.</p>
    <p><a href="#" class="btn btn-dark btn-close-popup">D'acord</a></p>
  </div>
</div>
@endif

{{-- @if(session('fail'))
<div id="nopremi" class="mfp-hide bg-blau">
  <div class="text-center">
    <h2 class="h1">Ep! La teva butlleta no té premi, però <u>tens una nova oportunitat</u></h2>
    <p><strong>Si estàs empadronat/da a Solsona, pots entrar en el sorteig de 20 premis de 500 euros</strong> en vals o d'un premi de 1.000 euros en vals.</p>
    <p>El sorteig es farà el <strong>15 de desembre, a les 4 de la tarda</strong>, al compte d'Instagram de l'Agència de Desenvolupament Local de Solsona i Cardona <strong>(@adlsolcar)</strong>.</p>
  </div>
</div>
@endif --}}

@if(session('error_soon'))
<div id="nopremi" class="mfp-hide">
  <div class="text-center">
    <h3 class="mb-4 lobster">La campanya s'inicia a partir de l'1 de desembre</h3>
  </div>
</div>
@endif

@if(session('error_late'))
<div id="nopremi" class="mfp-hide">
  <div class="text-center">
    <h3 class="mb-4 lobster">El període de validació de codis ha finalitzat.</h3>
    <p>Gràcies per participar!</p>
  </div>
</div>
@endif

@if(session('code'))
<div id="form-dades" class="mfp-hide @if($errors->any()) has-errors @endif">
  <div class="p-4 text-center">

    @if(session('success'))
      <h3 class="lobster">La teva butlleta té un premi de {{session('code')->premi*10}} €!</h2>
      <p><strong>Completa les teves dades per obtenir-lo.</strong></p>
    @else 
      <h3 class="lobster">Ep! La teva butlleta no té premi, però <u>tens una nova oportunitat</u></h2>
      <p><strong>Si estàs empadronat/da a Solsona, pots entrar en el sorteig de 3 premis de 250 euros</strong> en vals.</p>
      <p>El sorteig es farà el <strong>19 de desembre, a les 4 de la tarda</strong>, al compte d'Instagram de l'Agència de Desenvolupament Local de Solsona i Cardona <strong>(@adlsolcar)</strong>.</p>
      <p><strong>Completa les teves dades per participar-hi.</strong></p>
    @endif

    

    @if($errors->any())
    {!! implode('', $errors->all('<div class="alert alert-danger mb-2">:message</div>')) !!}
    @endif

    <form action="{{ route('vals.store') }}" class="form-dades" method="post">
      @csrf
      <div class="row">
        <div class="form-group col-md-4 mb-3">
          <div class="row">
            <div class="col-auto col-label">
              <label for="form-nom">Nom</label>
            </div><div class="col">
              <input type="text" class="form-control" id="form-nom" name="nom" required value="{{ old('name') }}">
            </div>
          </div>
        </div>
        <div class="form-group col-md-8 mb-3">
          <div class="row">
            <div class="col-auto col-label">
              <label for="form-cognoms">Cognoms</label>
            </div><div class="col">
              <input type="text" class="form-control" id="form-cognoms" name="cognoms" required value="{{ old('cognoms') }}">
            </div>
          </div>
        </div>
        <div class="form-group col-md-4 mb-3">
          <div class="row">
            <div class="col-md-auto col-label">
              <label for="form-dni">DNI/NIE</label>
            </div><div class="col">
              <input type="text" class="form-control" id="form-dni" name="dni" required maxlength="9" value="{{ old('dni') }}">
            </div>
          </div>
        </div>
        <div class="form-group col-md-4 mb-3">
          <div class="row">
            <div class="col-md-auto col-label">
              <label for="form-localitat">Localitat</label>
            </div><div class="col">
              <input type="text" class="form-control" id="form-localitat" name="localitat" required value="{{ old('localitat') }}">
            </div>
          </div>
        </div>
        <div class="form-group col-md-4 mb-3">
          <div class="row">
            <div class="col-md-auto col-label">
              <label for="form-telefon">Telèfon</label>
            </div><div class="col">
              <input type="text" class="form-control" id="form-telefon" name="telefon" required value="{{ old('telefon') }}">
              {{-- <p class="small mt-2">Només s'utilitzarà per contactar-te en cas d'obtenir un premi en el sorteig</p> --}}
            </div>
          </div>
        </div>
        <div class="form-group col-md-6">
          <div class="row">
            <div class="col-md-auto col-label">
              <label for="form-email">Correu-e</label>
            </div>
            <div class="col">
              <input type="email" class="form-control" id="form-email" name="email" required value="{{ old('email') }}">
            </div>
          </div>
        </div>
        <div class="form-group col-md-6">
          <div class="row">
            <div class="col-auto col-label col-label-long">
              <label for="form-email2">Repeteix email</label>
            </div>
            <div class="col">
              <input type="email" class="form-control" id="form-email2" name="email_confirmation" required>
            </div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center mt-4">
        <div class="form-group col-md-6">
          <p class="mb-0"><label class="text-center"><input type="checkbox" name="acceptation" value="1" required> He llegit i accepto les <a href="jocomproasolsona/condicions-us" style="color: #FFF; text-decoration: underline;">bases legals</a> de la campanya</label></p>
          <p><label class="text-center"><input type="checkbox" name="registre" value="1"> Vull estar al dia de futures promocions</label></p>
          <p><input type="submit" value="@if(session('success')) Vull rebre el premi @else Participa @endif" class="envia-dades"></p>
        </div>
      </div>
      <input type="hidden" class="input-dades-codi" name="codi" value="{{ session('code')->codi }}">
    </form>

  </div>
</div>
@endif

@stop