<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class History extends Model
{
    protected $hidden = ['entry_id'];

    protected $casts = [
        'data' => 'array',
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }
}
