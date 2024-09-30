<?php

use App\Http\Controllers\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->as('auth.')->group(function () {
    Route::get('check', [AuthController::class, 'check'])->name('check');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::delete('logout', [AuthController::class, 'logout'])->name('logout');
});
