<?php
return [

    'key'                   => env('REDSYS_SECRET_KEY'),
    'key_test'              => env('REDSYS_SECRET_KEY_TEST'),
    'url_notification'      => config('app.url').'/tpv_response',
    'url_ok'                => config('app.url').'/checkout/card',
    'url_ko'                => config('app.url').'/checkout/card-error',

    'merchantCode'          => env('REDSYS_MERCHANT_CODE'),
    'tradeName'             => 'Solsonès Entrades',
    'terminal'              => '1'

];