<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\ComplaintHistoryController;
use App\Http\Controllers\ComplaintSearchController;
use App\Http\Controllers\ComplaintTypeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\GovernmentEntityController;
use App\Http\Controllers\IncomingComplaintsController;
use App\Http\Controllers\LoginController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [RegisterController::class, 'register'])->middleware('throttle:3,1');
Route::post('verify-otp', [RegisterController::class, 'verifyOtp'])->middleware('throttle:3,2');
Route::post('login', [LoginController::class, 'login'])->middleware('throttle:5,1');
Route::get('session/check', [LoginController::class, 'checkSession'])->middleware('auth:sanctum');
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum', 'throttle:3,1');
Route::post('store/complaint', [ComplaintController::class, 'store'])->middleware('auth:sanctum', 'throttle:10,1');
Route::get('show_all_my_complaints', [ComplaintController::class, 'show_all_my_complaints'])->middleware('auth:sanctum');
Route::get('filter_complant_status', [ComplaintController::class, 'filter_complant_status'])->middleware('auth:sanctum');
Route::get('findMyComplaintByReference', [ComplaintController::class, 'findMyComplaintByReference'])->middleware('auth:sanctum');
Route::get('/complaint/id', [ComplaintController::class, 'findMyComplaintById'])->middleware('auth:sanctum');


Route::get('government-entities', [GovernmentEntityController::class, 'index']);
Route::prefix('employees')->middleware(['auth:sanctum', 'role:المشرف العام'])->group(function () {
    Route::get('all', [EmployeeController::class, 'index']);
    Route::post('/create/{governmentEntityId}', [EmployeeController::class, 'createEmployee']);
    Route::post('/update/{employeeId}', [EmployeeController::class, 'updateEmployee']);
    Route::delete('/delete/{employeeId}', [EmployeeController::class, 'deleteEmployee']);
});
Route::prefix('complaints')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/search', [ComplaintSearchController::class, 'searchByReference']);
    Route::get('/all', [IncomingComplaintsController::class, 'all'])->middleware('role:المشرف العام');
});
Route::prefix('complaints')->middleware(['auth:sanctum', 'role:الموظف'])->group(function () {
    Route::get('/incoming', [IncomingComplaintsController::class, 'index']);
    Route::get('/incoming/{complaintId}', [IncomingComplaintsController::class, 'show']);
    Route::patch('/toggle-status/{id}', [ComplaintHistoryController::class, 'toggleStatus']);
    Route::patch('/status/{id}', [ComplaintHistoryController::class, 'updateStatus']);
});


Route::get('complaint-types', [ComplaintTypeController::class, 'index']);
