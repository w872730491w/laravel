<?php

use Illuminate\Support\Facades\Route;

Route::prefix('system')->middleware('auth:admin')->group(function () {
    Route::prefix('config')->group(function () {
        Route::get('main', function () {
            return inertia('system/config/Main');
        })->name('system.config.main');
        Route::get('admin', function () {
            return inertia('system/config/Admin');
        })->name('system.config.admin');
    });
});
