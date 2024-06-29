<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\PackController;
use App\Http\Controllers\AdminController;
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

	Route::get('/cart/destroy', [CartController::class, 'destroy'])->name('cart.destroy');
	Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
	Route::post('/cart/remove', [CartController::class, 'removeRow'])->name('cart.remove');
	Route::post('/cart/coupon', [CouponController::class, 'apply'])->name('cart.coupon');

	Route::get('activitat/{name?}/{day?}/{hour?}', [ProductController::class, 'show'])
		->where('day', '^\d{4}-((0\d)|(1[012]))-(([012]\d)|3[01])$')
		->where('hour', '^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$')
		->name('product');

	Route::get('image/{path}', [ProductController::class, 'image'])->name('image')
		->where('path', '.*\.(jpg|jpeg|png|gif|bmp|webp)');

	Route::get('search', [ProductController::class, 'search'])->name('search');

	// Reserva d'un producte dins un pack
	Route::post('pack/{id}', [PackController::class, 'register'])->name('pack.register');
	Route::any('pack/{id}/esborra', [PackController::class, 'delete'])->name('pack.delete');
	Route::post('pack-afegeix', [PackController::class, 'addTicket'])->name('pack.add');
	Route::get('pack-elimina/{pack_id}/{product_id}', [PackController::class, 'removeTicket'])->name('pack.remove');

	// Checkout
	Route::get('confirmacio', [CartController::class, 'confirm'])->name('checkout');

	// Login client
	Route::post('cistell/login', array('uses' => 'UserController@login'));

	// Pagament
	Route::post('checkout', [OrderController::class, 'store'])->name('checkout.store');
	Route::get('checkout/card/order/{id}', [OrderController::class, 'checkoutTPV'])->name('checkout.tpv');
	Route::get('confirmacio/{session}/{id}', [OrderController::class, 'checkoutSuccess'])->name('checkout.success');
	Route::get('confirmacio/error', [OrderController::class, 'checkoutError'])->name('checkout.error');

	// Login web
	Route::post('modal/login', [OrderController::class, 'login'])->name('modal-login');

	// Pàgines estàtiques
	Route::get('condicions', [PageController::class, 'conditions'])->name('condicions');
	Route::get('politica-privacitat', [PageController::class, 'privacyPolicy'])->name('privacitat');
	Route::get('organitzadors', [PageController::class, 'organizers'])->name('organitzadors');

	// Devolucions
	Route::get('devolucio/{id}', [RefundController::class, 'refund'])->name('refund');

	// Calendari
	Route::get('calendari', [ProductController::class, 'calendar'])->name('calendar');
	Route::get('calendari/ics', [ProductController::class, 'ics']);

});

Route::get('pdf/{session}/{id}', [OrderController::class, 'pdf'])->name('order.pdf');

// TPV responses
Route::middleware('cors')->group(function () {
	Route::any('tpv_response', [OrderController::class, 'tpvResponse'])->name('tpv_response');
	Route::any('tpv_response_refund', [RefundController::class, 'tpvResponse'])->name('refund-tpv');
});


Route::group(['middleware' => ['role:admin']], function()
{

	Route::get('data', [AdminController::class, 'data'])->name('admin.data');

});