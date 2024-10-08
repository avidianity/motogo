<?php

use App\Enums\Role;
use App\Http\Controllers\V1\Administrator\UserController;
use App\Http\Controllers\V1\AuthController;
use Illuminate\Support\Facades\Route;

$admin = Role::ADMIN();

Route::prefix('auth')->as('auth.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('register', [AuthController::class, 'register'])->name('register');

    Route::middleware('auth')->group(function () {
        Route::get('check', [AuthController::class, 'check'])->name('check');
        Route::delete('logout', [AuthController::class, 'logout'])->name('logout');
    });
});

Route::prefix('administrator')->as('administrator.')->middleware(['auth', "role:{$admin}"])->group(function () {
    Route::apiResource('users', UserController::class);
});
