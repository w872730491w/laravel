<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('auth:admin')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Home');
    })->name('home');
});

require __DIR__.'/auth.php';
