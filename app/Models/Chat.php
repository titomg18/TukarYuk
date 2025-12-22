<?php
// app/Models/Chat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'swap_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function swap()
    {
        return $this->belongsTo(Swap::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Scope untuk chat yang belum dibaca
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Scope untuk chat antara dua user dalam swap tertentu
    public function scopeBetweenUsers($query, $swapId, $userId1, $userId2)
    {
        return $query->where('swap_id', $swapId)
            ->where(function($q) use ($userId1, $userId2) {
                $q->where(function($sub) use ($userId1, $userId2) {
                    $sub->where('sender_id', $userId1)
                        ->where('receiver_id', $userId2);
                })->orWhere(function($sub) use ($userId1, $userId2) {
                    $sub->where('sender_id', $userId2)
                        ->where('receiver_id', $userId1);
                });
            });
    }

    // Mark as read
    public function markAsRead()
    {
        $this->update(['is_read' => true]);
    }
}