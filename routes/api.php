<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ComplaintTypeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GovernmentEntityController;
use App\Http\Controllers\LoginController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [RegisterController::class,'register']);
Route::post('verify-otp', [RegisterController::class,'verifyOtp']);
Route::post('login',[LoginController::class,'login']);
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::post('store/complaint', [ComplaintController::class,'store'])->middleware('auth:sanctum');


Route::get('government-entities', [GovernmentEntityController::class, 'index']);
Route::prefix('employees')->middleware(['auth:sanctum', 'role:Super Admin'])->group(function () {
    Route::post('create/{governmentEntityId}', [EmployeeController::class, 'createEmployee']);
    Route::post('assign-government-entity/{employeeId}', [EmployeeController::class, 'updateGovernmentEntity']);
});
Route::get('complaint-types', [ComplaintTypeController::class, 'index']);
