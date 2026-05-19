<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\Admin\AttendanceController as AdminAttendanceController;
use App\Http\Controllers\Admin\StaffController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role === 'admin'
            ? redirect('/admin/attendance/list')
            : redirect('/attendance');
    }
    return redirect('/login');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/attendance');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm']);

Route::middleware(['auth', 'verified'])->group(function () {
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
});
