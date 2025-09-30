<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ComplaintController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\IndicatorController;


Route::prefix('v1')->group(function () {

    Route::get('/test', function () {
        return response()->json([
            'message' => 'page',
            'data' => 'This is a test response',
            'user-list' => User::all()->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            }),
        ])->setStatusCode(200);
    });



    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('logout', [AuthController::class, 'logout']);
        // get posts
        Route::get('/candidate-list', [PostController::class, 'candidateList']);
        Route::get('/search-voter', [PostController::class, 'searchVoter']);
        Route::post('/allow-voter', [PostController::class, 'store']);
    });
});
