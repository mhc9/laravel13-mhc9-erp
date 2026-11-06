<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('oidc')->group(function () {
    Route::get('thaid/redirect', [App\Http\Controllers\ThaiDController::class, 'redirect'])->name('oidc.thaid.redirect');
    Route::get('thaid/callback', [App\Http\Controllers\ThaiDController::class, 'callback'])->name('oidc.thaid.callback');
    Route::get('thaid/introspect', [App\Http\Controllers\ThaiDController::class, 'introspect'])->name('oidc.thaid.introspect');

    Route::get('mojoauth/redirect', [App\Http\Controllers\MojoAuthController::class, 'redirect'])->name('oidc.mojoauth.redirect');
    Route::get('mojoauth/callback', [App\Http\Controllers\MojoAuthController::class, 'callback'])->name('oidc.mojoauth.callback');
});

Route::get('/get-uuid', function () {
    return response()->json(['uuid' => Str::uuid()]);
});

require __DIR__.'/auth.php';
