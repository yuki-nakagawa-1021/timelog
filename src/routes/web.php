<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\AttendanceController;

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm']);

Route::middleware('auth')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::post('/attendance/start', [AttendanceController::class, 'start']);
    Route::post('/attendance/end', [AttendanceController::class, 'end']);
    Route::post('/attendance/break/start', [AttendanceController::class, 'breakStart']);
    Route::post('/attendance/break/end', [AttendanceController::class, 'breakEnd']);
     Route::get('/attendance/list', [AttendanceController::class, 'list']);
    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'show']);
});