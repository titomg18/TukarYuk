<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\SwapController;
use App\Http\Controllers\ChatController;

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
    Route::prefix('barang')->name('barang.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('index');
        Route::post('/', [ItemController::class, 'store'])->name('store');
        Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('edit');
        Route::put('/{item}', [ItemController::class, 'update'])->name('update');
        Route::delete('/{item}', [ItemController::class, 'destroy'])->name('destroy');
        
        // Tambahkan route untuk create (untuk halaman form)
        Route::get('/create', function () {
            return view('items.create');
        })->name('create');
        
        // Detail barang milik sendiri (jika ingin bedakan dengan public)
        Route::get('/{item}', [ItemController::class, 'show'])->name('show');
    });
    
    // Detail barang (public) - diakses dengan id saja
    Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show');
    
    // Swap routes
    Route::prefix('swaps')->name('swaps.')->group(function () {
        Route::get('/', [SwapController::class, 'index'])->name('index');
        Route::get('/create/{item}', [SwapController::class, 'create'])->name('create'); // Kembalikan route create
        Route::post('/', [SwapController::class, 'store'])->name('store');
        Route::get('/{swap}', [SwapController::class, 'show'])->name('show');
        
        // Actions
        Route::post('/{swap}/accept', [SwapController::class, 'accept'])->name('accept');
        Route::post('/{swap}/reject', [SwapController::class, 'reject'])->name('reject');
        Route::post('/{swap}/cancel', [SwapController::class, 'cancel'])->name('cancel');
        Route::post('/{swap}/complete', [SwapController::class, 'complete'])->name('complete');
    });
    
    // Chat routes
    Route::prefix('chats')->name('chats.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/{swap}', [ChatController::class, 'show'])->name('show');
        Route::post('/{swap}', [ChatController::class, 'store'])->name('store');
        Route::post('/{swap}/read', [ChatController::class, 'markAsRead'])->name('read');
        Route::get('/unread/count', [ChatController::class, 'getUnreadCount'])->name('unread.count');
    });
    
    // API untuk mengambil barang milik user (untuk modal swap)
    Route::get('/api/user-items', function (\Illuminate\Http\Request $request) {
        try {
            $exclude = $request->query('exclude');
            
            $items = \App\Models\Item::where('user_id', auth()->id())
                ->where('status', 'available')
                ->when($exclude, function ($query, $exclude) {
                    return $query->where('id', '!=', $exclude);
                })
                ->with('images')
                ->get()
                ->map(function ($item) {
                    $image = $item->images->first();
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'condition' => $item->condition,
                        'category' => $item->category,
                        'image_url' => $image 
                            ? asset('storage/' . $image->image_path) 
                            : asset('images/default-item.png'),
                        'has_image' => $image ? true : false
                    ];
                });
            
            return response()->json($items, 200);
        } catch (\Exception $e) {
            \Log::error('Error fetching user items: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal memuat data barang'], 500);
        }
    })->name('api.user.items');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Update route placeholder conversations ke route yang sebenarnya
    Route::get('/conversations', [ChatController::class, 'index'])->name('conversations');
    
    // Favorites routes
    Route::get('/favorites', [\App\Http\Controllers\FavoritesController::class, 'index'])->name('favorites');
    Route::post('/favorites', [\App\Http\Controllers\FavoritesController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{favorite}', [\App\Http\Controllers\FavoritesController::class, 'destroy'])->name('favorites.destroy');
    Route::delete('/favorites/remove-all', [\App\Http\Controllers\FavoritesController::class, 'removeAll'])->name('favorites.removeAll');

    // Placeholder views
    Route::view('/settings', 'settings')->name('settings');
    Route::view('/profile', 'profile')->name('profile');
});