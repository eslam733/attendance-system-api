<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['name' => 'auth', 'prefix' => 'auth'], function () {
    Route::post('login', [UserController::class, 'login'])->name('auth.login');
    Route::post('register', [UserController::class, 'register'])->name('auth.register');
});

Route::group(['middleware' => 'auth:sanctum', 'prefix' => 'attendance', 'name' => 'attendance'], function () {
    Route::get('check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::put('check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
    Route::post('get-total-hours', [AttendanceController::class, 'getTotalHours'])->name('attendance.get-total-hours');
});


