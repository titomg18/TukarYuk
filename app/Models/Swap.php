<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Swap extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'offered_item_id', // Hapus requested_item_id
        'requester_id',
        'owner_id',
        'type',
        'status',
        'message',
        'meeting_location',
        'meeting_time',
        'completed_at'
    ];

    // ... method lainnya tetap sama, tapi hapus requestedItem()
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    // Hapus method ini:
    // public function requestedItem()
    // {
    //     return $this->belongsTo(Item::class, 'requested_item_id');
    // }

    public function offeredItem()
    {
        return $this->belongsTo(Item::class, 'offered_item_id');
    }

    // ... method lainnya
}