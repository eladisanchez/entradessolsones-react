<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\VenueMapController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::middleware(['restrict.public'])->prefix(LaravelLocalization::setLocale())->group(function () {

	Route::get('/', [ProductController::class, 'home'])->name('home');

	Route::get('/cart/destroy',[CartController::class,'destroy']);
	Route::post('/cart/add',[CartController::class,'add']);
	Route::post('/cart/remove',[CartController::class,'removeRow']);
	Route::post('/cart/coupon',[CartController::class,'applyCoupon']);

	Route::get('activitat/{name?}/{day?}/{hour?}', [ProductController::class, 'show'])
		->where('day', '^\d{4}-((0\d)|(1[012]))-(([012]\d)|3[01])$')
		->where('hour', '^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$')
		->name('product');
	
	Route::get('image/{path}', [ProductController::class, 'image'])->name('image')->where('path', '.*\.(jpg|jpeg|png|gif|bmp|webp)');

	Route::get('search', [ProductController::class, 'search'])->name('search');

	// Reserva d'un producte dins un pack
	Route::post('pack/{id}', array('uses' => 'PackController@registraPack'));
	Route::any('pack/{id}/esborra', array('uses' => 'PackController@esborraPack'));
	Route::post('pack-afegeix', array('uses'=>'PackController@producteaPack'));
	Route::get('pack-elimina/{id_pack}/{id_producte}', array('uses' => 'PackController@esborraProducte'));

	// Cistell
	Route::get('cistell', [CartController::class, 'show'])->name('cart');
	Route::post('cistell/add', [CartController::class, 'add'])->name('cart.add');
	Route::post('cistell/addpack', [CartController::class, 'addPack'])->name('cart.add-pack');
	Route::post('cistell/addesdeveniment', [CartController::class, 'addEvent'])->name('cart.add-seats');
	Route::get('cistell/destroy', [CartController::class, 'destroy'])->name('cart.destroy');
	Route::get('cistell/remove', [CartController::class, 'removeItem'])->name('cistell.remove')->name('cart.remove-item');
	Route::get('confirmacio', [CartController::class, 'confirm'])->name('checkout');

	// Login client
	Route::post('cistell/login',array('uses' => 'UserController@login'));

	// Codis promocionals
	Route::post('codi-promocional', array('as'=>'codi','uses'=>'CodiController@aplica'));

	// Pagament
	Route::post('checkout',[OrderController::class,'store'])->name('checkout');
	Route::get('checkout/card/order/{id}', array(
		'as' => 'checkout-tpv',
		'uses' => 'OrderController@checkoutTPV'));

	// Pagament
	Route::get('checkout/card/{sessio}/{id}', array(
		'as' => 'checkout-tpv-ok',
		'uses' => 'OrderController@tpvOK'));
	Route::get('checkout/card-error', array(
		'as' => 'checkout-tpv-ko',
		'uses' => 'OrderController@tpvKO'));

	// Devolucions
	Route::get('devolucio/{hash}',array(
		'as' => 'refund',
		'uses' => 'DevolucioController@refund'
	));

	// Condicions
	Route::get('condicions',array('as' => 'condicions', 'uses'=>'HomeController@condicions'));
	Route::get('politica-privacitat',['as'=>'politica-privacitat','uses'=>'HomeController@politicaPrivacitat']);

	Route::get('organitzadors',array('as' => 'organitzadors', 'uses'=>'HomeController@organitzadors'));
	Route::post('organitzadors','UserController@solicitudAlta');

	// Petició
	Route::post('peticio', array(
		'as' => 'peticio',
		'uses' => 'OrderController@peticio'));
	
	// Clients
	Route::get('perfil', 
		function(){
			return view('perfil');
	})->middleware('role:admin');

	// Devolucions
	Route::get('devolucio/{id}','ReservaController@devolucio');

	// Calendari
	Route::get('calendari',[ProductController::class,'calendar'])->name('calendar');
	Route::get('calendari/ics',[ProductController::class,'ics']);

});

Route::group(['prefix' => 'admin', 'middleware'=>['role:admin']],function(){
	Route::get('/venues/{id}/edit/map',[VenueMapController::class,'edit'])->name('venue.edit');
	Route::post('/venues/{id}/edit/map',[VenueMapController::class,'update'])->name('venue.update');
});

Route::group(['prefix' => 'admin', 'middleware' => ['role:admin|organizer']],function(){
	Route::get('producte/{id}/entrades/{dia}/{hora}/map',[AdminTicketController::class,'map'])
	->where('dia','^\d{4}-((0\d)|(1[012]))-(([012]\d)|3[01])$')->name('ticket.map');
});

Route::get('pdf/{session}/{id}',[OrderController::class,'pdf'])->name('order.pdf');
// TODO: Redirect contracte to pdf

// TPV responses
Route::middleware('cors')->group(function () {
	Route::any('tpv_response', [OrderController::class, 'tpvResponse'])->name('tpv_response');
	Route::any('tpv_response_refund', [RefundController::class, 'tpvResponse'])->name('refund-tpv');
});
