<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\AdminPriceController;
use App\Http\Controllers\Admin\AdminPackController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminVenueController;
use App\Http\Controllers\Admin\AdminRateController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminExtractController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminUserController;
use Rap2hpoutre\LaravelLogViewer\LogViewerController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::name('admin.')->prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {

	// Inici
	Route::get('/', [AdminController::class, 'home'])->name('admin');

	// Analítiques
	Route::get('excel', [AdminController::class, 'excelExport'])->name('analytics');

	// Llista de productes
	Route::get('productes', [AdminProductController::class, 'index'])->name('product.index');

	// Editor del producte
	Route::get('producte/{id}', [AdminProductController::class, 'edit'])->name('product.edit');
	Route::post('producte/{id}', [AdminProductController::class, 'update'])->name('product.update');
	Route::delete('producte/{id}', [AdminProductController::class, 'destroy'])->name('product.destroy');
	Route::get('producte/{id}/preview-pdf', [AdminProductController::class, 'previewPdf'])->name('product.preview-pdf');

	// Activa/desactiva
	Route::get('producte/{id}/activacio', [AdminProductController::class, 'actiu'])->name('product.active');

	// Reordenar drag & drop
	Route::post('update-order', [AdminProductController::class, 'updateOrder'])->name('product.order');

	// Foto del producte
	Route::post('producte/{id}/imatge', [AdminProductController::class, 'imatge'])->name('product.image');
	Route::post('producte/{id}/imatgeHeader', [AdminProductController::class, 'imatgeHeader'])->name('product.header-image');

	// Producte nou
	Route::get('producte-nou', [AdminProductController::class, 'create'])->name('product.create');
	Route::post('producte-nou', [AdminProductController::class, 'store'])->name('product.store');

	// Editor de les entrades
	Route::get('producte/{id}/entrades', [AdminTicketController::class, 'index'])->name('ticket.index');
	Route::get('producte/{id}/entrades/{dia}/{hora}', [AdminTicketController::class, 'edit'])->name('ticket.edit');
	Route::post('entrada-update', [AdminTicketController::class, 'update'])->name('ticket.update');
	Route::post('entrada-nova', [AdminTicketController::class, 'store'])->name('ticket.store');
	Route::get('producte/{id}/entrades-destroy/{dia}/', [AdminTicketController::class, 'destroyDay'])->name('ticket.destroy-day');
	Route::post('producte/{id}/entrades/{dia}/{hora}/destroy', [AdminTicketController::class, 'destroy'])->name('ticket.destroy');

	// Editor dels preus
	Route::get('producte/{id}/preus', [AdminPriceController::class, 'index'])->name('price.index');
	Route::post('producte/{id}/preus', [AdminPriceController::class, 'store'])->name('price.store');
	Route::delete('producte/preus/{idproducte}/{idtarifa}', [AdminPriceController::class, 'destroy'])->name('price.destroy');

	// Editor dels packs
	Route::get('producte/{id}/pack', [AdminPackController::class, 'index'])->name('pack.index');
	Route::post('producte/{id}/pack', [AdminPackController::class, 'store'])->name('pack.store');
	Route::delete('producte/pack/{idpack}/{idproducte}', [AdminPackController::class, 'destroy'])->name('pack.destroy');

	// Categories
	Route::get('productes/categories', [AdminCategoryController::class, 'index'])->name('category.index');
	Route::get('categoria-nova', [AdminCategoryController::class, 'create'])->name('category.create');
	Route::post('categoria-nova', [AdminCategoryController::class, 'store'])->name('category.store');
	Route::get('categoria/{id}', [AdminCategoryController::class, 'edit'])->name('category.edit');
	Route::post('categoria/{id}', [AdminCategoryController::class, 'update'])->name('category.update');
	Route::delete('categoria/{id}', [AdminCategoryController::class, 'destroy'])->name('category.destroy');

	// Espais
	Route::get('productes/espais', [AdminVenueController::class, 'index'])->name('venue.index');
	Route::get('espai', [AdminVenueController::class, 'create'])->name('venue.create');
	Route::post('espai', [AdminVenueController::class, 'store'])->name('venue.store');
	Route::get('espai/{id}', [AdminVenueController::class, 'edit'])->name('venue.edit');
	Route::post('espai/{id}/imatge', [AdminVenueController::class, 'imatge'])->name('venue.image');
	Route::post('espai/{id}', [AdminVenueController::class, 'update'])->name('venue.update');
	Route::delete('espai/{id}', [AdminVenueController::class, 'destroy'])->name('venue.destroy');

	// Tarifes
	Route::get('productes/tarifes', [AdminRateController::class, 'index'])->name('rate.index');
	Route::get('tarifa-nova', [AdminRateController::class, 'create'])->name('rate.create');
	Route::post('tarifa-nova', [AdminRateController::class, 'store'])->name('rate.store');
	Route::get('tarifa/{id}', [AdminRateController::class, 'edit'])->name('rate.edit');
	Route::post('tarifa/{id}', [AdminRateController::class, 'update'])->name('rate.update');
	Route::delete('tarifa/{id}', [AdminRateController::class, 'destroy'])->name('rate.destroy');

	// Comandes
	Route::get('comandes', [AdminOrderController::class, 'index'])->name('order.index');
	Route::get('comanda/{id}', [AdminOrderController::class, 'edit'])->name('order.edit');
	Route::get('comanda/{id}/reenviar', [AdminOrderController::class, 'resendMail'])->name('order.resend');
	Route::post('comanda/{id}', [AdminOrderController::class, 'update'])->name('order.update');
	Route::delete('comanda/{id}', [AdminOrderController::class, 'destroy'])->name('order.destroy');

	// Reserves
	Route::get('reserves', [AdminBookingController::class, 'index'])->name('booking.index');
	Route::get('reserva/{id}', [AdminBookingController::class, 'edit'])->name('booking.edit');
	Route::post('reserva/{id}', [AdminBookingController::class, 'update'])->name('booking.update');

	// Extractes
	Route::get('extractes', [AdminExtractController::class, 'index'])->name('extract.index');
	Route::post('extractes/create', [AdminExtractController::class, 'store'])->name('extract.store');
	Route::get('extractes/toggle/{id}', [AdminExtractController::class, 'togglePaid'])->name('extract.paid');
	Route::get('extractes/excel/{id}', [AdminExtractController::class, 'excel'])->name('extract.excel');
	Route::get('extractes/excel/delete/{id}', [AdminExtractController::class, 'destroy'])->name('extract.destroy');

	// Codis
	Route::get('codis', [AdminCouponController::class, 'index'])->name('coupon.index');
	Route::post('codis', [AdminCouponController::class, 'store'])->name('coupon.store');
	Route::delete('codis', [AdminCouponController::class, 'destroy'])->name('coupon.destroy');
	Route::delete('codisAll', [AdminCouponController::class, 'destroyAll'])->name('coupon.destroy-all');

	// Usuaris
	Route::get('usuaris', [AdminUserController::class, 'index'])->name('user.index');
	Route::post('usuaris', [AdminUserController::class, 'store'])->name('user.store');
	Route::get('usuari/{id}', [AdminUserController::class, 'edit'])->name('user.edit');
	Route::post('usuari/{id}', [AdminUserController::class, 'update'])->name('user.update');
	Route::delete('usuari/{id}', [AdminUserController::class, 'destroy'])->name('user.destroy');
	Route::post('usuari/producte/{id}', [AdminUserController::class, 'addProducte'])->name('user.product.add');
	Route::delete('usuari/producte/{id}/{idproducte}', [AdminUserController::class, 'destroyProducte'])->name('user.product.destroy');
	Route::get('clients', [AdminUserController::class, 'indexClients'])->name('clients');

	// Login com un altre usuari
	Route::get('loginas/{id}', [AdminUserController::class, 'loginAs'])->name('loginas');

	// Opcions
	Route::get('opcions', [AdminController::class, 'indexOptions'])->name('options.index');
	Route::post('opcions', [AdminController::class, 'updateOptions'])->name('options.update');

	// Logs
	Route::get('logs', [LogViewerController::class, 'index']);

// });