<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StaffAuthController;

// Staff Auth Routes
Route::prefix('staff')->name('staff.')->group(function () {
    Route::get('register', [StaffAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [StaffAuthController::class, 'register']);
    Route::get('login', [StaffAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [StaffAuthController::class, 'login']);
    Route::post('logout', [StaffAuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', function () {
        return view('staff_auth.dashboard');
    })->middleware('auth:staff')->name('dashboard');
});
