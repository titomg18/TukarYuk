<?php
// app/Http\Controllers\ChatController.php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Swap;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Ambil semua swap yang melibatkan user dan sudah accepted
        $swaps = Swap::where(function($query) use ($user) {
                $query->where('owner_id', $user->id)
                      ->orWhere('requester_id', $user->id);
            })
            ->where('status', 'accepted')
            ->with(['item', 'offeredItem', 'owner', 'requester', 'latestChat'])
            ->orderBy('updated_at', 'desc')
            ->get();
            
        // Hitung total unread messages
        $totalUnread = Chat::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();
            
        return view('chats', compact('swaps', 'totalUnread'));
    }
    
    public function show($swapId)
    {
        $swap = Swap::with(['item', 'offeredItem', 'owner', 'requester'])
            ->findOrFail($swapId);
            
        // Authorization: cek apakah user terlibat dalam swap
        if (!in_array(Auth::id(), [$swap->owner_id, $swap->requester_id])) {
            abort(403, 'Unauthorized action.');
        }
        
        // Tentukan lawan bicara
        $otherUserId = (Auth::id() == $swap->owner_id) 
            ? $swap->requester_id 
            : $swap->owner_id;
            
        $otherUser = $swap->owner_id == $otherUserId 
            ? $swap->owner 
            : $swap->requester;
            
        // Ambil semua chat untuk swap ini
        $chats = Chat::where('swap_id', $swapId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();
            
        // Tandai pesan yang belum dibaca sebagai sudah dibaca
        Chat::where('swap_id', $swapId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        // Ambil semua swaps untuk sidebar
        $user = Auth::user();
        $swaps = Swap::where(function($query) use ($user) {
                $query->where('owner_id', $user->id)
                      ->orWhere('requester_id', $user->id);
            })
            ->where('status', 'accepted')
            ->with(['item', 'offeredItem', 'owner', 'requester', 'latestChat'])
            ->orderBy('updated_at', 'desc')
            ->get();
            
        $totalUnread = Chat::where('receiver_id', $user->id)
            ->where('is_read', false)
            ->count();
            
        return view('chats', compact(
            'swap', 'chats', 'otherUser', 
            'swaps', 'totalUnread'
        ));
    }
    
    public function store(Request $request, $swapId)
    {
        Log::info('ChatController@store called', ['swapId' => $swapId, 'user_id' => Auth::id(), 'input' => $request->all()]);

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $swap = Swap::findOrFail($swapId);
        
        // Authorization
        if (!in_array(Auth::id(), [$swap->owner_id, $swap->requester_id])) {
            abort(403, 'Unauthorized action.');
        }
        
        // Tentukan receiver
        $receiverId = (Auth::id() == $swap->owner_id) 
            ? $swap->requester_id 
            : $swap->owner_id;
            
        try {
            $chat = Chat::create([
                'swap_id' => $swapId,
                'sender_id' => Auth::id(),
                'receiver_id' => $receiverId,
                'message' => $request->message,
                'is_read' => false,
            ]);
            Log::info('Chat created', ['chat_id' => $chat->id, 'swap_id' => $swapId, 'sender' => Auth::id(), 'receiver' => $receiverId]);
        } catch (\Exception $e) {
            Log::error('Chat create failed: ' . $e->getMessage(), ['swapId' => $swapId, 'user_id' => Auth::id(), 'input' => $request->all()]);
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json(['success' => false, 'error' => 'Server error while saving message'], 500);
            }
            return redirect()->route('chats.show', $swapId)->with('error', 'Gagal menyimpan pesan.');
        }
        
        // Update updated_at timestamp di swap
        $swap->touch();
        
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'chat' => $chat->load('sender'),
                'message' => 'Pesan terkirim'
            ]);
        }
        
        return redirect()->route('chats.show', $swapId)->with('success', 'Pesan terkirim!');
    }
    
    public function markAsRead($swapId)
    {
        Chat::where('swap_id', $swapId)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
            
        return response()->json(['success' => true]);
    }
    
    public function getUnreadCount()
    {
        $count = Chat::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();
            
        return response()->json(['count' => $count]);
    }
}