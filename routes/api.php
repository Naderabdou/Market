<?php

use App\Http\Controllers\Api\AdsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoriesController;
use App\Http\Controllers\Api\DriversController;
use App\Http\Controllers\Api\ExtrasController;
use App\Http\Controllers\Api\MarketsController;
use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::get('markets',[MarketsController::class,'index']);
Route::get('markets/{market}',[MarketsController::class,'show'])->name('markets.show');
Route::get('all/products/market/{market}', [MarketsController::class, 'ShowProductsMarket']);


Route::get('categories',[CategoriesController::class,'index']);
Route::get('categories/{id}',[CategoriesController::class,'show'])->name('categories.show');

Route::get('products',[ProductsController::class,'index']);
Route::get('products/{id}',[ProductsController::class,'show'])->name('products.show');

Route::get('all/drivers/online',[DriversController::class,'driversOnline'])->name('driver.show');


Route::get('all/ads',[AdsController::class,'index']);
Route::get('ads/{id}',[AdsController::class,'show'])->name('ads.show');



//Route::get('extras',[ExtrasController::class,'index']);


Route::middleware('jwtApi')->group(function () {
    Route::get("/profile", [AuthController::class, 'profile']);
    Route::get("/refresh", [AuthController::class, 'refreshToken']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user', [AuthController::class, 'user']);

    Route::get('user/{id}/block', [UsersController::class, 'block']);
    Route::get('user/{id}/unblock', [UsersController::class, 'unblock']);


    Route::get('user/{id}/close',[UsersController::class,'close']);
    Route::get('user/{id}/open',[UsersController::class,'open']);


    Route::get('users/all/block/users', [UsersController::class, 'AllBlockUsers']);
    Route::get('users/all/block/markers', [UsersController::class, 'AllBlockMarkets']);
    Route::get('users/all/block/drivers', [UsersController::class, 'AllBlockDrivers']);

    Route::get('all/users', [UsersController::class, 'AllUsers']);





    Route::get('all/markets/expired', [MarketsController::class, 'expired']);
    Route::resource('markets', MarketsController::class)->except('show','index');


    Route::resource('categories', CategoriesController::class)->except('show','index');


    Route::get('all/drivers/expired', [DriversController::class, 'expired']);
    Route::resource('drivers', DriversController::class);


    Route::get('product/status/{id}/available', [ProductsController::class, 'statusAvailable']);
    Route::get('product/status/{id}/unavailable', [ProductsController::class, 'statusUnavailable']);
    Route::get('all/available', [ProductsController::class, 'allProductsAvailable']);
    Route::get('all/unavailable', [ProductsController::class, 'allProductsUnavailable']);

    Route::resource('products', ProductsController::class)->except('show','index');

    Route::resource('extras', ExtrasController::class);
    Route::get('all/ads/user', [AdsController::class, 'allAdsUser']);
    Route::resource('Ads', AdsController::class)->except('show','index');



});
