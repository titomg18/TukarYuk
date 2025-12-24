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
    public function dashboard(Request $request)
    {
        // Filtering params
        $q = $request->query('q');
        $category = $request->query('category');
        $type = $request->query('type');

        // Build query
        $query = Item::where('status', 'available')->with(['images', 'user']);

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($type) {
            $query->where('type', $type);
        }

        $items = $query->latest()->paginate(12)->appends($request->except('page'));

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

        // provide distinct categories and types for filter selects
        $categories = Item::select('category')->distinct()->pluck('category');
        $types = Item::select('type')->distinct()->pluck('type');

        return view('dashboard', compact('items', 'stats', 'incomingSwapsCount', 'userFavorites', 'categories', 'types'));
    }

    // ====================
    // BARANG SAYA (My Items)
    // ====================
    public function index(Request $request)
    {
        $q = $request->query('q');
        $category = $request->query('category');
        $type = $request->query('type');

        $query = Item::where('user_id', auth()->id())->with('images');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($type) {
            $query->where('type', $type);
        }

        $items = $query->latest()->paginate(12)->appends($request->except('page'));

        $stats = [
            'total_items' => Item::where('user_id', auth()->id())->count(),
            'active_swaps' => Item::where('user_id', auth()->id())->where('status', 'in_swap')->count(),
            'completed_swaps' => Item::where('user_id', auth()->id())->where('status', 'completed')->count(),
            'available_items' => Item::where('user_id', auth()->id())->where('status', 'available')->count(),
        ];

        $categories = Item::select('category')->where('user_id', auth()->id())->distinct()->pluck('category');
        $types = Item::select('type')->where('user_id', auth()->id())->distinct()->pluck('type');

        return view('barang', compact('items', 'stats', 'categories', 'types'));
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

        $item->update($request->only(['title','description','category','condition','type','status','location']));

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => 'Barang berhasil diperbarui', 'item' => $item], 200);
        }

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

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['message' => 'Barang berhasil dihapus'], 200);
        }

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }
}
