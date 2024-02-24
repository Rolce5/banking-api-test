<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CustomerController;

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

Route::post('/customers', [CustomerController::class, 'register']);
Route::get('/customers', [CustomerController::class, 'index']);
Route::post('/login', [AuthController::class, 'login']);

Route::group([auth:sanctum],function () {
    Route::post('/accounts', [AccountController::class, 'create']);
    Route::post('/accounts/{account}/deposit', [AccountController::class, 'deposit']);
    Route::post('/accounts/{account}/withdraw', [AccountController::class, 'withdraw']);
    Route::post('/accounts/transfer', [AccountController::class, 'transfer']);
    Route::get('/accounts/{account}/balance', [AccountController::class, 'getBalance']);
    Route::get('/accounts/{account}/transactions', [AccountController::class, 'getTransactions']);
});
