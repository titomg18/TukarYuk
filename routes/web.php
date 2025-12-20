<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SwapController;

// Halaman utama
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Routes yang membutuhkan authentication
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [ItemController::class, 'dashboard'])->name('dashboard');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth'])->group(function () {
    // ... route lainnya ...
    
    Route::prefix('swap')->name('swap.')->group(function () {
        Route::get('/', [SwapController::class, 'index'])->name('index');
        Route::get('/create', [SwapController::class, 'create'])->name('create');
        Route::post('/', [SwapController::class, 'store'])->name('store');
        Route::get('/{id}', [SwapController::class, 'show'])->name('show');
        Route::post('/{id}/accept', [SwapController::class, 'accept'])->name('accept');
        Route::post('/{id}/reject', [SwapController::class, 'reject'])->name('reject');
        Route::post('/{id}/cancel', [SwapController::class, 'cancel'])->name('cancel');
        Route::post('/{id}/complete', [SwapController::class, 'complete'])->name('complete');
    });
});