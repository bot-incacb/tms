<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    protected $hidden = [
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
