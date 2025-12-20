<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function dashboard()
    {
        $items = Item::where('user_id', auth()->id())
            ->with('images')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'total_items' => Item::where('user_id', auth()->id())->count(),
            'active_swaps' => Item::where('user_id', auth()->id())->where('status', 'in_swap')->count(),
            'completed_swaps' => Item::where('user_id', auth()->id())->where('status', 'completed')->count(),
            'available_items' => Item::where('user_id', auth()->id())->where('status', 'available')->count(),
        ];

        return view('dashboard', compact('items', 'stats'));
    }

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

        return redirect()->route('dashboard')->with('success', 'Barang berhasil ditambahkan!');
    }
}