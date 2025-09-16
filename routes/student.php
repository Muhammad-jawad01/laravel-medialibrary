<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StudentAuthController;

// Student Auth Routes
Route::prefix('student')->name('student.')->group(function () {
    Route::get('register', [StudentAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [StudentAuthController::class, 'register']);
    Route::get('login', [StudentAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [StudentAuthController::class, 'login']);
    Route::post('logout', [StudentAuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', function () {
        return view('student_auth.dashboard');
    })->middleware('auth:student')->name('dashboard');
});
