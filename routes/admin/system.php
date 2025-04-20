<?php

use App\Http\Controllers\Admin\Permission\Permission;
use Illuminate\Support\Facades\Route;

Route::prefix('system')->name('system.')->middleware('auth:admin')->group(function () {

    Route::prefix('config')->name('config.')->group(function () {

        Route::get('main', function () {
            return inertia('system/config/Main');
        })->name('main');

        Route::get('admin', function () {
            return inertia('system/config/Admin');
        })->name('admin');

    });

    Route::prefix('permission')->name('permission.')->group(function () {

        Route::get('index', [Permission::class, 'index'])->name('index');

    });

});
