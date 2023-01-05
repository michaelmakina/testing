<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//auth controller
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\ProductController;

//product controller


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

Route::get('allProducts', [ProductController::class, 'index']);

Route::group([], function(){
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::delete('delete', [AuthController::class, 'delete']);
});

Route::middleware("auth:sanctum")->group(function(){

    /**
     * products
     */
    Route::prefix("products")->group(function(){
        Route::post('/', [ProductController::class, 'store']);
        Route::delete("/", [ProductController::class, 'destroy']);
        Route::put("/{id}", [ProductController::class, 'update']);
    });
},




);









