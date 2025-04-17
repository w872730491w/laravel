<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AuthenticatedController;

Route::middleware('guest:admin')->group(function () {
    Route::get('login', [AuthenticatedController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedController::class, 'store']);
});

Route::middleware('auth:admin')->group(function () {
    Route::post('logout', [AuthenticatedController::class, 'destroy'])->name('logout');
});
