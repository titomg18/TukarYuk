<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Swap extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'requested_item_id',
        'offered_item_id',
        'requester_id',
        'owner_id',
        'type',
        'status',
        'message',
        'meeting_location',
        'meeting_time',
        'completed_at'
    ];

    protected $casts = [
        'meeting_time' => 'datetime',
        'completed_at' => 'datetime'
    ];

    const TYPE_SWAP = 'swap';
    const TYPE_FREE = 'free';

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function requestedItem()
    {
        return $this->belongsTo(Item::class, 'requested_item_id');
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
}