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
    
    // PERBAIKAN: Gunakan route model binding
    public function create(Item $item)
    {
        // Cek apakah item available
        if ($item->status !== 'available') {
            return redirect()->route('dashboard')->with('error', 'Item tidak tersedia untuk ditukar.');
        }
        
        // Cek apakah item milik sendiri
        if ($item->user_id === Auth::id()) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak dapat menukar item Anda sendiri.');
        }
        
        $userItems = Item::where('user_id', Auth::id())
            ->where('status', 'available')
            ->where('id', '!=', $item->id)
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
        
        // Cek apakah offered_item milik user dan available
        $offeredItem = Item::where('id', $request->offered_item_id)
            ->where('user_id', Auth::id())
            ->where('status', 'available')
            ->first();
            
        if (!$offeredItem) {
            return redirect()->back()->with('error', 'Item yang Anda tawarkan tidak tersedia atau bukan milik Anda.');
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
            'offered_item_id' => $request->offered_item_id,
            'requester_id' => Auth::id(),
            'owner_id' => $item->user_id,
            'type' => $request->type,
            'status' => 'pending',
            'message' => $request->message
        ]);
        
        // Update status offered item menjadi in_swap
        Item::where('id', $request->offered_item_id)->update(['status' => 'in_swap']);
        
        return redirect()->route('swaps.index')->with('success', 'Penukaran berhasil diajukan!');
    }
    
    // PERBAIKAN: Tambahkan validasi status
    public function accept($id)
    {
        $swap = Swap::findOrFail($id);
        
        if ($swap->owner_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk tindakan ini.');
        }
        
        // Cek apakah swap masih pending
        if ($swap->status !== 'pending') {
            return redirect()->back()->with('error', 'Tawaran sudah diproses sebelumnya.');
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
        
        // Cek apakah swap masih pending
        if ($swap->status !== 'pending') {
            return redirect()->back()->with('error', 'Tawaran sudah diproses sebelumnya.');
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
        
        // Cek apakah swap masih pending atau accepted
        if (!in_array($swap->status, ['pending', 'accepted'])) {
            return redirect()->back()->with('error', 'Penukaran tidak dapat dibatalkan.');
        }
        
        $swap->update(['status' => 'cancelled']);
        
        // Kembalikan status offered item
        Item::where('id', $swap->offered_item_id)->update(['status' => 'available']);
        
        // Jika status sebelumnya accepted, kembalikan juga item milik owner
        if ($swap->status === 'accepted') {
            Item::where('id', $swap->item_id)->update(['status' => 'available']);
        }
        
        return redirect()->back()->with('success', 'Penukaran telah dibatalkan.');
    }
    
    public function complete($id)
    {
        $swap = Swap::findOrFail($id);
        
        if (!in_array(Auth::id(), [$swap->owner_id, $swap->requester_id])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk tindakan ini.');
        }
        
        // Cek apakah swap sudah accepted
        if ($swap->status !== 'accepted') {
            return redirect()->back()->with('error', 'Hanya swap yang sudah diterima yang bisa diselesaikan.');
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
            
        // Authorization: cek apakah user terlibat dalam swap
        if (!in_array(Auth::id(), [$swap->owner_id, $swap->requester_id])) {
            abort(403, 'Unauthorized action.');
        }
            
        return view('swap.show', compact('swap'));
    }
}