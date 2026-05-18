<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\StaffController;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect('/admin/attendance/list')
            : redirect('/attendance');
    }
    return redirect('/login');
});


Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm']);

Route::middleware('auth')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index']);
    Route::post('/attendance/start', [AttendanceController::class, 'start']);
    Route::post('/attendance/end', [AttendanceController::class, 'end']);
    Route::post('/attendance/break/start', [AttendanceController::class, 'breakStart']);
    Route::post('/attendance/break/end', [AttendanceController::class, 'breakEnd']);
    Route::get('/attendance/list', [AttendanceController::class, 'list']);
    Route::get('/attendance/detail/{id}', [AttendanceController::class, 'show']);
    Route::post('/attendance/request/{id}', [AttendanceController::class, 'requestUpdate']);
    Route::get('/stamp_correction_request/list', [AttendanceController::class, 'requestList']);
});

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/attendance/list', [AdminAttendanceController::class, 'index']);
    Route::get('/attendance/detail/{id}', [AdminAttendanceController::class, 'show']);
    Route::post('/attendance/{id}', [AdminAttendanceController::class, 'update']);
    Route::get('/staff/list', [StaffController::class, 'index']);
    Route::get('/attendance/staff/{id}', [AdminAttendanceController::class, 'show']);
    Route::post('/attendance/staff/{id}', [AdminAttendanceController::class, 'update']);
    Route::get('/attendance/staff/{id}', [AdminAttendanceController::class, 'staffAttendance']);
    Route::get('/attendance/staff/{id}/csv', [AdminAttendanceController::class, 'exportCsv']);
    Route::get('/stamp_correction_request/list', [AdminAttendanceController::class, 'requestList']);
    Route::get('/stamp_correction_request/approve/{id}',[AdminAttendanceController::class, 'showRequest']);
    Route::post('/stamp_correction_request/approve/{id}',[AdminAttendanceController::class, 'approve']);
    Route::get('/stamp_correction_request/approve/{id}',[AdminAttendanceController::class, 'showRequest']);
});
