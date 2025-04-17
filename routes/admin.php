<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Admin\Auth\AuthenticatedController;


Route::middleware('auth:admin')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Home');
    })->name('home');
});

require __DIR__ . '/admin/auth.php';
