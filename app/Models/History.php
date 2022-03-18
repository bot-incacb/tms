<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class History extends Model
{
    protected $hidden = ['translate_id', 'prev_id'];

    public function prev(): BelongsTo
    {
        return $this->belongsTo(History::class, 'prev_id');
    }
}
