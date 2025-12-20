<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Swap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwapController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $incomingSwaps = Swap::where('owner_id', $user->id)
            ->with(['requester', 'item', 'offeredItem'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $outgoingSwaps = Swap::where('requester_id', $user->id)
            ->with(['owner', 'item', 'offeredItem'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $completedSwaps = Swap::where(function($query) use ($user) {
                $query->where('owner_id', $user->id)
                      ->orWhere('requester_id', $user->id);
            })
            ->where('status', 'completed')
            ->with(['requester', 'owner', 'item', 'offeredItem'])
            ->orderBy('completed_at', 'desc')
            ->get();
            
        return view('swap', compact('incomingSwaps', 'outgoingSwaps', 'completedSwaps'));
    }
    
    public function create(Request $request)
    {
        $item = Item::findOrFail($request->item_id);
        $userItems = Item::where('user_id', Auth::id())
            ->where('status', 'available')
            ->where('id', '!=', $request->item_id)
            ->get();
            
        return view('swap.create', compact('item', 'userItems'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'offered_item_id' => 'required|exists:items,id',
            'message' => 'nullable|string|max:500',
            'type' => 'required|in:swap,free'
        ]);
        
        $item = Item::findOrFail($request->item_id);
        
        if ($item->status !== 'available') {
            return redirect()->back()->with('error', 'Item tidak tersedia untuk ditukar.');
        }
        
        if ($item->user_id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menukar item Anda sendiri.');
        }
        
        $existingSwap = Swap::where('item_id', $item->id)
            ->where('requester_id', Auth::id())
            ->whereIn('status', ['pending', 'accepted'])
            ->first();
            
        if ($existingSwap) {
            return redirect()->back()->with('error', 'Anda sudah mengajukan penukaran untuk item ini.');
        }
        
        $swap = Swap::create([
            'item_id' => $item->id,
            'requested_item_id' => $item->id,
            'offered_item_id' => $request->offered_item_id,
            'requester_id' => Auth::id(),
            'owner_id' => $item->user_id,
            'type' => $request->type,
            'status' => 'pending',
            'message' => $request->message
        ]);
        
        Item::where('id', $request->offered_item_id)->update(['status' => 'in_swap']);
        
        return redirect()->route('swap.index')->with('success', 'Penukaran berhasil diajukan!');
    }
    
    public function accept($id)
    {
        $swap = Swap::findOrFail($id);
        
        if ($swap->owner_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk tindakan ini.');
        }
        
        $swap->update(['status' => 'accepted']);
        
        Item::where('id', $swap->item_id)->update(['status' => 'in_swap']);
        Item::where('id', $swap->offered_item_id)->update(['status' => 'in_swap']);
        
        return redirect()->back()->with('success', 'Tawaran penukaran telah diterima.');
    }
    
    public function reject($id)
    {
        $swap = Swap::findOrFail($id);
        
        if ($swap->owner_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk tindakan ini.');
        }
        
        $swap->update(['status' => 'rejected']);
        
        Item::where('id', $swap->offered_item_id)->update(['status' => 'available']);
        
        return redirect()->back()->with('success', 'Tawaran penukaran telah ditolak.');
    }
    
    public function cancel($id)
    {
        $swap = Swap::findOrFail($id);
        
        if ($swap->requester_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk membatalkan penukaran ini.');
        }
        
        $swap->update(['status' => 'cancelled']);
        
        Item::where('id', $swap->offered_item_id)->update(['status' => 'available']);
        
        return redirect()->back()->with('success', 'Penukaran telah dibatalkan.');
    }
    
    public function complete($id)
    {
        $swap = Swap::findOrFail($id);
        
        if (!in_array(Auth::id(), [$swap->owner_id, $swap->requester_id])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk tindakan ini.');
        }
        
        $swap->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        Item::where('id', $swap->item_id)->update(['status' => 'completed']);
        Item::where('id', $swap->offered_item_id)->update(['status' => 'completed']);
        
        return redirect()->back()->with('success', 'Penukaran telah diselesaikan.');
    }
    
    public function show($id)
    {
        $swap = Swap::with(['item', 'offeredItem', 'requester', 'owner'])
            ->findOrFail($id);
            
        return view('swap.show', compact('swap'));
    }
}