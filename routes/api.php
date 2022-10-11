<?php

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

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::resource('payments', App\Http\Controllers\PaymentController::class);
    Route::resource('travel-payments', App\Http\Controllers\TravelPaymentController::class);
    Route::resource('payment-approvals', App\Http\Controllers\PaymentApprovalController::class);

    Route::get('/users/approver', [\App\Http\Controllers\UserController::class, 'getAllApprovers']);
    Route::post('approval', [App\Http\Controllers\PaymentApprovalController::class, 'paymentApproval']);
});

Route::post('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
