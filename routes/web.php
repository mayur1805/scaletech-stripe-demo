<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\WebHookController;

//Payment
Route::get('/', [PaymentController::class, 'show'])->name('payment.form');
Route::post('/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');


//Stripe
Route::post('/stripe/webhook', WebHookController::class);
