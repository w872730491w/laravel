<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return '123';
})->name('home');
