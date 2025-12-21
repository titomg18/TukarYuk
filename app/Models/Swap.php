<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Swap extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'offered_item_id',
        'offered_item_name', // Tambahkan untuk support barang custom
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

    public function offeredItem()
    {
        return $this->belongsTo(Item::class, 'offered_item_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // ... method lainnya
}
