<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ThaiDController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('oidc')->group(function () {
    Route::get('thaid/redirect', [ThaiDController::class, 'redirect'])->name('oidc.thaid.redirect'); ;
    Route::get('thaid/callback', [ThaiDController::class, 'callback'])->name('oidc.thaid.callback');
    Route::get('thaid/introspect', [ThaiDController::class, 'introspect'])->name('oidc.thaid.introspect');
});

Route::get('/get-uuid', function () {
    return response()->json(['uuid' => Str::uuid()]);
});

require __DIR__.'/auth.php';
