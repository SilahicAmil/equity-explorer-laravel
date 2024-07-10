<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockTransactionController;

Route::view('/', 'welcome');

Route::get('/stocks', [StockController::class, 'index'])
    ->middleware('auth', 'verified')
    ->name('Market');

Route::post('/stock-transactions', [StockTransactionController::class, 'store'])
    ->middleware('auth', 'verified')
    ->name('stock-transactions.store');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
