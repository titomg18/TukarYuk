<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemImage;
use App\Models\Swap;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    // ====================
    // DASHBOARD (Marketplace)
    // ====================
    public function dashboard()
    {
        // Ambil semua barang yang available (bisa dilihat semua user)
        $items = Item::where('status', 'available')
            ->with(['images', 'user'])
            ->latest()
            ->paginate(12);

        $stats = [
            'total_items' => Item::where('user_id', auth()->id())->count(),
            'active_swaps' => Item::where('user_id', auth()->id())->where('status', 'in_swap')->count(),
            'completed_swaps' => Item::where('user_id', auth()->id())->where('status', 'completed')->count(),
            'available_items' => Item::where('user_id', auth()->id())->where('status', 'available')->count(),
        ];

        // Untuk notifikasi
        $incomingSwapsCount = Swap::where('owner_id', auth()->id())
            ->where('status', 'pending')
            ->count();

        // Ambil daftar favorit milik user saat ini (map item_id => favorite_id)
        $userFavorites = Favorite::where('user_id', auth()->id())->pluck('id', 'item_id')->toArray();

        return view('dashboard', compact('items', 'stats', 'incomingSwapsCount', 'userFavorites'));
    }

    // ====================
    // BARANG SAYA (My Items)
    // ====================
    public function index()
    {
        $items = Item::where('user_id', auth()->id())
            ->with('images')
            ->latest()
            ->paginate(12);

        $stats = [
            'total_items' => Item::where('user_id', auth()->id())->count(),
            'active_swaps' => Item::where('user_id', auth()->id())->where('status', 'in_swap')->count(),
            'completed_swaps' => Item::where('user_id', auth()->id())->where('status', 'completed')->count(),
            'available_items' => Item::where('user_id', auth()->id())->where('status', 'available')->count(),
        ];

        return view('barang', compact('items', 'stats'));
    }

    // ====================
    // TAMBAH BARANG
    // ====================
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'condition' => 'required|in:baru,bekas_layak',
            'description' => 'nullable|string',
            'type' => 'required|in:swap,free',
            'location' => 'nullable|string',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $item = Item::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'condition' => $request->condition,
            'type' => $request->type,
            'location' => $request->location ?? auth()->user()->location,
            'status' => 'available',
        ]);

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('items', 'public');
                ItemImage::create([
                    'item_id' => $item->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    // ====================
    // DETAIL BARANG
    // ====================
    public function show(Item $item)
    {
        $item->load(['images', 'user']);
        return view('items.show', compact('item'));
    }

    // ====================
    // EDIT BARANG
    // ====================
    public function edit(Item $item)
    {
        // Authorization: cek apakah user pemilik barang
        if ($item->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('items.edit', compact('item'));
    }

    // ====================
    // UPDATE BARANG
    // ====================
    public function update(Request $request, Item $item)
    {
        // Authorization
        if ($item->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'condition' => 'required|in:baru,bekas_layak',
            'description' => 'nullable|string',
            'type' => 'required|in:swap,free',
            'status' => 'required|in:available,in_swap,completed',
            'location' => 'nullable|string',
        ]);

        $item->update($request->all());

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    // ====================
    // HAPUS BARANG
    // ====================
    public function destroy(Item $item)
    {
        // Authorization
        if ($item->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Hapus gambar terlebih dahulu
        foreach ($item->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $item->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
