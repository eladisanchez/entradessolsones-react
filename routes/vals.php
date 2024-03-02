<?php

use App\Http\Controllers\ValController;


Route::get('generaVals',function() {

    // Generar codis
    DB::table('vals_codis')->truncate();
    $i = 0;
    while ($i<11750) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($j = 0; $j < 8; $j++) {
            $randstring .= $characters[rand(0, strlen($characters)-1)];
        }
        $code_exists = DB::table('vals_codis')->where('codi',$randstring)->first();
        if ($code_exists) {
            continue;
        }
        DB::table('vals_codis')->insert([
            'id' => $i,
            'codi' => $randstring,
            'premi' => 0,
            'talonari' => floor($i/50)+1
        ]);
        $i++;
    }

    // Repartir premis entre talonaris
    $totalVals = DB::table('vals_codis')->count();
    $cada = 50;
    $talonaris = $totalVals/$cada;

    for ($i=0; $i <= $talonaris; $i++) {

        echo '<strong>Talonari '.($i+1).'</strong><br>';

        $premiats = DB::table('vals_codis')->orderBy('id')->skip($i*$cada)->take($cada)->get();
        $premiats = $premiats->shuffle();
        foreach ($premiats as $k=>$premiat) {
            if ($k<3) {
                DB::table('vals_codis')->where('id',$premiat->id)->update(['premi'=>2]);
                echo 'Premi de 20€<br>';
            } else {
                continue;
            }
            // if ($k==0) {
            //     DB::table('vals_codis')->where('id',$premiat->id)->update(['premi'=>5]);
            //     echo 'Premi de 50€<br>';
            // }
            // if ($k>0 && $k<=4) {
            //     DB::table('vals_codis')->where('id',$premiat->id)->update(['premi'=>2]);
            //     echo 'Premi de 20€<br>';
            // }
            // if ($k>4) {
            //     continue;
            // }
            echo $premiat->id.' - '.$premiat->codi;
            echo '<br>';
        }

        echo '<br><br>';

    }


    // for($i=1;$i<=140;$i++) {
    //     $out .= 'Afegim 2 al talonari '.$i.'<br>';
    //     DB::table('vals_codis')->where([
    //         ['talonari','=',$i],
    //         ['premi','=',0]
    //     ])->inRandomOrder()->limit(6)->update(['premi' => 2]);
    //     $out .= 'Afegim 5 al talonari '.$i.'<br>';
    //     DB::table('vals_codis')->where([
    //         ['talonari','=',$i],
    //         ['premi','=',0]
    //     ])->inRandomOrder()->limit(1)->update(['premi' => 5]);
    // }
    // $out .= 'Afegim altres<br>';
    // DB::table('vals_codis')->where('premi',0)->inRandomOrder()->limit(10)->update(['premi' => 2]);
    // DB::table('vals_codis')->where('premi',0)->inRandomOrder()->limit(10)->update(['premi' => 5]);

    // return $out;
});




// Gestor Vals
Route::group(['prefix' => 'admin', 'middleware' => ['role:vals|admin']], function()
{
	Route::get('vals', [ValController::class,'index'] )->name('vals.admin.index');
    Route::post('vals/dates',[ValController::class,'storeDates'])->name('vals.admin.storeDates');
    Route::get('vals/comercos',[ValController::class,'indexComercos'])->name('vals.admin.comercos.index');
    Route::post('vals/comercos',[ValController::class,'storeComerc'])->name('vals.admin.comercos.store');
    Route::get('vals/comercos/{id}',[ValController::class,'editComerc'])->name('vals.admin.comercos.edit');
    Route::post('vals/comercos/{id}',[ValController::class,'updateComerc'])->name('vals.admin.comercos.update');
    Route::get('vals/comercos/toggle/{id}',[ValController::class,'toggleComerc'])->name('vals.admin.comercos.toggle');
    Route::get('vals/excel',[ValController::class,'export'])->name('vals.admin.excel');
    Route::get('vals/excelqr',[ValController::class,'exportQr'])->name('vals.admin.excelqr');
    Route::post('vals',[ValController::class,'storeAnonymous'])->name('vals.storeAnonymous');
});

// Jo compro a solsona
Route::get('ensolsonat',[ValController::class,'create'])->name('vals.create');
Route::post('ensolsonat',[ValController::class,'checkCode'])->name('vals.check');
Route::post('ensolsonat/register',[ValController::class,'store'])->name('vals.store');
//Route::get('vals/checkout/{id}',[ValController::class,'tpv')->name('vals.tpv');
//Route::any('vals/tpv-response',[ValController::class,'tpvResponse')->name('vals.tpvresponse');
Route::get('vals/pdf/{codi}',[ValController::class,'pdf'])->name('vals.pdf');
Route::get('/vals/qr/{id}/{count}',[ValController::class,'qr'])->name('vals.qr')->where('count', '[0-9]+');
Route::post('/vals/qr/{qr}/{count}',[ValController::class,'activate'])->where('count', '[0-9]+')->name('vals.activate');

// Textos legals
Route::get('/ensolsonat/condicions-us',[ValController::class,'condicionsUs'])->name('vals.condicions-us');
Route::get('/ensolsonat/proteccio-dades',[ValController::class,'proteccioDades'])->name('vals.proteccio-dades');

// Gestor comerços
Route::get('ensolsonat/comerc',[ValController::class,'comercAdmin'])->name('vals.comercadmin');
Route::post('ensolsonat/comerc',[ValController::class,'comercLogin'])->name('vals.comerclogin');
Route::get('ensolsonat/comerc/logout',[ValController::class,'comercLogout'])->name('vals.comerclogout');