<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:admin')->group(function () {
    Route::get('login', [AuthenticatedController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedController::class, 'store'])->name('login');;
});

Route::middleware('auth:admin')->group(function () {
    Route::post('logout', [AuthenticatedController::class, 'destroy'])->name('logout');
});
