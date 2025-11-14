<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ComplaintController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [RegisterController::class,'register']);
Route::post('verify-otp', [RegisterController::class,'verifyOtp']);
Route::post('store/complaint', [ComplaintController::class,'store'])->middleware('auth:sanctum');
