<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    protected $fillable = [
        'message',
        'side',
        'message_open_ai_id',
        'room_id',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
