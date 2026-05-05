<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Middleware\EnsureClientIsResourceOwner;
use App\Http\Controllers\AuthController;
use App\Models\User;

Route::prefix('auth')->group(function() {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/user-for-client', function (Request $request) {
    return User::all();
})->middleware(EnsureClientIsResourceOwner::class);
