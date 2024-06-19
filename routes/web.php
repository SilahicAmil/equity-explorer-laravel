<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockDataController;

Route::view('/', 'welcome');

Route::get('/stocks', [StockDataController::class, 'index']);


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
