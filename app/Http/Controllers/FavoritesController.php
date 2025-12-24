<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Item;

class FavoritesController extends Controller
{
    public function index()
    {
        $favoriteItems = Favorite::where('user_id', auth()->id())
            ->with(['item.images', 'item.user'])
            ->get();

        $incomingSwapsCount = 0; // keep compatibility with views
        $stats = ['active_swaps' => 0];

        return view('favorites', compact('favoriteItems', 'incomingSwapsCount', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate(['item_id' => 'required|integer|exists:items,id']);

        $itemId = $request->input('item_id');

        $favorite = Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'item_id' => $itemId,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['message' => 'Favorit ditambahkan', 'favorite' => $favorite], 201);
        }

        return redirect()->route('favorites')->with('success', 'Barang ditambahkan ke favorit');
    }

    public function destroy(Favorite $favorite)
    {
        if ($favorite->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $favorite->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['message' => 'Dihapus'], 200);
        }

        return redirect()->route('favorites')->with('success', 'Barang dihapus dari favorit');
    }

    public function removeAll()
    {
        Favorite::where('user_id', auth()->id())->delete();
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['message' => 'Semua favorit dihapus'], 200);
        }
        return redirect()->route('favorites')->with('success', 'Semua favorit dihapus');
    }
}
