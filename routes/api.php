<?php

use App\Http\Controllers\WebHookController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/webhook',[WebHookController::class,'webHook'])->name('webhook');
