<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ComplaintTypeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GovernmentEntityController;
use App\Http\Controllers\IncomingComplaintsController;
use App\Http\Controllers\LoginController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [RegisterController::class,'register']);
Route::post('verify-otp', [RegisterController::class,'verifyOtp']);
Route::post('login',[LoginController::class,'login']);
Route::get('session/check', [LoginController::class, 'checkSession'])->middleware('auth:sanctum');
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::post('store/complaint', [ComplaintController::class,'store'])->middleware('auth:sanctum');
Route::get('show_all_my_complaints', [ComplaintController::class,'show_all_my_complaints'])->middleware('auth:sanctum');
Route::get('filter_complant_status', [ComplaintController::class,'filter_complant_status'])->middleware('auth:sanctum');
Route::get('findMyComplaintByReference', [ComplaintController::class,'findMyComplaintByReference'])->middleware('auth:sanctum');
Route::get('/complaint/id', [ComplaintController::class, 'findMyComplaintById'])->middleware('auth:sanctum');


Route::get('government-entities', [GovernmentEntityController::class, 'index']);
Route::prefix('employees')->middleware(['auth:sanctum', 'role:Super Admin'])->group(function () {
    Route::post('create/{governmentEntityId}', [EmployeeController::class, 'createEmployee']);
    Route::post('assign-government-entity/{employeeId}', [EmployeeController::class, 'updateGovernmentEntity']);
});

Route::get('complaints/incoming', [IncomingComplaintsController::class, 'index'])->middleware(['auth:sanctum', 'role:Employee']);

Route::get('complaint-types', [ComplaintTypeController::class, 'index']);
