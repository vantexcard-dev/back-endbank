<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\TransferController;
use App\Http\Controllers\Api\PayPalController;
use App\Http\Controllers\Api\VerificationController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\CreditCardRequestController;
use App\Http\Controllers\Api\PushTokenController;
use App\Http\Controllers\Api\LoanController;
use App\Http\Controllers\Api\CardStatusController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReceiptController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/create-payment-intent', [PaymentController::class, 'createPaymentIntent']);
Route::post('/confirm-payment', [PaymentController::class, 'confirmPayment']);
Route::get('/receipt/{paymentId}/download', [PaymentController::class, 'downloadReceipt'])->name('receipt.download');
Route::get('/receipt/{paymentId}', [ReceiptController::class, 'show'])->name('receipt.show');

// routes/api.php
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->get('/me', [UserController::class, 'profile']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->prefix('transfers')->group(function () {
    Route::post('/initiate', [TransferController::class, 'initiate']);
    Route::post('/verify', [TransferController::class, 'verify']);
    Route::get('/pending', [TransferController::class, 'pending']);

});

Route::middleware('auth:sanctum')->group(function () {
    Route::patch('/cards/{id}/toggle', [CardStatusController::class, 'toggle']);
});

Route::middleware('auth:sanctum')->post('/request-card', [CreditCardRequestController::class, 'store']);

Route::middleware('auth:sanctum')->post('/register-push-token', [PushTokenController::class, 'register']);


Route::middleware('auth:sanctum')->post('/card/toggle/{id}', [CardController::class, 'toggleStatus']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/loans', [LoanController::class, 'index']);
    Route::post('/loans', [LoanController::class, 'store']);
    Route::get('/loans/{id}', [LoanController::class, 'show']);
    Route::put('/loans/{id}', [LoanController::class, 'update']);
    Route::delete('/loans/{id}', [LoanController::class, 'destroy']);
});

Route::get('/paypal/checkout', [PayPalController::class, 'checkout'])->name('paypal.checkout');

Route::post('/api/paypal/validate-payment', [PayPalController::class, 'validatePayment'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->post('/verification', [VerificationController::class, 'store']);

Route::middleware('auth:sanctum')->post('/verifications/selfie', [VerificationController::class, 'submitSelfie']);


Route::get('/paypal/error', function () {
    return view('paypal.error'); // Crée une vue d’erreur personnalisée si tu veux
})->name('paypal.error');
