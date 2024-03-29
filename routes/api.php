<?php

use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/products', [ProductController::class, 'index']);

Route::get('/product/{sku_or_slug}', [ProductController::class, 'getBySkuOrSlug']);

Route::get('/products/search/{term?}', [ProductController::class, 'search']);

Route::post('/delivery', [DeliveryController::class, 'create']);