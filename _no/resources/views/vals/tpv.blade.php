@extends('vals.base')

@section('head')
	<title>Vals</title>
@stop

@section('content')

<div class="container">

  <h2>Redirigint a la passarel·la de pagament...</h2>

  <div class="d-none">
		<?php

		$amount = 40;
		$numero_aleatorio = mt_rand(1,100);
		$digit_control = str_repeat("0",3 - strlen($numero_aleatorio)) . $numero_aleatorio;
		$numeroPedido = $qr->id . $digit_control;
		if (strlen($numeroPedido)<10) {
			$ventafill =  str_repeat("0", 10 - strlen($numeroPedido)) . $numeroPedido; 
		} else {
			$ventafill = $numeroPedido;
		}
    $ventafill = '10'.$ventafill;
		$titular = $qr->name.' '.$qr->cognoms;

		Redsys::setAmount($amount);
		Redsys::setOrder($ventafill);
		Redsys::setMerchantcode(config('redsys.merchantCode'));
		Redsys::setCurrency('978');
		Redsys::setTransactiontype('0');
		Redsys::setTerminal(config('redsys.terminal'));
		Redsys::setMethod('T');
		Redsys::setNotification(config('app.url').'/vals/tpv-response');
		Redsys::setUrlOk(config('app.url').'/jocomproasolsona?ok='.$qr->codi);
		Redsys::setUrlKo(config('app.url').'/jocomproasolsona?error_pagament=1');
		Redsys::setVersion('HMAC_SHA256_V1');
		Redsys::setTradeName(config('redsys.tradeName'));
		Redsys::setTitular($titular);
		Redsys::setProductDescription('Jo compro a Solsona');

		//$key = config('redsys.key_test');
		//Redsys::setEnvironment('test');
		$key = config('redsys.key');
		Redsys::setEnvironment('live');
			
		Redsys::setLanguage('003'); // Català

		$signature = Redsys::generateMerchantSignature($key);
		Redsys::setMerchantSignature($signature);

		echo Redsys::createForm();

		?>
		</div>
							  
		<script type="text/javascript">document.redsys_form.submit();</script>

</div>

@stop