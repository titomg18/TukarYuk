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
    // Dashboard - Marketplace (semua barang)
    Route::get('/dashboard', [ItemController::class, 'dashboard'])->name('dashboard');
    
    // Barang Saya - CRUD
    Route::get('/barang', [ItemController::class, 'index'])->name('barang.index');
    Route::post('/barang', [ItemController::class, 'store'])->name('barang.store');
    Route::get('/barang/{item}/edit', [ItemController::class, 'edit'])->name('barang.edit');
    Route::put('/barang/{item}', [ItemController::class, 'update'])->name('barang.update');
    Route::delete('/barang/{item}', [ItemController::class, 'destroy'])->name('barang.destroy');
    
    // Detail barang (public)
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
    
    // Swap routes
    Route::prefix('swaps')->name('swaps.')->group(function () {
        Route::get('/', [SwapController::class, 'index'])->name('index');
        // HAPUS ROUTE INI: Route::get('/create/{item}', [SwapController::class, 'create'])->name('create');
        Route::post('/', [SwapController::class, 'store'])->name('store');
        Route::get('/{swap}', [SwapController::class, 'show'])->name('show');
        
        // Actions - menggunakan POST untuk sekarang
        Route::post('/{swap}/accept', [SwapController::class, 'accept'])->name('accept');
        Route::post('/{swap}/reject', [SwapController::class, 'reject'])->name('reject');
        Route::post('/{swap}/cancel', [SwapController::class, 'cancel'])->name('cancel');
        Route::post('/{swap}/complete', [SwapController::class, 'complete'])->name('complete');
    });
    
    // API untuk mengambil barang milik user (untuk modal swap)
    Route::get('/api/user-items', function (\Illuminate\Http\Request $request) {
        $exclude = $request->query('exclude');
        
        $items = \App\Models\Item::where('user_id', auth()->id())
            ->where('status', 'available')
            ->when($exclude, function ($query, $exclude) {
                return $query->where('id', '!=', $exclude);
            })
            ->with('images')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'condition' => $item->condition,
                    'category' => $item->category,
                    'image_url' => $item->images->first() 
                        ? asset('storage/' . $item->images->first()->image_path) 
                        : asset('images/default-item.png'),
                    'has_image' => $item->images->first() ? true : false
                ];
            });
        
        return response()->json($items);
    });
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Route placeholder untuk fitur yang belum ada
    Route::view('/conversations', 'conversations')->name('conversations');
    Route::view('/favorites', 'favorites')->name('favorites');
    Route::view('/settings', 'settings')->name('settings');
});