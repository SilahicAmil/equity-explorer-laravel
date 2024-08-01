<?php

use App\Livewire\Stocks\StocksDashboard;
use Illuminate\Support\Facades\Route;


Route::view('/', 'welcome');

Route::get('/stocks', [\App\Http\Controllers\StockController::class, 'index'])
    ->middleware('auth', 'verified')
    ->name('stocks');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
