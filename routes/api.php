<?php

use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/cart',[CartController::class,'apiCart']);
Route::get('/cart/destroy',[CartController::class,'destroy']);
Route::post('/cart/add',[CartController::class,'add']);

Route::group(['middleware'=>['cors']],function(){

    Route::post('/login',[QrController::class,'login']);
    Route::post('/testQR',[QrController::class,'QrController@testQr']);
    Route::post('/checkQR',[QrController::class,'checkQr'])->middleware('auth:api');

});