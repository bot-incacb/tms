<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Translate extends Model
{
    protected $hidden = [
        'entry_id',
    ];

    protected $casts = [
        'quality' => 'integer',
        'is_original' => 'boolean',
    ];

    public function entry(): BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }
}
