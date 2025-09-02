<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'title',
        'response_open_ai_id',
    ];

    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }
}
