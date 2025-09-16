<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GalleryController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/post', [FrontController::class, 'post'])->name('post');
Route::get('/post-create', [FrontController::class, 'create'])->name('create.post');
Route::post('/post-store', [FrontController::class, 'store'])->name('post.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/img', [ProfileController::class, 'getForm'])->name('profile.img');
    Route::post('/profile/img', [ProfileController::class, 'updateImage'])->name('profile.img.post');

    Route::resource('gallery', GalleryController::class);
});

require __DIR__ . '/auth.php';
require __DIR__ . '/student.php';
require __DIR__ . '/staff.php';
